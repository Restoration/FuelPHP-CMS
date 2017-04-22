<?php
class Model_User extends \Model{

	protected static $_table_name = 'tbl_user';
	protected static $_primary_key = 'id';
	protected static $_properties = array(
		'id',
		'username',
		'password',
		'group',
		'email',
		'last_login',
		'login_hash',
		'profile_fields',
		'created_at',
		'updated_at'
	);

	/**
	 * Get user info from user id
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function get_search_user()
    {
	    $id = \Input::get('id');
		$table_name = 'tbl_user';
		$query = \DB::select()->where('id','=',$id)->and_where('dltflg','=',0)->from($table_name);
		return $query->execute()->as_array();
	}

	/**
	 * idとpasswordからpasswordが存在しているかチェック
	 *
	 * @access  public
	 * @return  Response
	 */
	public static function password_search($old_password)
	{
		$table_name = 'tbl_user';
		$auth = \Auth::instance();
		list($driver, $user_id) = $auth->get_user_id();
		$password = $auth->hash_password($old_password);
		$query = \DB::select(\DB::expr('COUNT(*) as count'))->where('password','=',$password)->and_where('id','=',$user_id)->from($table_name);
		$result = $query->execute();
		$result_arr = $result->current();
		return $result_arr['count'];
	}

	/**
	 * User Update
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function edit_action($post)
	{
		if(!empty($_POST['save'])){
			$auth = \Auth::instance();
			$table_name = 'tbl_user';
			$email = $post['email'];
			$new_password = $post['password'];
			$old_password = $post['old_password'];
			$update_user_array = array(
				'email'=> $email,
				'password'=> $new_password,
				'old_password' => $old_password
			);
			return $auth->update_user($update_user_array);
	    }
	}
	/**
	 *  Check user search from username
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function username_search($username)
    {
		$table_name = 'tbl_user';
		$query = \DB::select(\DB::expr('COUNT(*) as count'))->where('username','=',$username)->from($table_name);
		$result = $query->execute();
		$result_arr = $result->current();
		return $result_arr['count'];
	}
	/**
	 * Check user search from email
	 *
	 * @access  public
	 * @return  Response
	 */
    public static function email_search($email)
    {
		$table_name = 'tbl_user';
		$query = \DB::select(\DB::expr('COUNT(*) as count'))->where('email','=',$email)->from($table_name);
		$result = $query->execute();
		$result_arr = $result->current();
		return $result_arr['count'];
	}
}