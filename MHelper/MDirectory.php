<?php

/**
 * Класс-хелпер для работы с директориями
 *
 * @version 0.1 22.11.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
class MDirectory extends MHelperBase
{
	
	/**
	 * Метод возвращает массив с содержимым папки
	 * 
	 * @param string $source_dir
	 * @param integer $directory_depth (0 = fully recursive, 1 = current dir, etc)
	 * @param bool $hidden
	 * @return array 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/directory_helper.php
	 */
	public function map($source_dir, $directory_depth=0, $hidden=false)
	{
		if ($fp = @opendir($source_dir))
		{
			$filedata	= array();
			$new_depth	= $directory_depth - 1;
			$source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (false !== ($file = @readdir($fp))) {
				if (!trim($file, '.') || ($hidden == false && $file[0] == '.'))
					continue;

				if (($directory_depth < 1 || $new_depth > 0) && @is_dir($source_dir.$file))
					$filedata[$file] = $this->map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
				else
					$filedata[] = $file;
			}

			@closedir($fp);
			return $filedata;
		}

		return false;
	}
	
	/**
	 * Метод удаляет все файлы в директории
	 * 
	 * @param string $path
	 * @param bool $del_dir
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/file_helper.php
	 */
	public function clear($path, $del_dir=true, $level=0)
	{
		$path = rtrim($path, DIRECTORY_SEPARATOR);

		if (!$current_dir = @opendir($path))
			return false;

		while (false !== ($filename = @readdir($current_dir))) {
			if ($filename != "." && $filename != "..") {
				if (is_dir($path.DIRECTORY_SEPARATOR.$filename)) {
					if (substr($filename, 0, 1) != '.')
						$this->delete($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $level+1);
				} else {
					@unlink($path.DIRECTORY_SEPARATOR.$filename);
				}
			}
		}
		@closedir($current_dir);

		if ($del_dir == true && $level > 0)
			return @rmdir($path);

		return true;
	}
	
	/**
	 * Метод удаляет каталог со всем содержимым
	 * 
	 * @param string $path
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function delete($path)
	{
		if ($this->clear($path)) {
			@rmdir($path);
			return true;
		}
		return false;
	}
	
}

