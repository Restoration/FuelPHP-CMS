<?php
class Controller_Rss extends Controller_App
{

	/**
	 * RSS index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if (!\Auth::check()){
			\Response::redirect('app/login');
		}
		$view = \View::forge('rss/index');
		$model_rss = new Model_Rss();
		$url = $model_rss->get_result();
		$url_array = array(0=>'',1=>'',2=>'');
		if(!empty($url)){
			for($i = 0; $i < count($url); $i++){
				if(!preg_match("/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/",$url[$i]['url'])){
					continue;
				}
				$url_array[$i] = $url[$i]['url'];
				$data[$url[$i]['url']] = $this->get_rss($url[$i]['url'],3);
			}
			$view->set('data',$data);
		}
		$view->set('result',$url_array);
		return $view;
	}

    /**
    * Get RSS proccess
    *
    * @param (string) url
    * @param (int) limit
    * @return string
    */
    public function get_rss($url,$limit){
    	$ns_dc = 'http://purl.org/dc/elements/1.1/';
    	$ns_rdf = 'https://www.w3.org/1999/02/22-rdf-syntax-ns';
    	if(empty($url)){
	    	return false;

    	}
	    $xml = simplexml_load_file($url);
    	if($xml === false){
    		return false;
    	}
    	$cnt = 0;
	    foreach ( $xml->channel->item as $item) {
	        $result[$cnt]['title'] = $item->title;
	        $result[$cnt]['description'] = mb_strimwidth (strip_tags($item->description), 0 , 110, "…Read More", "utf-8");
	        $result[$cnt]['link'] = $item->link;
	        $result[$cnt]['date'] = date("Y年 n月 j日", strtotime($item->pubDate));
	        if($limit === ($cnt+1) ){
		        break;
	        }
	        $cnt++;
	    }
	    return $result;
	}


	 /**
	 *
	 * RSS URL add action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_add()
	{
		if(empty($_POST['add'])){
			\Response::redirect('rss/index', 'refresh');
		}
		$post = $_POST['add'];
		if(\Input::method() != 'POST' || !$post){
			\Response::redirect('rss/index', 'refresh');
		}
		$errors = $this->validate_rss($post);
		if(empty($errors)){
			if ( ! \Security::check_token()){
				$error_message = 'I suspect unauthorized access.';
				echo $error_message;
				exit();
			} else {
				$model_rss = \Model_Rss::forge();
				$model_rss->insert_action($post);
				$result_message = 'New RSS URL added.';
				\Session::set_flash('result_message',$result_message);
				\Response::redirect('rss/index', 'refresh');
			}
		} else {
			$view = \View::forge('rss/index');
			$model_rss = new Model_Rss();
			$result = $model_rss->get_result();
			$error_message = 'Add tag failed.';
			\Session::set_flash('post',$post);
			\Session::set_flash('error_message',$error_message);
			\Session::set_flash('errors',$errors);
			$view->set('result',$result);
			return $view;
		}
	}


	/**
	 * RSS URL add validation
	 *
	 * @access  private
	 * @params  post data
	 * @return  Response
	 */
	private function validate_rss($post)
	{
		$validate = \Validation::forge();
		$validate->add('rss_1','RSS Feed URL')->add_rule('max_length', 60)->add_rule('valid_url');
		$validate->add('rss_2','RSS Feed URL')->add_rule('max_length', 60)->add_rule('valid_url');
		$validate->add('rss_3','RSS Feed URL')->add_rule('max_length', 60)->add_rule('valid_url');
		$errors = !$validate->run($post) ? $validate->error() : array();
		return $errors;
	}


}
