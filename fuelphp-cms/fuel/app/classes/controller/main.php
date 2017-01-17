<?php
class Controller_Main extends Controller_App
{
	public function action_index()
	{

		if (!Auth::check())
		{
			Response::redirect('app/login');
		}

		$view = View::forge('main/index');
		$model_main = new Model_Main();
		$model_utility = new Model_Utility();
		$model_tag = new Model_Tag();
		$model_category = new Model_Category();
		$result = $model_main->get_result();
		$tag_result = $model_tag->get_tag();
		$category_result = $model_category->get_category();
		$view->set('result',$result);
		$view->set('tag_result',$tag_result);
		$view->set('category_result',$category_result);
		return $view;

	}
	//404エラー
	public function action_404()
	{
		return Response::forge(Presenter::forge('main/404'), 404);
	}
	//追加アクション
	public function action_add()
	{
		if($_POST){
			//バリデーションスタート
			$post = $_POST['add'];
			$errors = $this->validate_post($post);

			if(empty($errors)){
				if ( ! \Security::check_token()){
					$error_message = 'I suspect unauthorized access.';
					echo $error_message;
					exit();
				} else {
					$model_main = Model_Main::forge();
					$model_main->insert_action();
					$result_message = 'Post article added.';
					Session::set_flash('result_message',$result_message);
					Response::redirect('main/index', 'refresh');
				}
			} else {
				$view = View::forge('main/index');
				$model_main = new Model_Main();
				$model_utility = new Model_Utility();
				$model_tag = new Model_Tag();
				$model_category = new Model_Category();
				$result = $model_main->get_result();
				$tag_result = $model_tag->get_tag();
				$category_result = $model_category->get_category();
				$view->set('post',$post);
				$view->set('result',$result);
				$view->set('tag_result',$tag_result);
				$view->set('category_result',$category_result);
				$error_message = 'Failed to add posted article.';
				Session::set_flash('error_message',$error_message);
				Session::set_flash('errors',$errors);
				Session::set_flash('post',$post);
				Response::forge(View::forge('main/index'));
				return $view;
			}
		}
	}
	//編集画面表示
	public function action_preview()
	{
		$id = $_GET['id'];
		$edit_key = $_GET['edit_key'];
		$view = View::forge('main/preview');
		$model_main = new Model_Main();
		$preview = $model_main->preview($id);
		if($edit_key != $preview[0]['post_key']){
			Response::redirect('main/index', 'refresh');
		}
		$view->set('result',$preview);
		return $view;
	}
	private function validate_post($post)
	{
		$validate = Validation::forge();
		$validate->add('post_title','Title')->add_rule('max_length', 60)->add_rule('required');;
		$validate->add('post_message','Message')->add_rule('required');
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}





}
