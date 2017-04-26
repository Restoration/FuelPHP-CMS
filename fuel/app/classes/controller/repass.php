<?php

class Controller_Repass extends Controller_App
{

	/**
	 * Reset password request page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index(){
		$this->template->title = 'Reset Password';
		$this->template->content = \View::forge('repass/index');
	}
	/**
	 * Reset Password request complete
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_complete(){
		if(\Input::method() == 'POST'){
			$post = $_POST['repass'];
			$username = $post['username'];
			$email = $post['email'];
			$errors = $this->validate_repass($post);
			if(empty($errors) || !\Security::check_token()){
				$model_user = new Model_User();
				$user = $model_user->username_email_search($username,$email);
				// create one time password
				$onepass = $model_user->create_ontimepass($user[0]['id']);
				$data['username']= $username;
				$data['email'] = $email;
				$data['anchor'] = 'repass/repass/'.$onepass;
				$body = \View::forge('email/repass',$data);
				$sendmail = \Email::forge();
				$sendmail->from('examplem@example.com','FuelPHP-CMS');
				$sendmail->to($email,$username);
				$sendmail->subject('Password recurrence procedure accepted');
				$sendmail->html_body($body);

				if($sendmail->send()){
					$this->template->title = 'Reset Password';
					$this->template->content = \View::forge('repass/complete');
				} else {
					$result_message = 'Password reset failed';
					\Session::set_flash('error_message',$result_message);
				}
			} else {
				$result_message = 'Password reset failed';
				\Session::set_flash('error_message',$result_message);
				\Response::redirect('repass/index', 'refresh');
			}
		} else {
			\Response::redirect('repass/index', 'refresh');
		}
	}
	/**
	 * Reset Password Page
	 *
	 * @access  public
	 * @return  Response
	 */
	//
	public static function action_repass($onepass){

		if(\Input::method() == 'POST'){
			$post = $_POST['repass'];
			$password = $post['password'];
			$password = $post['password_conf'];
			$errors = $model_user->validate_repass_process($post);
			if(!empty($errors) || !\Security::check_token()){
				$model_user = new Model_User();
				if($model_user->get_onepass_user($onepass) == 0){
					$result_message = 'Password reset failed';
					\Session::set_flash('error_message',$result_message);
					$view = \View::forge('repass/repass');
					return $view;
				}
				if($model_user->repass($onepass,$password)){
					// one time password to empty
					list($driver, $user_id) = Auth::get_user_id();
					$model_user->create_ontimepass($user_id,false);
					\Response::redirect('user/index', 'refresh');

				} else {
					$result_message = 'Password reset failed';
					\Session::set_flash('error_message',$result_message);
					\Response::redirect('repass/index');
				}
			} else {
				$result_message = 'Password reset failed';
				\Session::set_flash('error_message',$result_message);
			}
		}
		$view = \View::forge('repass/repass');
		return $view;
	}

	/**
	 * Reset Password request validation
	 *
	 * @access  private
	 * @return  Response
	 */
	private function validate_repass($post)
	{
		$username = $post['username'];
		$email = $post['email'];
		$validate = \Validation::forge();
		$validate->add_callable('MyValidation');
		$validate->add('username','username')
			->add_rule('trim')
			->add_rule('min_length', 4)
			->add_rule('max_length', 50)
			->add_rule('required')
			->add_rule('username_find');
		$validate->set_message('username_find', 'UserName not found');
		$validate->add('email','email')
			->add_rule('trim')
			->add_rule('max_length', 255)
			->add_rule('required');
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}
	/**
	 * Reset Password process validation
	 *
	 * @access  private
	 * @return  Response
	 */
	private function validate_repass_process($post)
	{
		$password = $post['password'];
		$password_conf = $post['password_conf'];
		$validate = \Validation::forge();

		$validate->add('password','Password')
			->add_rule('trim')
			->add_rule('min_length', 6)
			->add_rule('max_length', 255)
			->add_rule('required')
			->add_rule('match_field', 'password_conf');
		$validate->add('password_conf','Password Confirmation')
			->add_rule('trim')
			->add_rule('min_length', 6)
			->add_rule('max_length', 255)
			->add_rule('required');
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}
}