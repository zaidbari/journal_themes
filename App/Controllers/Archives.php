<?php


namespace App\Controllers;


use App\Lib\Config;
use App\Lib\HTTPRequester;
use Core\View;

class Archives
{

	function lo($v) : array
	{
		$a = [];
		foreach ((array) $v as $key => $value) {
			$a[] = [ 'number' => $value->number, 'title' => $value->title, 'issue' => $value->issues ];
		}
		return $a;
	}

	public function index()
	{
		$a = (array) HTTPRequester::HTTPGet(Config::env('API_URL') . 'archive')['body']->data;

		$archives = [];
		foreach ($a as $key => $value) {
			$archives[] = [ 'year' => $key, 'volumes' => $this->lo($value) ];
		}

		View::render('archives/index', [
			'page_title' => 'Archives',
			'archives' => $archives,
		]);

	}
}