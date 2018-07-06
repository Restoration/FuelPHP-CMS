<?php
class Controller_Markdown extends Controller_App
{

	/**
	 * Markdown index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if (!\Auth::check()){
			\Response::redirect('app/login');
		}
		$model_markdown = new Model_Markdown();
		$result = $model_markdown->get_result();
		$view = \View::forge('markdown/index');
		$view->set('result',$result);
		return $view;
	}

	/**
	 * Markdown edit page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_edit()
	{
		if (!\Auth::check()){
			\Response::redirect('app/login');
		}
		$view = \View::forge('markdown/edit');
		$id = \Input::get('id');
		$result = array(
			'id' => '',
			'title' => '',
			'text' => '',
		);
		if(!empty($id)){
			$model_markdown = new Model_Markdown();
			$result = $model_markdown->preview($id);
			$result = $result[0];
		}
		$view->set('result',$result);
		return $view;
	}

	/**
	 * Markdown add action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_update()
	{
		if(\Input::method() == 'POST'){
			$post = $_POST['edit'];
			$errors = $this->validate_markdown($post);
			if(!empty($errors)){
				if ( !\Security::check_token()){
					$error_message = 'I suspect unauthorized access.';
					echo $error_message;
					exit();
				}
				$error_message = 'Add Markdown Text failed.';
				\Session::set_flash('error_message',$error_message);
				\Session::set_flash('errors',$errors);
				Response::redirect('markdown', 'refresh');
			} else {
				if($post['save'] == 'Download'){
					$this->file_download($post);
				}
				$model_markdown = \Model_Markdown::forge();
				$model_markdown->update_action($post);
				$result_message = 'New Markdown text added.';
				\Session::set_flash('result_message',$result_message);
				\Response::redirect('markdown', 'refresh');
			}
		}
		\Response::redirect('markdown', 'refresh');
	}

	/**
	 * Update validation
	 *
	 * @access  private
	 * @return  Response
	 */
	private function validate_markdown($post)
	{
		$validate = \Validation::forge();
		$validate->add('title','Title')->add_rule('max_length', 60)->add_rule('required');
		$validate->add('text','Text')->add_rule('required');
		$validate->add('content','Content')->add_rule('required');
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}

	/**
	 * Markdown download action
	 *
	 * @access  private
	 * @return  Response
	 */
	private function file_download($post)
	{
		$path = APPPATH.'tmp/';
		$file = $post['title'].'.md';
		$exists = File::exists($path.$file);
		if($exists){
			File::delete($path.$file);
		}
		if(File::create($path,$file,$post['content'])){
			File::download($path.$file,$file);
		}
	}
}
