<?php

/**
 * Класс-хелпер для работы со строками
 *
 * @version 0.1 21.08.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
class MString extends MHelperBase
{
	
	/**
	 * Метод для перевода строки в верхний регистр
	 * 
	 * @param string $string
	 * @param string $charset
	 * @return string 
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function toUpper($string, $charset='UTF-8')
	{
		if ($this->_functionExists('mb_strtoupper')) {
			return mb_strtoupper($string, $charset);
		} else {
			return strtoupper($string);
		}
	}
	
	/**
	 * Метод для перевода строки в нижний регистр
	 * 
	 * @param string $string
	 * @param string $charset
	 * @return string 
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function toLower($string, $charset='UTF-8')
	{
		if ($this->_functionExists('mb_strtolower')) {
			return mb_strtolower($string, $charset);
		} else {
			return strtolower($string);
		}
	}
	
	/**
	 * Функция вырезает часть текста из строки
	 *
	 * @param string $string
	 * @param integer $start
	 * @param integer $length
	 * @param string $charset
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function substr($string, $start, $length, $charset='UTF-8')
	{
		if ($this->_functionExists('mb_substr')) {
			return mb_substr($string, $start, $length, $charset);
		} else {
			return substr($string, $start, $length);
		}
	}

	/**
	 * Метод возвращает последнее вхождение символа в строке
	 *
	 * @param string $string
	 * @param string $charset
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function strrchr($string, $charset='UTF-8')
	{
		if ($this->_functionExists('mb_strrchr')) {
			return mb_strrchr($string, $charset);
		} else {
			return strrchr($string);
		}
	}

	/**
	 * Метод возвращает длину строки
	 *
	 * @param string $string
	 * @param string $charset
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function len($string, $charset='UTF-8')
	{
		if ($this->_functionExists('mb_strlen')) {
			return mb_strlen($string, $charset);
		} else {
			return strlen($string);
		}
	}

	/**
	 * Метод удаляет слеши по краям строки
	 *
	 * @param string $string
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function trimSlashes($string)
	{
		return trim($string, '/');
	}

	/**
	 * Метод удаляет экранирование символов
	 *
	 * @param string|array $string
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function stripSlashes($string)
	{
		if (is_array($string)) {
			foreach ($string as $key=>$val) {
				$string[$key] = $this->stripSlashes($val);
			}
		} else {
			$string = stripslashes($string);
		}
		return $string;
	}

	/**
	 * Метод удаляет двонйные и одинарные кавычки из строки
	 *
	 * @param string $string
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function stripQuotes($string)
	{
		return str_replace(array('"', "'"), '', $string);
	}

	/**
	 * Метод заменяет двойнные и одинарные кавычки на их html-коды
	 *
	 * @param string $string
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function quotesToEntities($string)
	{
		return str_replace(array("\'","\"","'",'"'), array("&#39;","&quot;","&#39;","&quot;"), $string);
	}

	/**
	 * Метод заменяет двойные слеши на одинарные
	 * за исключением http://
	 *
	 * @param string $string
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function reduceDoubleSlashes($string)
	{
		return preg_replace("#(^|[^:])//+#", "\\1/", $string);
	}

	/**
	 * Метод удаляет всякую хрень
	 * Например было:
	 * qwerty, test,, foo, bar
	 * стало:
	 * qwerty, test, foo, bar
	 *
	 * @param string $string
	 * @param string $character
	 * @param bool $trim
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function reduceMultiples($string, $character=',', $trim=false)
	{
		$string = preg_replace('#'.preg_quote($character, '#').'{2,}#', $character, $string);
		
		if ($trim === true)
			$string = trim($string, $character);
		
		return $string;
	}

	/**
	 * Метод увеличивает счетчик в строке
	 *
	 * @param string $string
	 * @param string $separator
	 * @param integer $first
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function incrementString($string, $separator='_', $first=1)
	{
		preg_match('/(.+)'.$separator.'([0-9]+)$/', $string, $match);
		
		return isset($match[2]) ? $match[1].$separator.($match[2] + 1) : $string.$separator.$first;
	}

	/**
	 * Ну в общем и так понятно должно быть, что делает метод
	 *
	 * @param string
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function alternator()
	{
		static $i;
		
		if (func_num_args() == 0) {
			$i = 0;
			return '';
		}
		
		$args = func_get_args();
		return $args[($i++ % count($args))];
	}

	/**
	 * Метод возвращает строку, продублированную заданное количество раз
	 *
	 * @param string $string
	 * @param integer $num
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function repeater($string, $num=1)
	{
		return (($num > 0) ? str_repeat($string, $num) : '');
	}

	/**
	 * Функция wordwrap для работы с UTF-8
	 *
	 * @param string $str
	 * @param integer $width
	 * @param string $break
	 * @param bool $cut
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function wordwrap($string, $width=80, $break="\r\n", $cut=false)
	{
		return preg_replace('#([\S]{'.$width.'}'. ($cut ? '' : '\s') .')#u', '$1'. $break , $string);
	}

	/**
	 * Метод для обрезания строк.
	 * Взят из плагинов "Smarty 3.0.6"
	 *  + добавлен параметр "$charset" (Иначе косяки с кириллицей в utf-8)
	 *  + подправлен код, т.к. неверно обрезались строки до целых слов
	 *
	 * $string - Строка, которую надо обрезать
	 *
	 * $length - Определяет максимальную длину обрезаемой строки
	 *
	 * $etc - Текстовая строка, которая заменяет обрезаемый текст.
	 *        Её длина НЕ включена в максимальную длину обрезаемой строки.
	 *
	 * $break_words - Определяет, обрезать ли строку в промежутке между словами (false)
	 *                или строго на указанной длине (true).
	 *
	 * $middle - Определяет, нужно ли обрезать строку в конце (false) или в середине строки (true).
	 *           Обратите внимание, что при включении этой опции, промежутки между словами игнорируются.
	 *
	 * $exact_length - Если true, то обрезается точно по запрашиваемой длине + $etc, если false, то запрашиваемая длина - длина $etc + $etc
	 * 
	 * $charset	- Кодировка строки
	 *
	 * @param string $string
	 * @param integer $length
	 * @param string $etc
	 * @param boolean $break_words
	 * @param boolean $middle
	 * @param string $charset
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function truncate($string, $length=80, $etc='...', $break_words=false, $middle=false, $exact_length=true, $charset='UTF-8')
	{
		if ($length == 0) return '';
		
		if ($this->_functionExists('mb_strlen')) {
			if (mb_detect_encoding($string, 'UTF-8, ISO-8859-1') === 'UTF-8') {
				// $string has utf-8 encoding
				if (mb_strlen($string, $charset) > $length) {
					if (!$break_words && !$middle) {
						$string = mb_ereg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1, $charset));
						if (mb_strlen($string, $charset) > $length) {
							return preg_replace('/\s+?(\S+)?$/u', '', $string) . $etc;
						}
					}
					if (!$exact_length) $length -= min($length, mb_strlen($etc, $charset));
					if (!$middle) {
						return mb_substr($string, 0, $length, $charset) . $etc;
					} else {
						return mb_substr($string, 0, $length / 2, $charset) . $etc . mb_substr($string, - $length / 2, $charset);
					}
				} else {
					return $string;
				}
			}
		}
		// $string has no utf-8 encoding
		if (strlen($string) > $length) {
			if (!$break_words && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
				if (mb_strlen($string, $charset) > $length) {
					return preg_replace('/\s+?(\S+)?$/', '', $string) . $etc;
				}
			}
			if (!$exact_length) $length -= min($length, strlen($etc));
			if (!$middle) {
				return substr($string, 0, $length) . $etc;
			} else {
				return substr($string, 0, $length / 2) . $etc . substr($string, - $length / 2);
			}
		} else {
			return $string;
		}
	}

	/**
	 * Метод возвращает рандомную строку
	 *
	 * @param string $type
	 * @param integer $len
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/string_helper.php
	 */
	public function randomString($type='alnum', $len=5)
	{
		switch($type) {
			case 'basic': return mt_rand();
				break;
			case 'alnum':
			case 'numeric':
			case 'nozero':
			case 'alpha':
					switch ($type) {
						case 'alpha' :
							$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'alnum':
							$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'numeric':
							$pool = '0123456789';
							break;
						case 'nozero':
							$pool = '123456789';
							break;
					}

					$str = '';
					for ($i=0; $i<$len; $i++) {
						$str .= $this->substr($pool, mt_rand(0, $this->len($pool) -1), 1);
					}
					return $str;
				break;
			case 'unique':
			case 'md5':
				return md5(uniqid(mt_rand()));
				break;
			case 'encrypt':
			case 'sha1':
				return sha1(uniqid(mt_rand(), true));
				break;
		}
	}

	/**
	 * Метод для транслитерации строк
	 *
	 * @param string $string
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function toTranslit($string)
	{
		$tr = array(

			'А'=>'A',  'Б'=>'B',  'В'=>'V',  'Г'=>'G', 'Д'=>'D',
			'Е'=>'E',  'Ё'=>'Yo', 'Ж'=>'Zh', 'З'=>'Z', 'И'=>'I',
			'Й'=>'J',  'К'=>'K',  'Л'=>'L',  'М'=>'M', 'Н'=>'N',
			'О'=>'O',  'П'=>'P',  'Р'=>'R',  'С'=>'S', 'Т'=>'T',
			'У'=>'U',  'Ф'=>'F',  'Х'=>'H',  'Ц'=>'C', 'Ч'=>'Ch',
			'Ш'=>'Sh', 'Щ'=>'W',  'Ъ'=>'',   'Ы'=>'Y', 'Ь'=>'',
			'Э'=>'Ye', 'Ю'=>'Yu', 'Я'=>'Ya',

			'а'=>'a',  'б'=>'b',  'в'=>'v',  'г'=>'g', 'д'=>'d',
			'е'=>'e',  'ё'=>'yo', 'ж'=>'zh', 'з'=>'z', 'и'=>'i',
			'й'=>'j',  'к'=>'k',  'л'=>'l',  'м'=>'m', 'н'=>'n',
			'о'=>'o',  'п'=>'p',  'р'=>'r',  'с'=>'s', 'т'=>'t',
			'у'=>'u',  'ф'=>'f',  'х'=>'h',  'ц'=>'c', 'ч'=>'ch',
			'ш'=>'sh', 'щ'=>'w',  'ъ'=>'',   'ы'=>'y', 'ь'=>'',
			'э'=>'ye', 'ю'=>'yu', 'я'=>'ya',

			'!'=>'',  '@'=>'',  '#'=>'', '$'=>'',  '%'=>'',
			'^'=>'',  '&'=>'',  '*'=>'', '('=>'',  ')'=>'',
			'"'=>'',  ' '=>'_', ';'=>'', ':'=>'',  '?'=>'',
			'['=>'',  ']'=>'',  '{'=>'', '}'=>'',  '\''=>'',
			','=>'',  '.'=>'',  '/'=>'', '<'=>'',  '>'=>'',
			'\\'=>'', '|'=>'',  '/'=>'', '='=>'',  '+'=>'',
			'№'=>'',

			// '_'=>'', '-'=>'',

		);

		return strtr($string, $tr);
	}
	
	/**
	 * Метод для растранслитерации строк
	 *
	 * @param string $string
	 * @return string
	 *
	 * @version 0.1 21.08.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function fromTranslit($string)
	{
		$tr = array(
			'A'=>'А', 'B'=>'Б',  'C'=>'К', 'D'=>'Д', 'E'=>'Е',
			'F'=>'Ф', 'G'=>'Дж', 'H'=>'Ш', 'I'=>'И', 'J'=>'',
			'K'=>'К', 'L'=>'Л',  'M'=>'М', 'N'=>'Н', 'O'=>'О',
			'P'=>'П', 'Q'=>'К',  'R'=>'Р', 'S'=>'С', 'T'=>'Т',
			'U'=>'У', 'V'=>'В',  'W'=>'В', 'X'=>'',  'Y'=>'И',
			'Z'=>'З',
			
			'a'=>'а', 'b'=>'б',  'c'=>'к', 'd'=>'д', 'e'=>'е',
			'f'=>'ф', 'g'=>'дж', 'h'=>'ш', 'i'=>'и', 'j'=>'',
			'k'=>'к', 'l'=>'л',  'm'=>'м', 'n'=>'н', 'o'=>'о',
			'p'=>'п', 'q'=>'к',  'r'=>'р', 's'=>'с', 't'=>'т',
			'u'=>'у', 'v'=>'в',  'w'=>'в', 'x'=>'',  'y'=>'и',
			'z'=>'з',

		);

		return strtr($string, $tr);
	}

}

