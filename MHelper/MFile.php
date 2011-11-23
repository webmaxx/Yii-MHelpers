<?php

/**
 * Класс-хелпер для работы с файлами
 *
 * @version 0.1 22.11.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
class MFile extends MHelperBase
{
	
	/**
	 * Метод проверяет является запрашиваемый файл собственно файлом
	 * 
	 * @param string $file
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function isFile($file)
	{
		return $file ? is_file($file) : false;
	}
	
	/**
	 * Метод проверяет есть ли права на чтение файла
	 * 
	 * @param string $file
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function isReadable($file)
	{
		return $file ? is_readable($file) : false;
	}
	
	/**
	 * Метод читает файл и возвращает его содержимое
	 * 
	 * @param string $file
	 * @return string 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/file_helper.php
	 */
	public function read($file)
	{
		if (!file_exists($file))
			return false;

		if ($this->_functionExists('file_get_contents'))
			return file_get_contents($file);

		if (!$fp = @fopen($file, FOPEN_READ))
			return false;

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0)
			$data = fread($fp, filesize($file));

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}
	
	/**
	 * Метод записывает данные в файл
	 * 
	 * @param string $path
	 * @param string $data
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/file_helper.php
	 */
	public function write($path, $data, $mode=FOPEN_WRITE_CREATE_DESTRUCTIVE)
	{
		if (!$fp = @fopen($path, $mode))
			return false;

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return true;
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
	public function delete($path, $del_dir=false, $level=0)
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
	 * Метод возвращает список файлов в директории
	 * 
	 * @param string $path
	 * @param bool $include_path
	 * @param bool $_recursion
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/file_helper.php
	 */
	public function filenames($source_dir, $include_path=false, $_recursion=false)
	{
		static $_filedata = array();

		if ($fp = @opendir($source_dir)) {
			if ($_recursion === false) {
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			while (false !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
					$this->filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, true);
				elseif (strncmp($file, '.', 1) !== 0)
					$_filedata[] = ($include_path == true) ? $source_dir.$file : $file;
			}
			return $_filedata;
		} else {
			return false;
		}
	}
	
	/**
	 * Метод возвращает информацию о файлах в директории
	 * 
	 * @param string $source_dir
	 * @param bool $top_level_only
	 * @param bool $_recursion
	 * @return array 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/file_helper.php
	 */
	public function filesInfo($source_dir, $top_level_only=true, $_recursion=false)
	{
		static $_filedata = array();
		$relative_path = $source_dir;

		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === false) {
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			// foreach (scandir($source_dir, 1) as $file) // In addition to being PHP5+, scandir() is simply not as fast
			while (false !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0 && $top_level_only === false) {
					$this->filesInfo($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, false);
				} elseif (strncmp($file, '.', 1) !== 0) {
					$_filedata[$file] = $this->fileInfo($source_dir.$file);
					$_filedata[$file]['relative_path'] = $relative_path;
				}
			}

			return $_filedata;
		} else {
			return false;
		}
	}
	
	/**
	 * Метод возвращает информацию файле
	 * 
	 * @param string $file
	 * @param mixed $returned_values
	 * @return array
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/file_helper.php
	 */
	public function fileInfo($file, $returned_values=array('name','server_path','size','date'))
	{
		if (!file_exists($file))
			return false;

		if (is_string($returned_values))
			$returned_values = explode(',', $returned_values);

		foreach ($returned_values as $key) {
			switch ($key) {
				case 'name':
					$fileinfo['name'] = $this->pathInfo($file, 'basename');
					break;
				case 'server_path':
					$fileinfo['server_path'] = $file;
					break;
				case 'size':
					$fileinfo['size'] = filesize($file);
					break;
				case 'date':
					$fileinfo['date'] = filemtime($file);
					break;
				case 'readable':
					$fileinfo['readable'] = is_readable($file);
					break;
				case 'writable':
					// There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
					$fileinfo['writable'] = is_writable($file);
					break;
				case 'executable':
					$fileinfo['executable'] = is_executable($file);
					break;
				case 'fileperms':
					$fileinfo['fileperms'] = fileperms($file);
					break;
			}
		}
		
		return $fileinfo;
	}
	
	/**
	 * Метод возвращает информацию о пути к файлу
	 * 
	 * @param string $file
	 * @param string $field (dirname, basename, extension, filename)
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function pathInfo($file, $field=false)
	{
		$pathInfo = $file ? pathinfo($file) : false;
		return $pathInfo ? ($field ? $pathInfo[$field] : $pathInfo) : false;
	}
	
	/**
	 * Метод возвращает информацию о размере файла
	 * 
	 * @param string $file
	 * @param string $format (see {@link CNumberFormatter})
	 * @return mixed
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function size($file, $format=false)
	{
		if ($this->isFile($file))
            $size = sprintf("%u", filesize($file));
        
        if ($format !== false)
            $size = $this->sizeFormat($size, $format);

        return $size;
	}
	
	/**
	 * Метод возвращает информацию о размере файла в человекопонятном формате
	 * 
	 * @param integer $bytes
	 * @param string $format (see {@link CNumberFormatter})
	 * @return string
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function sizeFormat($bytes, $format)
	{
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

		$bytes = max($bytes, 0);
		$expo = floor(($bytes ? log($bytes) : 0) / log(1024));
		$expo = min($expo, count($units)-1);

		$bytes /= pow(1024, $expo);

		return Yii::app()->numberFormatter->format($format, $bytes).' '.$units[$expo];
	}
	
	/**
	 * Метод отсылает файл юзеру
	 * 
	 * @param string $file
	 * @param string $fakename
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function send($file, $fakename=false)
	{
		if (!$this->isFile($file) || !$this->isReadable($file))
			return false;
		
		$contentType = $this->getMimeType($file);
		
		if (!$contentType)
			$contentType = "application/octet-stream";
		
		if ($fakename)
			$filename = $fakename;
		else
			$filename = $this->pathInfo($file, 'basename');
		
		// disable browser caching
		header('Cache-control: private');
		header('Pragma: private');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

		header('Content-Type: '.$contentType);
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.$this->size($file));
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		
		if ($contents = $this->read($file)) {
			echo $contents;
			exit;
		}
		
		return false;
	}
	
	/**
	 * Alias for {@link send}
	 * 
	 * @param string $file
	 * @param string $fakename
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function download($file, $fakename=false)
	{
		$this->send($file, $fakename);
	}
	
	/**
	 * Метод возвращает Mime-тип файла
	 * 
	 * @param string $file
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function getMimeType($file)
	{
		if ($this->_functionExists('finfo_open'))
			if(($info=@finfo_open(FILEINFO_MIME)) && ($result=@finfo_file($info,$file))!==false)
				return $result;
		
		if ($this->_functionExists('mime_content_type') && ($result=@mime_content_type($file))!==false)
			return $result;
		
		return $this->getMimeTypeByExtension($file);
	}
	
	/**
	 * Метод возвращает Mime-тип файла по его расширению
	 * 
	 * @param string $file
	 * @return bool 
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 */
	public function getMimeTypeByExtension($file)
	{
		if (!isset($this->_trash['extensions']))
			$this->_trash['extensions'] = require Yii::getPathOfAlias('system.utils.mimeTypes').'.php';
		
		$extension = $this->pathInfo($file, 'extension');
		
		if (!$extension || !isset($this->_trash['extensions'][$extension]))
			return false;
		
		return $this->_trash['extensions'][$extension];
	}
	
}

