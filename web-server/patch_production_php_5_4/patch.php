<?php
/**
 * ��������������� ����� ��� �������������� �������.
 */
class patchHelper
{
	/**
	 * ���� ����� ������� ������ ���� ������ � �������� �����
	 */
	public static $files;
	public static function listFolderFiles($dir){
		$ffs = scandir($dir);
		foreach($ffs as $ff){
			if($ff != '.' && $ff != '..'){
				if(is_dir($dir . DIRECTORY_SEPARATOR . $ff)) {
					self::listFolderFiles($dir . DIRECTORY_SEPARATOR . $ff);
				} else {
					self::$files[$dir.'/'.$ff] = $ff;
				}
			}
		}
	}
	public static function files($dir)
	{
		self::$files = array();
		self::listFolderFiles($dir);
		return self::$files;
	}
	
	
	/**
	 * ���������� ���� �� ���� ������� �����, ������ �����, � �� ������.
	 */
	public static function one_level_up($path)
	{
		$path = explode(DIRECTORY_SEPARATOR, $path);
		unset($path[0]);
		return implode(DIRECTORY_SEPARATOR, $path);
	}
}



class patch extends patchHelper
{
	/**
	 * ��������� ��� ����� ��������
	 */
	public static function read_templates()
	{
		$files = array();
		foreach(self::files('templates') as $path=>$file_name) $files[$path] = file_get_contents($path);
		return $files;
	}
	
	
	/**
	 * ��������� ��� �������� ���������� ��������.
	 */
	public static function read_params($templates)
	{
		$params = array();
		foreach($templates as $path=>$file_name) {
			$params_path = 'params/'.self::one_level_up($path).'.php';
			$params[$path] = is_file($params_path) ? include_once($params_path) : '';
		}
		return $params;
	}
	
	
	/**
	 * ���������� ��� ����� ��������
	 */
	public static function write_templates($templates)
	{
		foreach($templates as $path=>$content) {
			$path = '../'.self::one_level_up($path);
			
			// ������ �����, ���� �� ���.
			$path_info = pathinfo($path);
			if ( ! is_dir($path_info['dirname'])) {
				mkdir($path_info['dirname'], 0777, true);
			}
			
			// ������ �� $path ����� "templates" � �������� ���� � ������������ ������������� ����.
			file_put_contents($path, $content);
		}
	}
	
	
	/**
	 * ���������� ������ ��������� ���������� �� �������� ���������.
	 */
	public static function replace_templates($templates, $params)
	{
		foreach($templates as $key=>$content) {
			if ( ! isset($params[$key]) OR ! is_array($params[$key])) continue;
			$templates[$key] = strtr($content, $params[$key]);
		}
		return $templates;
	}
	
	
	/**
	 * ������� �������, ���-�� ����� main();
	 */
	public static function run()
	{
		$templates = self::read_templates();
		$params = self::read_params($templates);
		$r_templates = self::replace_templates($templates, $params);
		self::write_templates($r_templates);
	}
}

// �������!!!
patch::run();
