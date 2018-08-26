<?php
class MyValidation
{
	/**
	 * Search from username
	 *
	 * @access  public
	 * @params  username
	 * @return  Response
	 */
	public static function _validation_username_find($val){
		$model_user = new Model_User();
		$count = $model_user->username_search($val);
		if($count != 1){
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Search from password
	 *
	 * @access  public
	 * @params  password
	 * @return  Response
	 */
	public static function _validation_password_find($val){
		$model_user = new Model_User();
		$count = $model_user->password_search($val);
		if($count != 1){
			return false;
		} else {
			return true;
		}
	}
}