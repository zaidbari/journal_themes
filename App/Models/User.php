<?php namespace App\Models;

use \Core\Model;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends Model
{

	/**
	 * Get all the users as an associative array
	 *
	 * @return array
	 */
	public static function all() : array
	{
		$db = static::db();
		$stmt = $db->query('SELECT id, name FROM users');
		return $stmt->fetchAll();
	}

	public static function byEmail($value)
	{
		$db = static::db();
		$stmt = $db->prepare('SELECT id, name, email, password FROM users WHERE email=:value');
		$stmt->bindParam('value', $value);
		$stmt->execute();
		return $stmt->fetch();
	}

	public static function add($values = []): bool
	{
		$db = static::db();
		$password = password_hash($values['password'], PASSWORD_DEFAULT);
		$stmt = $db->prepare('INSERT into USERS (name, email, password) VALUES (:name, :email, :password)');
		$stmt->bindParam('email', $values['email']);
		$stmt->bindParam('name', $values['name']);
		$stmt->bindParam('password', $password);

		return $stmt->execute();
	}
}
