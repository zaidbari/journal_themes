<?php


namespace App\Controllers;

use Core\View;

class ErrorLog
{

	public function request(  )
	{
		$log = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/request.log');

		View::render('logs/index', [
			'page_title' => "Request Logs",
			'content' => $log
		]);
	}

	public function error(  )
	{
		$log = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/request.log');
		View::render('logs/index', [
			'page_title' => "Error Logs",
			'content' => $log
		]);
	}
}