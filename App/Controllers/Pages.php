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

	public function reviewers()
	{
		$data = HTTPRequester::HTTPGet( Config::env('API_URL'). 'posts/outstanding_reviewers')['body']->data;
		View::render('generic/reviewers',
		[
			'page_title' => "Outstanding Reviewers",
			'content' => isset($data->outstanding_reviewers) ? (array) $data->outstanding_reviewers : [],
		]);
	}
}