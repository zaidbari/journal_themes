<?php namespace App\Controllers;

use App\Lib\Config;
use App\Lib\HTTPRequester;
use App\Lib\Upload;

class ReceiveFiles
{
	public function file( $request ) : string
	{
		if ( $request->param('type') == "pdf" || $request->param('type') == "html" ) {
			$file = new Upload('file', $request->param('type'), Config::env('APP_ABBRV') . '-' . $request->param('id'));
			if ( $file->move() ) return json_encode([ 'success' => true, 'path' => 'files/' . $request->param('type') . '/' . $file->save_name ]);
			else return json_encode([ 'error' => 500, 'msg' => 'Internal Server Error' ]);
		} else return json_encode([ 'error' => 403, 'msg' => 'Invalid File type' ]);
	}

	public function url()
	{
		$post = json_decode(file_get_contents('php://input'));
		$result = HTTPRequester::File($post->url);
		$file = $_SERVER['DOCUMENT_ROOT']
			. '/files/' . $post->type . '/'
			. Config::env('APP_ABBRV') . '-' . $post->id . '.' . $post->type;

		if ( fopen($file, "w") ) {
			if ( file_put_contents($file, $result) ) return json_encode([ 'success' => true ]);
			else return json_encode([ 'success' => false ]);
		}
		else return json_encode([ 'success' => false ]);
	}

	public function dev()
	{
		$post = json_decode(file_get_contents('php://input'));
		return json_encode([
			'success' => $post->success ?? true,
			'url' => $post->url,
			'type' => $post->type,
			'id' => $post->id
		]);
	}
}