<?php

/**
 * Класс-хелпер для работы с датами
 *
 * @version 0.1 21.08.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
class MDate extends MHelperBase
{
	
	/**
	 * Возвращает строку с относительным временем
	 * 
	 * @param integer $timestamp
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public static function timeAgo($datetime=null, $options=array())
	{
		$now       = time();
		$inSeconds = is_int($datetime) ? $datetime : strtotime($datetime);
		$backwards = ($inSeconds > $now);

		$format = 'd.m.Y';
		$end    = '';
		$short  = false;

		if (is_array($options)) {
			if (isset($options['format'])) {
				$format = $options['format'];
				unset($options['format']);
			}
			if (isset($options['end'])) {
				$end = $options['end'];
				unset($options['end']);
			}
			if (isset($options['short'])) {
				$short = $options['short'];
				unset($options['short']);
			}
		} else {
			$short = $options;
		}

		if ($backwards) {
			$futureTime = $inSeconds;
			$pastTime = $now;
		} else {
			$futureTime = $now;
			$pastTime = $inSeconds;
		}
		$diff = $futureTime - $pastTime;

		// Если больше недели, то принимаем в расчет длину месяцев
		if ($diff >= 604800) {
			$current = array();
			$date = array();

			list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $futureTime));

			list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $pastTime));
			$years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

			if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
				$months = 0;
				$years = 0;
			} else {
				if ($future['Y'] == $past['Y']) {
					$months = $future['m'] - $past['m'];
				} else {
					$years = $future['Y'] - $past['Y'];
					$months = $future['m'] + ((12 * $years) - $past['m']);

					if ($months >= 12) {
						$years = floor($months / 12);
						$months = $months - ($years * 12);
					}

					if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
						$years --;
					}
				}
			}

			if ($future['d'] >= $past['d']) {
				$days = $future['d'] - $past['d'];
			} else {
				$daysInPastMonth = date('t', $pastTime);
				$daysInFutureMonth = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

				if (!$backwards) {
					$days = ($daysInPastMonth - $past['d']) + $future['d'];
				} else {
					$days = ($daysInFutureMonth - $past['d']) + $future['d'];
				}

				if ($future['m'] != $past['m']) {
					$months --;
				}
			}

			if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)) {
				$months = 11;
				$years --;
			}

			if ($months >= 12) {
				$years = $years + 1;
				$months = $months - 12;
			}

			if ($days >= 7) {
				$weeks = floor($days / 7);
				$days = $days - ($weeks * 7);
			}
		} else {
			$years = $months = $weeks = 0;
			$days = floor($diff / 86400);

			$diff = $diff - ($days * 86400);

			$hours = floor($diff / 3600);
			$diff = $diff - ($hours * 3600);

			$minutes = floor($diff / 60);
			$diff = $diff - ($minutes * 60);
			$seconds = $diff;
		}
		$relativeDate = '';
		$diff = $futureTime - $pastTime;

		if ($diff > abs($now - strtotime($end))) {
			$relativeDate = sprintf('%s', date($format, $inSeconds));
		} else {
			if ($years > 0) {
				// years and months and days
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} год|{n} года|{n} лет|{n} года', $years);
				$relativeDate .= $months > 0 ? ($relativeDate ? ', ' : '') .  Yii::t(null, '{n} месяц|{n} месяца|{n} месяцев|{n} месяца', $months) : '';
				$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} неделю|{n} недели|{n} недель|{n} недели', $weeks) : '';
				$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} день|{n} дня|{n} дней|{n} дня', $days) : '';
			} elseif (abs($months) > 0) {
				// months, weeks and days
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} месяц|{n} месяца|{n} месяцев|{n} месяца', $months);
				$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} неделю|{n} недели|{n} недель|{n} недели', $weeks) : '';
				$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} день|{n} дня|{n} дней|{n} дня', $days) : '';
			} elseif (abs($weeks) > 0) {
				// weeks and days
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} неделю|{n} недели|{n} недель|{n} недели', $weeks);
				$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} день|{n} дня|{n} дней|{n} дня', $days) : '';
			} elseif (abs($days) > 0) {
				// days and hours
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} день|{n} дня|{n} дней|{n} дня', $days);
				$relativeDate .= $hours > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} час|{n} часа|{n} часов|{n} часа', $hours) : '';
			} elseif (abs($hours) > 0) {
				// hours and minutes
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} час|{n} часа|{n} часов|{n} часа', $hours);
				$relativeDate .= $minutes > 0 ? ($relativeDate ? ', ' : '') . Yii::t(null, '{n} минуту|{n} минуты|{n} минут|{n} минуты', $minutes) : '';
			} elseif (abs($minutes) > 0) {
				// minutes only
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} минуту|{n} минуты|{n} минут|{n} минуты', $minutes);
			} else {
				// seconds only
				$relativeDate .= ($relativeDate ? ', ' : '') . Yii::t(null, '{n} секунду|{n} секунды|{n} секунд|{n} секунды', $seconds);
			}

			if (!$backwards) {
				$relativeDate = sprintf('%s назад', $relativeDate);
			}
		}
		return $relativeDate;
	}

	/**
	 * Возвращает количество дней в месяце
	 * 
	 * @param integer $month
	 * @param integer $year
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function daysInMonth($month=null, $year=null)
	{
		if (!is_numeric($month) || $month<1 || $month>12)
			$month = date('m');
		
		if (!is_numeric($year) || $year<0)
			$year = date('Y');
		
		return date('t', mktime(0,0,0,$month,1,$year));
	}

}

