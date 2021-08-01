<?php

namespace Core;

use App\Lib\Config;
use App\Lib\HTTPRequester;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{

	/**
	 * Render a view template using Twig
	 *
	 * @param string $template The template file
	 * @param array  $args     Associative array of data to display in the view (optional)
	 *
	 * @return void
	 */
	public static function render( string $template, array $args = [] )
	{
		static $twig = null;

		if($twig == null) {


			/** TODO: Theme
			 * Replace code for dynamic theme options
			 */
			$theme = Config::env('JOURNAL_THEME');

			$loader = new FilesystemLoader([
				'resources/views/themes/'.$theme.'/pages',
				'resources/views/themes/'.$theme.'/partials'
			]);

			$twig = new Environment($loader, [
				'cache' => Config::CACHE,
				'auto_reload' => Config::RELOAD
			]);

			/*
			 * Global Variables available everywhere in app
			 *
			 */

			// Required to render menu items both in header and footer
			$twig->addGlobal('menu',(array)  HTTPRequester::HTTPGet(Config::env('API_URL') . 'pages')['body']->data);

			// Required to render correct theme according to theme settings
			$twig->addGlobal('THEME', $theme);

			$twig->addGlobal('APP_NAME', Config::env('APP_NAME'));
			$twig->addGlobal('DOI', Config::env('DOI'));
			$twig->addGlobal('DOMAIN', Config::env('DOMAIN'));
			$twig->addGlobal('CURRENT_ROUTE', Router::currentRoute());

			// Converts array of stdObjects into iterable array
			$twig->addFilter( new TwigFilter('cast_to_array', function ($obj) { return (array) $obj;}));

			// Required to check availability of PDF file for an article
			$twig->addFunction(new TwigFunction('get_pdf', function ($content) {
				return array_search(
					Config::env('APP_ABBRV') . '-' . $content .'.pdf',
					array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/pdf'), array('..', '.')),true
				);
			}));

			// Required to check availability of HTML file for an article
			$twig->addFunction(new TwigFunction('get_html', function ($content) {
				return array_search(
					Config::env('APP_ABBRV') . '-' . $content .'.html',
					array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/files/html'), array('..', '.')),true
				);
			}));

			// Required to get a variable from .env file
			$twig->addFunction(new TwigFunction('get_var', function ($content) { return Config::env($content); }));
		}

		try {
			echo $twig->render($template . '.twig', $args);
		} catch (LoaderError | RuntimeError | SyntaxError $e) {
			echo '<pre>' . $e . '</pre>';
		}
	}
}
