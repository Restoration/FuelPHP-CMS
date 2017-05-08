<?php
class Model_User extends \Model{

	protected static $_table_name = 'mtr_user';
	protected static $_primary_key = 'id';
	protected static $_properties = array(
		'id',
		'username',
		'password',
		'group',
		'email',
		'last_login',
		'login_hash',
		'onepass',
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
		$table_name = 'mtr_user';
		$query = \DB::select()->where('id','=',$id)->and_where('dltflg','=',0)->from($table_name);
		return $query->execute()->as_array();
	}

	/**
	 * Check password search from id and password
	 *
	 * @access  public
	 * @params  old password
	 * @return  Response
	 */
	public static function password_search($old_password)
	{
		$table_name = 'mtr_user';
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
	 * @params  post data
	 * @params  flg
	 * @return  Response
	 */
    public static function edit_action($post,$flg = 1)
	{
		if(!empty($_POST['save'])){
			$auth = \Auth::instance();
			$table_name = 'mtr_user';
			$email = $post['email'];
			if($flg){
				$new_password = $post['password'];
				$old_password = $post['old_password'];
				$update_user_array = array(
					'email'=> $email,
					'password'=> $new_password,
					'old_password' => $old_password
				);
			} else {
				$update_user_array = array('email'=> $email);
			}
			return $auth->update_user($update_user_array);
	    }
	}

	/**
	 *  Check user search from username
	 *
	 * @access  public
	 * @params  username
	 * @return  Response
	 */
    public static function username_search($username)
    {
		$table_name = 'mtr_user';
		$query = \DB::select(\DB::expr('COUNT(*) as count'))->where('username','=',$username)->from($table_name);
		$result = $query->execute();
		$result_arr = $result->current();
		return $result_arr['count'];
	}

	/**
	 * Check user search from email
	 *
	 * @access  public
	 * @params  email
	 * @return  Response
	 */
    public static function email_search($email)
    {
		$table_name = 'mtr_user';
		$query = \DB::select(\DB::expr('COUNT(*) as count'))->where('email','=',$email)->from($table_name);
		$result = $query->execute();
		$result_arr = $result->current();
		return $result_arr['count'];
	}

	/**
	 * Check user search from username and email
	 *
	 * @access  public
	 * @params  UserName
	 * @params  Email
	 * @return  Response
	 */
    public function username_email_search($username,$email)
    {
		$table_name = 'mtr_user';
		$query = \DB::select()->where('username','=',$username)->and_where('email','=',$email)->from($table_name);
		return $query->execute()->as_array();
	}

	/**
	 * Create one time password
	 *
	 * @access  public
	 * @params  UserID
	 * @return  Response
	 */
    public static function create_ontimepass($user_id,$flg = true)
    {
	    $table_name = 'mtr_user';
	    if($flg){
			$onepass = md5(time());
	    } else {
		    $onepass = '';
	    }
		$query = \DB::update($table_name)->value('onepass',$onepass)->where('id','=',$user_id);
		$query->execute();
		return $onepass;
	}

	/**
	 * Search user count from one time password
	 *
	 * @access  public
	 * @params  One Time Password
	 * @return  Response
	 */
    public static function get_onepass_user($onepass)
    {
	    $table_name = 'mtr_user';
		$query = \DB::select()->where('onepass','=',$onepass)->from($table_name);
		return count($query->execute()->as_array());
	}
	/**
	 * Search from one time password and delete user
	 *
	 * @access  public
	 * @params  One Time Password
	 * @return  Response
	 */
    public static function delete_user($onepass)
    {
		try {
			\DB::start_transaction();
			$table_name = 'mtr_user';
			$query = \DB::select('username')->where('onepass','=',$onepass)->from($table_name);
			$user = $query->execute();
			$auth = \Auth::instance();
			$auth->delete_user($user[0]['username']);
			\DB::commit_transaction();
			return true;
		}catch(\Database_exception $e){
			\DB::rollback_transaction();
			return false;
		}
	}
	/**
	 * Search from one time password and change user's password
	 *
	 * @access  public
	 * @params  One Time Password
	 * @params  Password
	 * @return  Response
	 */
    public static function repass($onepass,$password)
    {
		try {
			\DB::start_transaction();
			$table_name = 'mtr_user';
			$query = \DB::select()->where('onepass','=',$onepass)->from($table_name);
			$user = $query->execute();
			$username = $user[0]['username'];
			$auth = \Auth::instance();
			$old_password = $auth->reset_password($username);
			$auth->change_password($old_password,$password,$username);
			$auth->login($username, $password);
			\DB::commit_transaction();
			return true;
		}catch(\Database_exception $e){
			\DB::rollback_transaction();
			return false;
		}
	}
}