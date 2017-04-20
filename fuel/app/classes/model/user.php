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

	//idからユーザーを取得
    public static function get_search_user()
    {
	    $id = $_GET['id'];
		$table_name = 'tbl_user';
		$query = \DB::select()->where('id','=',$id)->and_where('dltflg','=',0)->from($table_name);
		return $query->execute()->as_array();
	}

	//追加
    public static function insert_action()
    {
		$username = \Input::post('username');
		$password = \Input::post('password');
		$email = \Input::post('email');
		$auth = \Auth::instance();
		$auth->create_user($username,$password,$email);
    }


	//編集
    public static function edit_action()
	{
		try {
			\DB::start_transaction();
			if(!empty($_POST['save'])){
				//Update
				$table_name = 'tbl_user';
				$username = \Input::post('username');
				$password = \Input::post('password');
				$old_password = \Input::post('old_password');
				$email = \Input::post('email');
				$group = \Input::post('group');
				$auth = \Auth::instance();
				$update_user_array =
					array(
						'email'=> $email,
						'password'=> $password,
						'old_password' => $old_password
					);
				$query = \DB::update($table_name)->set(array('group'=>$group))->where('username','=',$username);
				$query->execute();
				if(!empty($password) && !empty($old_password)){
					$auth->update_user($update_user_array);
				}
		    }
			\DB::commit_transaction();
		}catch(\Database_exception $e){
			\DB::rollback_transaction();
		}
	}
    public static function username_search($username)
    {
		$table_name = 'tbl_user';
		$query = \DB::select(\DB::expr('COUNT(*) as count'))->where('username','=',$username)->from($table_name);
		$result = $query->execute();
		$result_arr = $result->current();
		return $result_arr['count'];
	}

    public static function email_search($email)
    {
		$table_name = 'tbl_user';
		$query = \DB::select(\DB::expr('COUNT(*) as count'))->where('email','=',$email)->from($table_name);
		$result = $query->execute();
		$result_arr = $result->current();
		return $result_arr['count'];
	}
}