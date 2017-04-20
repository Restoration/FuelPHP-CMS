<?php
class Controller_Postlist extends Controller_App
{
	public function action_index()
	{
		if (!\Auth::check()){
			\Response::redirect('app/login');
		}
		$view = \View::forge('postlist/index');
		$model_postlist = new Model_Postlist();
		$result = $model_postlist->get_result();
		$view->set('result',$result);
		return $view;

	}
	//404エラー
	public function action_404()
	{
		return \Response::forge(\Presenter::forge('main/404'), 404);
	}
	//編集画面表示
	public function action_preview()
	{
		$id = $_GET['id'];
		$view = \View::forge('postlist/preview');
		$model_utility = new Model_Utility();
		$model_tag = new Model_Tag();
		$model_category = new Model_Category();
		$preview = $model_utility->preview($id);
		$tag_result = $model_tag->get_tag();
		$category_result = $model_category->get_category();
		$view->set('result',$preview);
		$view->set('tag_result',$tag_result);
		$view->set('category_result',$category_result);
		return $view;
	}
	//編集,削除アクション
	public function action_edit()
	{
		if(\Input::method() == 'POST'){
	   		if (!\Security::check_token()){
		   		$error_message = 'I suspect unauthorized access.';
				echo $error_message;
				exit();
			} else {
				$validate = \Validation::forge();
				$post = $_POST['edit'];
				$validate->add('post_title','Title')->add_rule('max_length', 60)->add_rule('required');;
				$validate->add('post_message','Message')->add_rule('required');
				$errors = !$validate->run($post) ? $validate->error() : array();
				$values = $validate->validated();

				foreach ($errors as $key => &$error)
				{
					$values[$key] = $error->value;
					$error = ''.$error;
				}
				if(empty($errors)){
					$model_postlist = \Model_Postlist::forge();
					$save_flg = isset($_POST['edit']['save']) ? $_POST['edit']['save'] : '';
					if($save_flg != ''){
						$result_message = 'Posted articles updated.';
					} else {
						$result_message = 'Deleted posted article.';
					}
					$model_postlist->edit_action();
					\Session::set_flash('result_message',$result_message);
					\Response::redirect('postlist/index');
				} else {
					$error_message = 'Posting article update failed.';
					\Session::set_flash('error_message',$error_message);
					\Session::set_flash('errors',$errors);
					\Response::redirect('postlist/index');
				}
			}
		}
	}

	public function action_post_search()
	{
		if($_POST['action'] == 'ajaxPostSearch'){
			$search_value = $_POST['value'];
			$model_postlist = new Model_Postlist();
			$result = $model_postlist->post_search_action($search_value);
			echo json_encode($result);
			exit();
		} else {
			echo json_encode(1);
			exit();
		}
	}

	public function action_post_category_search()
	{
		if($_POST['action'] == 'ajaxPostCategorySearch'){
			$model_category = new Model_Category();
			$category_result = $model_category->get_category();

			$r_category_result = array();
			$r_category_result['-1'][0]['category_name'] = 'No parent category';
			$r_category_result['-1'][0]['category_description'] = 'No';
			$r_category_result['-1'][0]['category_slug'] = '';
			for($i=0; $i < count($category_result); $i++){
				if($category_result[$i]['category_parent_id'] != -1){
					$parent_category_array = array();
					$r_parent_category_array = array();
					for($j=0; $j < count($category_result); $j++){
						if($category_result[$i]['category_parent_id'] == $category_result[$j]['category_id']){
							if(!empty($category_result[$j]['category_parent_id'])){
								$parent_category_array['category_parent_id'] = $category_result[$j]['category_parent_id'];
								$r_parent_category_array[] = $category_result[$i]['category_parent_id'];
							}
						}
						if(!empty($parent_category_array['category_parent_id'])){
							if(!empty($category_result[$j]['category_parent_id'])){
								$r_parent_category_array[]=  $category_result[$j]['category_parent_id'];
							}
						}
					}
					$r_category_result[$category_result[$i]['category_parent_id']][$category_result[$i]['category_id']]['category_name'] = $category_result[$i]['category_name'];
					$r_category_result[$category_result[$i]['category_parent_id']][$category_result[$i]['category_id']]['category_description'] = $category_result[$i]['category_description'];
					$r_category_result[$category_result[$i]['category_parent_id']][$category_result[$i]['category_id']]['category_slug'] = $category_result[$i]['category_slug'];
				} else {
					$r_category_result[$category_result[$i]['category_id']][0]['category_name'] = $category_result[$i]['category_name'];
					$r_category_result[$category_result[$i]['category_id']][0]['category_description'] = $category_result[$i]['category_description'];
					$r_category_result[$category_result[$i]['category_id']][0]['category_slug'] = $category_result[$i]['category_slug'];
				}
			}
			ksort($r_category_result);
			foreach($r_category_result as $key => $val){
				ksort($r_category_result[$key]);
			}

			echo json_encode($r_category_result,JSON_PRETTY_PRINT);
			exit();
		} else {
			echo json_encode(1);
			exit();
		}
	}




}
?>