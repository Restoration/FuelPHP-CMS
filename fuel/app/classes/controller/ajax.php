<?php
class Controller_Ajax extends Controller_Rest
{
    protected $_supported_formats = array(
        "html" => "text/html",
        "json" => "application/json"
    );
    protected $format = 'json';

	/**
	 * Calendar index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function get_task()
	{
		$val = \Input::post('value');
		$model_user = new Model_User();
		$count = $model_user->username_search($val);
		$this->response($count);
	}
}
