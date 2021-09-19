<?php namespace App\Controllers;

use App\Lib\Config;
use App\Lib\HTTPRequester;
use Core\View;

class Articles
{

	public function track()
	{
		View::render('track/index', [
			'page_title' => 'Track your Article',
		]);
	}
	public function show($request)
	{
		$id = $request->param('id');
		$a = HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles/' . $id . '/show', 'single_article_'. $id . '_cache.json')['body'];
		$html = "";
		if($a != null && !isset($a->error)) {
			$article = $a->data;
			$html = array_search( Config::env('APP_ABBRV') . '-' . $id.'.html',array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/html'), array('..', '.')),true);
			if($html != false) {
				$html = str_replace( 'image-' . $id, '/files/html/image-' . $id ,file_get_contents($_SERVER['DOCUMENT_ROOT'].'/files/html/' . Config::env('APP_ABBRV') . '-' . $id . '.html'));
			}
		} else {
			header('Location: /404');
			exit();
		}

		View::render('articles/index', [
			'page_title' => $article->title,
			'article' => $article,
			'html' => $html,
			'self_link' => Config::env('DOMAIN') . Config::env('DOI') . '-' . $id
		]);
	}

	public function pdf($request, $response)
	{
		$id = $request->param('id');
		$pdf = array_search( Config::env('APP_ABBRV') . '-' .$id.'.pdf',array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/pdf'), array('..', '.')),true);
		if($pdf != false) {
			$pdf = $_SERVER['DOCUMENT_ROOT'].'/files/pdf/' . Config::env('APP_ABBRV') . '-' . $id . '.pdf';
		} else {
			header('location: /404');
			exit();
		}
		$response->file($pdf);
	}



	public function current()
	{

		$articles = HTTPRequester::HTTPGet(Config::env('API_URL') . 'issues/current/articles', 'current_issue_cache.json')['body'];
		$articles_data = (array) $articles->data ?? [];

		View::render('articles/list', [
				'page_title' => 'Current Issue',
				'articles' => $articles_data
		]);
	}

	public function latest()
	{
		$articles_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles', 'latest_issue_cache.json')['body']->data;

		View::render('articles/list', [
			'page_title' => 'Latest Articles',
			'articles' => $articles_data
		]);
	}
}