<?php
class Controller_Task extends Controller_App
{

	/**
	 * Task index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if (!\Auth::check()){
			\Response::redirect('app/login');
		}
		$view = \View::forge('task/index');
		return $view;
	}
}
