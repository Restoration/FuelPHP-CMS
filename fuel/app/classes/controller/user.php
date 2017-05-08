<?php
class Controller_User extends Controller
{
	/**
	 * User index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		$auth = \Auth::instance();
		$username = $auth->get_screen_name();
		$email = $auth->get_email();
		$view = \View::forge('user/index');
		$model_user = new Model_User();
		$view->set('username',$username);
		$view->set('email',$email);
		return $view;
	}

	/**
	 * User Update Validation
	 *
	 * @access  private
	 * @params  post data
	 * @return  Response
	 */
	private function validate_user_update($post)
	{
		$username = $post['username'];
		$email = $post['email'];
		$validate = \Validation::forge();
		$validate->add_callable('MyValidation');

		$validate->add('email','Email')
			->add_rule('trim')
			->add_rule('valid_email')
			->add_rule('max_length', 255)
			->add_rule('required');

		$validate->add('old_password','Old Password')
			->add_rule('trim')
			->add_rule('min_length', 6)
			->add_rule('max_length', 255)
			->add_rule('required')
			->add_rule('password_find');
		$validate->set_message('password_find', 'Password not found');

		$validate->add('password','New Password')
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

	/**
	 * Email Validation
	 *
	 * @access  private
	 * @params  post data
	 * @return  Response
	 */
	private function validate_user_emial_update($post)
	{
		$email = $post['email'];
		$validate = \Validation::forge();

		$validate->add('email','Email')
			->add_rule('trim')
			->add_rule('valid_email')
			->add_rule('max_length', 255)
			->add_rule('required');
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}

	/**
	 * User Update
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_update()
	{
		if(\Input::method() == 'POST'){
			$auth = \Auth::instance();
			$model_user = new Model_User();
			$post = $_POST['user'];
			if(!empty($post['email']) && empty($post['old_password']) && empty($post['password']) && empty($post['password_conf']) ){
				$errors = $this->validate_user_emial_update($post);
				$flg = 0;
			} else {
				$errors = $this->validate_user_update($post);
				$flg = 1;
			}
			if(!empty($errors) || !\Security::check_token()){
				if(!\Security::check_token()){
					$error_message = 'I suspect unauthorized access.';
				} else {
					$error_message = 'User update failed.';
				}
				\Session::set_flash('error_message',$error_message);
				\Session::set_flash('errors',$errors);
				\Session::set_flash('post',$post);
				\Response::redirect('user/index', 'refresh');
			}
			if($model_user->edit_action($post,$flg)){
				$result_message = 'User update completed.';
				\Session::set_flash('result_message',$result_message);
			} else {
				$error_message = 'User update failed.';
				\Session::set_flash('error_message',$error_message);
			}
			\Response::redirect('user/index');
		}
		\Response::redirect('user/index');
	}
}