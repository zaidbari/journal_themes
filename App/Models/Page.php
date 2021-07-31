<?php


namespace App\Models;


use Core\Model;

class Page extends Model
{
	public static function all() : array
	{
		$db = static::db();
		$stmt = $db->query('SELECT * FROM pages');
		return $stmt->fetchAll();
	}

	public static function bySlug($value)
	{
		$db = static::db();
		$stmt = $db->prepare('SELECT * FROM pages WHERE slug=:value');
		$stmt->bindParam('slug', $value);
		$stmt->execute();
		return $stmt->fetch();
	}

	public static function byId($value)
	{
		$db = static::db();
		$stmt = $db->prepare('SELECT * FROM pages WHERE id=:id');
		$stmt->bindParam('id', $value);
		$stmt->execute();
		return $stmt->fetch();
	}

	public static function slug( $string ) : string
	{
		return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', trim($string))));
	}

	public static function remove( $id ) : bool
	{
		$db = static::db();
		$stmt = $db->prepare('DELETE FROM pages WHERE id=:id');
		$stmt->bindParam('id',$id);

		return $stmt->execute();
	}

	public static function add($values = []): bool
	{
		$db = static::db();
		$stmt = $db->prepare('INSERT INTO pages (title, slug, content, position, type) VALUES (:title, :slug, :content, :position, :type)');

		$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $values['title'])));

		$content = htmlspecialchars(trim($values['content']));
		$title = htmlspecialchars(trim($values['title']));

		$stmt->bindParam('title',$title);
		$stmt->bindParam('slug',$slug);
		$stmt->bindParam('content', $content);
		$stmt->bindParam('position', $values['position']);
		$stmt->bindParam('type', $values['type']);

		return $stmt->execute();
	}

	public static function update($id , $values) : bool
	{
		$db = static::db();
		$stmt = $db->prepare('UPDATE pages SET title=:title, content=:content, slug=:slug, position=:position, type=:type WHERE id=:id');

		$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $values['title'])));
		$content = htmlspecialchars(trim($values['content']));
		$title = htmlspecialchars(trim($values['title']));

		$stmt->bindParam('id',$id);
		$stmt->bindParam('title',$title);
		$stmt->bindParam('slug',$slug);
		$stmt->bindParam('content', $content);

		$stmt->bindParam('position', $values['position']);
		$stmt->bindParam('type', $values['type']);

		return $stmt->execute();
	}
}