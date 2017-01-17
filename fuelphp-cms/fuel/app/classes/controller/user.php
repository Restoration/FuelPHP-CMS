<?php
class Controller_User extends Controller
{
	public function action_index()
	{
		$auth = Auth::instance();
		$username = $auth->get_screen_name();
		$email = $auth->get_email();
		$view = View::forge('user/index');
		$model_user = new Model_User();
		$model_utility = new Model_Utility();
		$view->set('username',$username);
		$view->set('email',$email);
		//Response::forge(View::forge('main/index'));
		return $view;
	}

	//追加アクション
	public function action_add()
	{
		$model_user = new Model_User();
		if($_POST){
			//バリデーションスタート
			$validate = Validation::forge();
			$post = $_POST['add'];
			$errors = !$validate->run($post) ? $validate->error() : array();
			$values = $validate->validated();

			foreach ($errors as $key => &$error){
				$values[$key] = $error->value;
				$error = ''.$error;
			}
			//POSTデータを受け取る
			$username = Input::post('username');
			$email = Input::post('email');
			$password = Input::post('password');
			$group = Input::post('group');


			$username_count = $model_user->username_search($username);
			$email_count = $model_user->email_search($email);

			//重複チェック
			if($username_count>0){
				$errors[] = 'Duplicate user name.';
				$error_message = 'Failed to add user.';
				Session::set_flash('error_message',$error_message);
			}
			if($email_count>0){
				$errors[] = 'Duplicate email address.';
				$error_message = 'Failed to add user.';
				Session::set_flash('error_message',$error_message);
			}

			if(empty($errors)){
				if ( ! \Security::check_token()){
					$error_message = 'I suspect unauthorized access.';
					echo $error_message;
					exit();
				} else {
					$model_user->insert_action();
					 $result_message = 'New user added.';
					Session::set_flash('result_message',$result_message);
					Response::redirect('user', 'refresh');
				}
			} else {
				$auth = Auth::instance();
				$username = $auth->get_screen_name();
				$email = $auth->get_email();
				$view = View::forge('user/index');
				$model_user = new Model_User();
				$model_utility = new Model_Utility();
				$result = $model_user->get_result();
				$view->set('result',$result);
				$view->set('username',$username);
				$view->set('email',$email);
				Session::set_flash('errors',$errors);
				return $view;
			}
		}
	}


	private function validate_update()
	{
		// 入力チェック
		$validation = Validation::forge();
		$validation->add('password', 'New PassWord')
			->add_rule('min_length', 6)
			->add_rule('max_length', 20);
		$validation->add('old_password', 'Old PassWord')
			->add_rule('min_length', 6)
			->add_rule('max_length', 20);
		$validation->add('email', 'Eメール')
			->add_rule('required');
		$validation->run();
		return $validation;
	}

	// ユーザー更新&削除
	public function action_update(){
		$auth = Auth::instance();
		$username = $auth->get_screen_name();
		$email = $auth->get_email();
		$view = View::forge('user/index');
		$model_user = new Model_User();
		$model_utility = new Model_Utility();

		$validation = $this->validate_update();
		$errors = $validation->error();
		$auth = Auth::instance();
		$result_validate = '';


		$email = Input::post('email');
		$password = Input::post('password');
		$group = Input::post('group');

		//更新
		$model_user = new Model_User();
		$username = Input::post('username', null);
		try {
			if (empty($errors)) {
				$input = $validation->input();
				$values = array();
				foreach ($input as $key => $value) {
					if ($value === '') continue;
					$values[$key] = $value;
				}

				if (!empty($values)) {
					$model_user->edit_action();
					$result_message = 'User update completed.';
					Session::set_flash('result_message',$result_message);
					Response::redirect('user/index');
				}
				if (!empty($values)) $result_validate = 'User update failed.';
			} else {
				$result_validate = $validation->show_errors();
			}
		} catch (Exception $e) {
			$error_message = 'User update failed.';
			Session::set_flash('error_message',$error_message);
			$result_validate = $e->getMessage();
		}
	$view = View::forge('user/index');
	Session::set_flash('errors',$errors);
	$view->set('errmsg',$result_validate);
	$view->set('username',$username);
	$view->set('email',$email);
	return $view;
	}
}
