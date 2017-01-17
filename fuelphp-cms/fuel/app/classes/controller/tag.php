<?php
class Controller_Tag extends Controller_App
{
	public function action_index()
	{

		if (!Auth::check())
		{
			Response::redirect('app/login');
		}

		$view = View::forge('tag/index');
		$model_tag = new Model_Tag();
		$result = $model_tag->get_result();
		$view->set('result',$result);
		return $view;
	}
	//404エラー
	public function action_404()
	{
		return Response::forge(Presenter::forge('main/404'), 404);
	}
	//編集画面表示
	public function action_preview()
	{
		$id = $_GET['id'];
		$view = View::forge('tag/preview');
		$model_utility = new Model_Utility();
		$model_tag = new Model_Tag();
		$preview = $model_tag->preview($id);
		$tag_result = $model_tag->get_result();
		$view->set('result',$preview);
		$view->set('tag_result',$tag_result);
		return $view;
	}
	//タグ追加
	public function action_add()
	{
		if($_POST){
			$post = $_POST['add'];
			$errors = $this->validate_tag($post);
			if(empty($errors)){
				if ( ! \Security::check_token()){
					$error_message = 'I suspect unauthorized access.';
					echo $error_message;
					exit();
				} else {
					$model_tag = Model_Tag::forge();
					$model_tag->insert_action();
					$result_message = 'New tag added.';
					Session::set_flash('result_message',$result_message);
					Response::redirect('tag/index', 'refresh');
				}
			} else {
				$view = View::forge('tag/index');
				$model_tag = new Model_Tag();
				$result = $model_tag->get_result();
				$error_message = 'Add tag failed.';
				Session::set_flash('post',$post);
				Session::set_flash('error_message',$error_message);
				Session::set_flash('errors',$errors);
				$view->set('result',$result);
				return $view;
			}
		}
	}

	//編集,削除アクション
	public function action_edit()
	{
		if($_POST){
			$post = $_POST['edit'];
			$errors = $this->validate_tag($post);
			if(empty($errors)){
				if ( ! \Security::check_token()){
					$error_message = 'I suspect unauthorized access.';
					echo $error_message;
					exit();
				} else {
					$model_tag = Model_Tag::forge();
					$save_flg = isset($_POST['edit']['save']) ? $_POST['edit']['save'] : '';
					if($save_flg != ''){
						$result_message = 'Tag updated.';
					} else {
						$result_message = 'Tags deleted.';
					}
					$model_tag->edit_action();
					Session::set_flash('result_message',$result_message);
					Response::redirect('tag/index', 'refresh');
				}
			} else {
				$view = View::forge('tag/index');
				$model_tag = new Model_Tag();
				$result = $model_tag->get_result();
				$error_message = 'Tag update failed.';
				Session::set_flash('post',$post);
				Session::set_flash('error_message',$error_message);
				Session::set_flash('errors',$errors);
				$view->set('result',$result);
				return $view;
			}
		}
	}

	public function action_tag_search()
	{
		if($_POST['action'] == 'ajaxTagSearch'){
			$search_value = $_POST['value'];
			$model_tag = new Model_Tag();
			$result = $model_tag->tag_search_action($search_value);
			echo json_encode($result);
			exit();
		} else {
			echo json_encode(1);
			exit();
		}
	}

	private function validate_tag($post)
	{
		$validate = Validation::forge();
		$validate->add('tag_name','TagName')->add_rule('max_length', 60)->add_rule('required');;
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}
}
?>