<?php namespace App\Controllers;

use App\Lib\Config;
use App\Lib\HTTPRequester;
use \Core\View;
use \Core\Controller;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends Controller
{

	/**
	 * Show the index page
	 *
	 *
	 * @return void
	 */
	public function index()
	{
		$journal_data = HTTPRequester::HTTPGet(Config::env('API_URL') . 'home')['body']->data;

		/* FOR REGULAR PUBLISHING TYPE*/
		if(Config::env('PUBLISHING_TYPE') == "Regular") {
			$articles = HTTPRequester::HTTPGet(Config::env('API_URL') . 'issues/current/articles')['body'];
			$articles_data = (array) $articles->data ?? [];
		} else {
			/* FOR CONTINUOUS PUBLISHING TYPE */
			$articles = (array) HTTPRequester::HTTPGet(Config::env('API_URL') . 'articles')['body']->data;
			$articles_data = $articles;
		}

		View::render('home/index',
			[
				'page_title' => 'Home',
				'journal' => $journal_data->journal,
				'short_desc' => $journal_data->short_desc,
				'chief_editor' => $journal_data->chief_editor,
				'metrics' => $journal_data->metrics,
				'articles' => $articles_data,
				'news' => $journal_data->news,
				'events' => $journal_data->events,
				'announcements' => $journal_data->announcements,
				'featured_articles' => $journal_data->featured_articles
			]);

	}
}
