<?php namespace Core;

use \Klein\Klein;
/**
 * Router
 *
 * PHP version 7.0
 */
class Router extends Klein {
	// /**
	// * Parameters from the matched route
	// * @var array
	// */
	//protected $params = [];

	public function res($path, $controllerAction, $method = 'GET')
	{
		$handler = explode('@',$controllerAction);
		$controller = $handler[0];
		$action = $handler[1];
		$controller = 'App\Controllers\\' . $controller;
		if (class_exists($controller)) {
			$callback = [new $controller(),$action];
			parent::respond($method, $path, $callback);
		} else {
			try {
				throw new \Exception("Controller class '$controller' not found");
			} catch (\Exception $e) {
				echo '<pre>' . $e . '</pre>';
			}
		}
	}

	public static function currentRoute() {
		return $_SERVER['REQUEST_URI'];
	}
}
