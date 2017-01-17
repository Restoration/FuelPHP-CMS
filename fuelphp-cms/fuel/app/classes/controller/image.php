<?php
class Controller_Image extends Controller_App
{
	public function action_index()
	{

		if (!Auth::check())
		{
			Response::redirect('app/login');
		}

		$view = View::forge('image/index');
		$model_image = new Model_Image();
		$result = $model_image->get_result();
		$view->set('result',$result[0]);
		$view->set('count',$result[1]);
		return $view;

	}
	//404エラー
	public function action_404()
	{
		return Response::forge(Presenter::forge('main/404'), 404);
	}

	public function action_add()
	{
		$model_utility = new Model_Utility();
		// このアップロードのカスタム設定
		$base_path = Uri::base();

		$path = Uri::base().'files';
		$model_utility->make_date_dir($path);

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

		//ドラッグアンドドロップ
		//----------------------------------------------------------------
		if(empty($_POST['inputflg'])){
			Upload::process($config);
		    foreach (Upload::get_errors() as $file)
		    {
			    for($i =0; $i < count($file['errors']); $i++){
				    $error_array[] = $file['errors'][$i]['message'];
			    }
			    $result['flg'] = 1;
			    $result['data'] = $error_array;
		        echo json_encode($result);
		        exit();
		    }
		    // 有効なファイルがある場合
		    if (Upload::is_valid())
		    {
		        Upload::save();
				$model_image = new Model_Image();
				$data = $model_image->add_action(Upload::get_files(),$save_path);
			    $result['flg'] = 0;
			    $result['data'] = $data;
		        echo json_encode($result);
		        exit();
		    }
		}
		//インプットファイル
		//----------------------------------------------------------------
		//$_FILESの値を整形
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

        foreach (Upload::get_errors() as $file)
        {

		    for($i =0; $i < count($file['errors']); $i++){
			    $error_array[] = $file['errors'][$i]['message'];
		    }

		    $result_message = 'Failed to add image.';
		    Session::set_flash('error_message',$result_message);
		    Session::set_flash('error',$error_array);

		    Response::redirect('image/index', 'refresh');
        }
		// 有効なファイルがある場合
		if (Upload::is_valid())
		{
			Upload::save();
			$model_image = new Model_Image();
			$model_image->add_action(Upload::get_files(),$save_path);
			$result_message = 'The addition of the image is completed.';
		}
		Session::set_flash('result_message',$result_message);
		$view = View::forge('image/index');
		$model_image = new Model_Image();
		$model_utility = new Model_Utility();
		$result = $model_image->get_result();
		$view->set('result',$result[0]);
		$view->set('count',$result[1]);
		return $view;
	}
	//削除
	public function action_delete()
	{
		if($_POST['delete'] == 'image_delete'){
	        $del_path = $_POST['path'];
	        $file_id = $_POST['image_id'];
	        unlink($del_path);//画像削除
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
	public function action_image_iframe()
	{
		if (!Auth::check())
		{
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
