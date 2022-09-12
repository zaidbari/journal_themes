<?php


namespace App\Controllers;


use App\Lib\Config;
use App\Lib\HTTPRequester;
use Core\View;

class Archives
{


	function lo($v) : array
	{
		// create an array of numbers 1 to 10

		$a = [];
		foreach ((array) $v as $key => $value) {
			$a[] = [ 'number' => $value->number, 'title' => $value->title, 'issue' => $value->issues ];
		}
		return $a;
	}

	public function index()
	{
		$a = (array) HTTPRequester::HTTPGet(Config::env('API_URL') . 'archive', 'archive_cache.json')['body']->data;

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