<?php

class Controller_App extends Controller_Template
{

	/**
	 * Before action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function before()
	{
		parent::before();
		$post_methods = array(
			'created',
			'updated',
			'removed',
		);
		$method = \Uri::segment(2);
		$auth_methods = array(
			'logined',
			'logout',
			'update',
			'remove',
		);
		if (in_array($method, $auth_methods) && !\Auth::check()) {
			\Response::redirect('app/login');
		}
		$nologin_methods = array(
			'login',
		);
		if (in_array($method, $nologin_methods) && \Auth::check()) {
			\Response::redirect('main');
		}
	}

	/**
	 * Check login parameter
	 *
	 * @access  public
	 * @return  Response
	 */
	private function validate_login()
	{
		$validation = \Validation::forge();
		$validation->add('username', 'UserName')
			->add_rule('required')
			->add_rule('min_length', 4)
			->add_rule('max_length', 15);
		$validation->add('password', 'PassWord')
			->add_rule('required')
			->add_rule('min_length', 6)
			->add_rule('max_length', 20);
		$validation->run();
		return $validation;
	}

	/**
	 * Login action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_login()
	{
		$username = \Input::post('username', null);
		$password = \Input::post('password', null);
		$result_validate = '';
		if ($username !== null && $password !== null) {
			$validation = $this->validate_login();
			$errors = $validation->error();
			if (empty($errors)) {
				// Check Login
				$auth = \Auth::instance();
				if ($auth->login($username, $password)) {
					// Success
					\Response::redirect('main');
				}
				$result_validate[] = 'I failed to login.';
			} else {
				$result_validate = $validation->error_message();
			}
		}
		$this->template->title = 'Login';
		$this->template->content = \View::forge('app/login');
		$this->template->content->set_safe('username',$username);
		$this->template->content->set_safe('errmsg', $result_validate);
	}

	/**
	 * Logout action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_logout()
	{
		\Auth::logout();
		\Response::redirect('app/login');
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		$this->template->title = '404 Not Found';
		$this->template->content = \View::forge('app/404');
	}

}