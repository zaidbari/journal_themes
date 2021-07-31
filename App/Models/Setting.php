<?php


namespace App\Models;

use Core\Model;

class Setting extends Model
{
	public static function all() : array
	{
		$db = static::db();
		$stmt = $db->query('SELECT * FROM settings WHERE id=1');
		return $stmt->fetch();
	}

	public static function update($values) : bool
	{
		$db = static::db();

		$stmt = $db->prepare('
					UPDATE settings SET 
	                    cover_image=:cover_image, 
	                    issn=:issn, 
	                    eissn=:eissn, 
	                    twitter=:twitter, 
	                    analytics=:analytics, 
	                    journal_name=:journal_name, 
	                    journal_abbr=:journal_abbr, 
	                    description=:description, 
	                    header_text=:header_text
                	WHERE id=1
                ');

		$stmt->bindParam('cover_image', $values['cover_image']);
		$stmt->bindParam('issn', $values['issn']);
		$stmt->bindParam('eissn', $values['eissn']);
		$stmt->bindParam('twitter', $values['twitter']);
		$stmt->bindParam('analytics', $values['analytics']);
		$stmt->bindParam('header_text', $values['header_text']);
		$stmt->bindParam('journal_name', $values['journal_name']);
		$stmt->bindParam('description', $values['description']);
		$stmt->bindParam('journal_abbr', $values['journal_abbr']);

		return $stmt->execute();
	}


}