<?php namespace App\Lib;


class Upload
{
	public string $target_file;
	public $temp_name;
	public string $save_name;

	public function __construct( string $string, string $path = "", string $save_name = "" ){

		$computed_path = $_SERVER['DOCUMENT_ROOT'] .'/files/' . $path;
		$temp = explode(".", $_FILES[$string]["name"]);

//		$this->save_name = round(microtime(true)) . '.' . end($temp);
		$this->save_name = $save_name .'.'. end($temp);

		if (!is_dir( $computed_path )) mkdir( $computed_path, 0777, true );

		$this->target_file = $computed_path . '/' . $this->save_name;
		$this->temp_name = $_FILES[$string]["tmp_name"];
	}

	public function move() : bool
	{
		return move_uploaded_file($this->temp_name, $this->target_file);
	}

	public static function check( $file ) : bool
	{
		return !isset($_FILES[$file]) || $_FILES[$file]['error'] == UPLOAD_ERR_NO_FILE? false : true;
	}

	public static function checkType( $file, $type = [] ) : bool
	{
		return !in_array(pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION), $type) ? false : true;
	}
}