<?php
class Controller_Image extends Controller_App
{

	/**
	 * Image index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if (!Auth::check()){
			Response::redirect('app/login');
		}
		$view = View::forge('image/index');
		$model_image = new Model_Image();
		$result = $model_image->get_result();
		$view->set('result',$result[0]);
		$view->set('count',$result[1]);
		return $view;

	}

	/**
	 * Image add action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_add()
	{
		if(Input::method() != 'POST'){
			Response::redirect('image/index');
		}
		$utility = new Utility();
		$model_image = new Model_Image();
		$base_path = Uri::base();
		$path = DOCROOT.'assets/img';
		$utility->make_date_dir($path);
		$year = date('Y');
		$month = date('m');
		$save_path = 'files/'.$year.'/'.$month.'/';
		$config = array(
			'path' => $save_path,
			'randomize' => true,
			'ext_whitelist' => array('jpg', 'jpeg', 'gif', 'png'),
			'max_size' =>  2097152,
			'prefix' => 'img_',
		);
		// Drag and Drop
		//----------------------------------------------------------------
		if(empty($_POST['inputflg'])){
			Upload::process($config);
		    foreach (Upload::get_errors() as $file){
			    for($i =0; $i < count($file['errors']); $i++){
				    $error_array[] = $file['errors'][$i]['message'];
			    }
			    $result['flg'] = 1;
			    $result['data'] = $error_array;
		        echo json_encode($result);
		        exit();
		    }
		    if (Upload::is_valid()){
		        Upload::save();
				$data = $model_image->add_action(Upload::get_files(),$save_path);
			    $result['flg'] = 0;
			    $result['data'] = $data;
		        echo json_encode($result);
		        exit();
		    }
		}
		// input form
		//----------------------------------------------------------------
		// Parse $_FILES
		for($i = 0; $i < count($_FILES['add']['name']); $i++){
			$_FILES['add']['name'] = array_filter($_FILES['add']['name'], "strlen");
			$_FILES['add']['name'] = array_values($_FILES['add']['name']);
			$_FILES['add']['type'] = array_filter($_FILES['add']['type'], "strlen");
			$_FILES['add']['type'] = array_values($_FILES['add']['type']);
			$_FILES['add']['tmp_name'] = array_filter($_FILES['add']['tmp_name'], "strlen");
			$_FILES['add']['tmp_name'] = array_values($_FILES['add']['tmp_name']);
			$_FILES['add']['error'] = array_filter($_FILES['add']['error'], "strlen");
			$_FILES['add']['error'] = array_values($_FILES['add']['error']);
			$_FILES['add']['size'] = array_filter($_FILES['add']['size'], "strlen");
			$_FILES['add']['size'] = array_values($_FILES['add']['size']);
		}

		Upload::process($config);
		// Error
        foreach (Upload::get_errors() as $file){
		    for($i =0; $i < count($file['errors']); $i++){
			    $error_array[] = $file['errors'][$i]['message'];
		    }
		    $result_message = 'Failed to add image.';
		    Session::set_flash('error_message',$result_message);
		    Session::set_flash('error',$error_array);
		    Response::redirect('image/index', 'refresh');
        }
		if (Upload::is_valid()){
			Upload::save();
			$model_image->add_action(Upload::get_files(),$save_path);
			$result_message = 'The addition of the image is completed.';
			Session::set_flash('result_message',$result_message);
		} else {
			$error_message = 'Upload Error. File is not valid.';
			Session::set_flash('error_message',$error_message);
		}
		$view = View::forge('image/index');
		$result = $model_image->get_result();
		$view->set('result',$result[0]);
		$view->set('count',$result[1]);
		return $view;
	}

	/**
	 * Image delete action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_delete()
	{
		if($_POST['delete'] == 'image_delete'){
	        $del_path = $_POST['path'];
	        $file_id = $_POST['image_id'];
	        // Remove image files
	        unlink($del_path);
	        $model_image = new Model_Image();
	        $model_image->delete_action($file_id);
	        $result_message = 'Image deletion is completed.';
	        Session::set_flash('result_message',$result_message);
	        echo json_encode(0);
		} else {
			$result_message = 'Failed to delete image.';
			Session::set_flash('error_message',$result_message);
			echo json_encode(1);
		}
		exit();
	}

	/**
	 * Show iframe
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_image_iframe()
	{
		if(!Auth::check()){
			Response::redirect('app/login');
		}
		$view = View::forge('image/image_iframe');
		$model_image = new Model_Image();
		$result = $model_image->get_result();
		$view->set('result',$result[0]);
		$view->set('count',$result[1]);
		return $view;
	}
}
