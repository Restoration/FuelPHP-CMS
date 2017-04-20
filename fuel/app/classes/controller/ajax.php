<?php
class Controller_Ajax extends Controller_Rest
{

/*
    protected $no_data_status = 404;
    protected $no_method_status = 404;
*/
    protected $_supported_formats = array(
        "html" => "text/html",
        "json" => "application/json"
    );
    protected $format = 'json';

	/**
	 * ユーザー重複チェック
	 *
	 * @access  public
	 * @return  Response
	 */
	public function post_user_name_check()
	{
		$val = \Input::post('value');
		$model_user = new Model_User();
		$count = $model_user->username_search($val);
		$this->response($count);
	}
	/**
	 * メールアドレス重複チェック
	 *
	 * @access  public
	 * @return  Response
	 */
	public function post_user_email_check()
	{
		$val = \Input::post('value');
		$model_user = new Model_User();
		$count = $model_user->email_search($val);
		$this->response($count);
	}

	/**
	 * パスワード存在チェック
	 *
	 * @access  public
	 * @return  Response
	 */
	public function post_user_password_check()
	{
		$val = \Input::post('value');
		$model_user = new Model_User();
		$count = $model_user->password_search($val);
		$this->response($count);
	}



	/**
	 * プロフィール画像アップロード
	 *
	 * @access  public
	 * @return  Response
	 */
	public function post_image_profile()
	{
		if(\Input::method()=='POST'){
			list($driver, $user_id) = \Auth::get_user_id();
			$dir = DOCROOT.'assets/img/profile/'.$user_id.'/';

			if(!is_dir($dir)){
				\File::create_dir(DOCROOT.'assets/img/profile/', $user_id, 0755);
			}
            $config = array(
                'path' => DOCROOT.'assets/img/profile/'.$user_id,
                'randomize' => true,
                'max_size' =>  2097152,
                'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
                'new_name' => $user_id.'_'.'profile'
            );
            // アップロード基本プロセス実行
            \Upload::process($config);

			// 有効なファイルがある場合
			if (\Upload::is_valid()){
				$this->deleteImage($dir);
				\Upload::save(); //　画像保存

				$save_file = \Upload::get_files();
				$save_path = $save_file[0]['saved_to'].$save_file[0]['saved_as'];
				$this->image_trimming(50,50,$save_path);
				$size = filesize($save_path);
				$save_file[0]['size'] = $size;

				$model_image = new Model_Image();
				if($model_image->add_action($save_file)){
					$result = true;
				} else {
					$result = false;
				}
			}
			foreach (\Upload::get_errors() as $file){
				for($i =0; $i < count($file['errors']); $i++){
					$errors[] = $file['errors'][$i]['message'];
				}
			}
			if(!empty($errors)){
				$this->response($errors);
			}

			$this->response($result);
		}
	}

	/**
	 * 画像削除
	 *
	 * @access  public
	 * @return  Response
	 */
	public function post_image_delete()
	{
		if($_POST['delete'] == 'image_delete'){
	        $del_path = $_POST['path'];
	        $file_id = $_POST['image_id'];
	        unlink($del_path);//画像削除
	        $model_image = new Model_Image();
	        $model_image->delete_action($file_id);
	        $result_message = '画像の削除が完了しました。';
	        \Session::set_flash('result_message',$result_message);
	        echo json_encode(0);

		} else {
			$result_message = '画像の削除に失敗しました';
			\Session::set_flash('error_message',$result_message);
			echo json_encode(1);
		}
		exit();
	}
}