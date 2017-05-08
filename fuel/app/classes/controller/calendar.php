<?php
class Controller_Calendar extends Controller_App
{

	/**
	 * Calendar index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if (!\Auth::check()){
			\Response::redirect('app/login');
		}
		$view = \View::forge('calendar/index');
		return $view;
	}
}
