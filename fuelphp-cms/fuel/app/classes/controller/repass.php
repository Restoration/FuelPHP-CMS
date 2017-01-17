<?php

class Controller_Repass extends Controller_App
{

	//パスワードの自動再発行
	public function action_index(){

		if(Input::method() == 'POST'){
			$username = Input::post('username');
			$email = Input::post('email');
			$model_user = new Model_User();
			$username_count = $model_user->username_search($username);
			if($username_count > 0){

				$auth = Auth::instance();
				$repass = $auth->reset_password($username);
				$data['repass'] = $repass;
				$data['username']= $username;
				$data['email'] = $email;
				$data['anchor'] = 'app/login/';
				$body = View::forge('repass/email/repass',$data);

				$sendmail = Email::forge();
				$sendmail->from('aaaaa@aaaa.com','');
				$sendmail->to($email,$username);
				$sendmail->subject('Reissue your password');
				$sendmail->html_body($body);
				$sendmail->send();
				Response::redirect('repass/complete');
			}else{
				$result_message = 'No such user.';
				Session::set_flash('error_message',$result_message);
			}
		}
		$this->template->title = 'Reissue your password';
		$this->template->content = View::forge('repass/index');
	}

	public function action_complete(){
		$this->template->title = 'Password reissue complete.';
		$this->template->content = View::forge('repass/complete');
	}
}