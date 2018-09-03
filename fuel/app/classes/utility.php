<?php
class Utility {

	/**
	 * Escape processing
	 *
	 * @access  public
	 * @params  value
	 * @return  Response
	 */
	public static function h($val)
	{
		if(is_array($val)){
			return array_map('h',$val);
		} else {
			return htmlspecialchars($val,ENT_QUOTES,'UTF-8');
		}
	}

	/**
	 * Create directory for that date
	 *
	 * @access  public
	 * @params  directory
	 * @return  Response
	 */
	public function make_date_dir($dir)
	{
		if(!is_dir($dir)){
			mkdir($dir);
		}
		$_dir = '';
		$date_array = array(date('Y'),date('m'));
		for($i=0; $i < count($date_array); $i++){
			$this->make_dir($dir,$date_array[$i]);
			$dir .= '/'.$date_array[$i];
			$_dir .= '/'.$date_array[$i];
		}
		$_dir .= '/';
		return $_dir;
	}
	/**
	 * Create directory
	 *
	 * @access  public
	 * @params  directory
	 * @params  folder name
	 * @return  Response
	 */
	public function make_dir($dir,$name)
	{
		if(substr($dir,-1) !== "/"){
			$dir .= "/";
		}
		if(is_dir($dir.$name)){
			return false;
		}
		$dir .= $name;
		if(mkdir($dir)){
			return true;
		}
	}
}