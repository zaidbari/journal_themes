<?php

namespace Core;

use Exception;

abstract class Controller
{

	/**
	 * Magic method called when a non-existent or inaccessible method is
	 * called on an object of this class. Used to execute before and after
	 * filter methods on action methods. Action methods need to be named
	 * with an "Action" suffix, e.g. indexAction, showAction etc.
	 *
	 * @param string $name Method name
	 * @param array  $args Arguments passed to the method
	 *
	 * @return void
	 * @throws Exception
	 */
	public function __call( string $name, array $args )
	{
		$method = explode("Action",$name)[0];

		if (method_exists($this, $method)) {
			if ($this->before() !== false) {
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
		} else {
			throw new Exception("Method $method not found in controller " . get_class($this));
		}
	}

	/**
	 * Before filter - called before an action method.
	 *
	 * @return void
	 */
	protected function before() {
		if(isset($_SESSION['USER'])) {
			if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
				// last request was more than 30 minutes ago
				session_unset();     // unset $_SESSION variable for the run-time
				session_destroy();   // destroy session data in storage
				header('Location: /login');
			} else {
				$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
				session_regenerate_id();
			}
		} else {
			header('Location: /login');
		}
	}

	/**
	 * After filter - called after an action method.
	 *
	 * @return void
	 */
	protected function after() { }
}
