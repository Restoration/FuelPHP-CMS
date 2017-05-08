<?php
class Controller_Category extends Controller_App
{

	 /**
	 *
	 * Category index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if(!Auth::check()){
			\Response::redirect('app/login');
		}
		$view = \View::forge('category/index');
		$model_category = new Model_Category();
		$result = $model_category->get_result();
		$category_result = $model_category->get_category();
		$r_category_result = $this->category_parse($category_result);
		$view->set('result',$result);
		$view->set('category_result',$category_result);
		$view->set('r_category_result',$r_category_result);
		return $view;
	}

	 /**
	 *
	 * Show category detail page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_preview()
	{
		$id = \Input::get('id');
		if(\Input::method() != 'GET' || !$id){
				\Response::redirect('category/index', 'refresh');
		}
		$view = \View::forge('category/preview');
		$model_category = new Model_Category();
		$preview = $model_category->preview($id);
		$category_result = $model_category->get_result();
		$r_category_result = $this->category_parse($category_result);
		$view->set('id',$id);
		$view->set('result',$preview);
		$view->set('category_result',$category_result);
		$view->set('r_category_result',$r_category_result);
		return $view;
	}

	 /**
	 *
	 * Category add action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_add()
	{
		$post = $_POST['add'];
		if(\Input::method() != 'POST' || !$post){
			\Response::redirect('category/index', 'refresh');
		}
		$errors = $this->validate_category($post);
		if(empty($errors)){
			if ( ! \Security::check_token()){
				$error_message = 'I suspect unauthorized access.';
				echo $error_message;
				exit();
			} else {
				$model_category = \Model_Category::forge();
				$model_category->insert_action();
				$result_message = 'New tag added.';
				\Session::set_flash('result_message',$result_message);
				\Response::redirect('category/index', 'refresh');
			}
		} else {
			$view = \View::forge('category/index');
			$model_category = new Model_Category();
			$result = $model_category->get_result();
			$error_message = 'Add tag failed.';
			\Session::set_flash('post',$post);
			\Session::set_flash('error_message',$error_message);
			\Session::set_flash('errors',$errors);
			$view->set('result',$result);
			return $view;
		}
	}

	/**
	 * Category edit action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_edit()
	{
		$post = $_POST['edit'];
		if(\Input::method() != 'POST' || !$post){
			\Response::redirect('category/index', 'refresh');
		}
		$errors = $this->validate_category($post);
		if(empty($errors)){
			if (!\Security::check_token()){
				$error_message = 'I suspect unauthorized access.';
				echo $error_message;
				exit();
			} else {
				$model_category = \Model_Category::forge();
				$save_flg = isset($_POST['edit']['save']) ? $_POST['edit']['save'] : '';
				if($save_flg != ''){
					$result_message = 'Tag updated.';
				} else {
					$result_message = 'Tags deleted.';
				}
				$model_category->edit_action();
				\Session::set_flash('result_message',$result_message);
				\Response::redirect('category/index', 'refresh');
			}
		} else {
			$view = \View::forge('category/index');
			$model_category = new Model_Category();
			$result = $model_category->get_result();
			$error_message = 'Tag update failed.';
			\Session::set_flash('post',$post);
			\Session::set_flash('error_message',$error_message);
			\Session::set_flash('errors',$errors);
			$view->set('result',$result);
			return $view;
		}
	}

	/**
	 * Category search for Ajax
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_category_search()
	{
		if($_POST['action'] == 'ajaxCategorySearch'){
			$search_value = $_POST['value'];
			$model_category = new Model_Category();
			$result = $model_category->category_search_action($search_value);
			if(empty($search_value)){
				$separate_flg = true;
			} else {
				$separate_flg = false;
			}
			$result['category'] = $this->category_parse($result['category'],$separate_flg);
			echo json_encode($result);
			exit();
		} else {
			echo json_encode(1);
			exit();
		}
	}

	/**
	 * Category add validation
	 *
	 * @access  private
	 * @params  post data
	 * @return  Response
	 */
	private function validate_category($post)
	{
		$validate = \Validation::forge();
		$validate->add('category_name','CategoryName')->add_rule('max_length', 60)->add_rule('required');;
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}

	/**
	 * Category parse
	 *
	 * @access  private
	 * @return  Response
	 */
	private function category_parse($category_result,$separate_flg = true)
	{
		$r_category_result[-1][0]['category_name'] = 'No parent category';
		$r_category_result[-1][0]['category_description'] = 'No';
		$r_category_result[-1][0]['category_slug'] = '';
		for($i=0; $i < count($category_result); $i++){
			if($category_result[$i]['category_parent_id'] != -1){
				$separate = '';
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
				if($separate_flg){
					if(count($r_parent_category_array) == 0){
						$separate = '-';
					}
					$r_parent_category_array = array_unique($r_parent_category_array);
					if(!empty($r_parent_category_array)){
						for($j =0 ; $j < (count($r_parent_category_array) - 1); $j++){
							$separate .= '-';
						}
					}
				}
				$r_category_result[$category_result[$i]['category_parent_id']][$category_result[$i]['category_id']]['category_name'] = $separate.$category_result[$i]['category_name'];
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
		return $r_category_result;
	}
}
?>