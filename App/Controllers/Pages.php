<?php


namespace App\Controllers;


use App\Lib\Config;
use App\Lib\HTTPRequester;
use Core\View;

class Pages
{

	public function index( $request )
	{
		$d = HTTPRequester::HTTPGet(Config::env('API_URL'). 'pages/' . $request->param('slug'));

		if(empty($d['body'])) {
			header('Location: /404');
		}

		View::render('generic/index',
			[
				'page_title' => $d['body']->data->title,
				'content' => $d['body']->data->content,
			]);


	}
	
	/* TEMP ROUTE METHOD */
	public function submit()
	{
	    View::render('tmp_submission_page');
	}
}