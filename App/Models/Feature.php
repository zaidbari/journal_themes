<?php


namespace App\Models;


class Feature extends \Core\Model
{
	public static function all() : array
	{
		$db = static::db();
		$stmt = $db->query('SELECT * FROM featured_article WHERE id=1');
		return $stmt->fetch();
	}

	public static function update($values) : bool
	{
		$db = static::db();

		$stmt = $db->prepare('
					UPDATE featured_article SET 
	                    image=:image, 
	                    title=:title, 
	                    content=:content, 
	                    url=:url
                	WHERE id=1
                ');


		$stmt->bindParam('image', $values['image']);
		$stmt->bindParam('title', $values['title']);
		$stmt->bindParam('content', $values['content']);
		$stmt->bindParam('url', $values['url']);

		return $stmt->execute();
	}

}