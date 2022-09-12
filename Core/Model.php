<?php

namespace Core;

use PDO;
use App\Lib\Config;
use PDOException;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{

	/**
	 * Get the PDO database connection
	 *
	 * @return PDO|null
	 */
	protected static function db() : ?PDO
	{
		static $database = null;
		if ($database === null) {
			$dsn = 'mysql:host=' . Config::env('DB_HOST') . ';dbname=' . Config::env('DB_NAME') . ';charset=utf8';
			try {
				$database = new PDO($dsn, Config::env('DB_USER'), Config::env('DB_PASSWORD') , [
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				]);
			} catch (PDOException $e) {
				Logger::crit("FAILED TO CONNECT TO DATABASE USING CONFIG",
					[
					"Error" =>$e->getMessage(),
					"CONFIG"=>[
						"Host" => Config::env('DB_HOST'),
						"Database" => Config::env('DB_NAME'),
						"Username" => Config::env('DB_USER'),
						"Password" => Config::env('DB_PASSWORD')
					]
				]);
				die("Connection failed! check the logs.");
			}
		}

		return $database;
	}
}
