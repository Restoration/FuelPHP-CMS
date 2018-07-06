<?php
class Controller_Canvas extends Controller_App
{

	/**
	 * Canvas index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if (!\Auth::check()){
			\Response::redirect('app/login');
		}
		$view = \View::forge('canvas/index');
		return $view;
	}
}
