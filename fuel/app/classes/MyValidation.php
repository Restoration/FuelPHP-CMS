<?php
class MyValidation
{
	/**
	 * パスワードが存在しているかチェック
	 *
	 * @access  public
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