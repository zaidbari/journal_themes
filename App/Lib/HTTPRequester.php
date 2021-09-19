<?php


namespace App\Lib;


class HTTPRequester
{
	/**
	 * @description Make HTTP-GET call
	 *
	 * @param           $url
	 * @param null      $cache
	 * @param array     $params
	 * @param int|array $headers
	 * @param null      $expires
	 *
	 * @return array HTTP-Response body or an empty string if the request fails or is empty
	 */
	public static function HTTPGet( $url, $cache  = NULL, array $params = [], $headers = [],  $expires = NULL ) : array
	{


		$path = $_SERVER['DOCUMENT_ROOT'] . '/api_cache/';

		$cache_file = $cache ? $path . $cache : $path . 'api-cache.json';
		if( !$expires ) $expires = time() - 4 * 60 * 60;

		if( !file_exists($cache_file) ) fopen( $cache_file, "w");

		// Check that the file is older than the expiry time and that it's not empty
		if ( filectime($cache_file) < $expires || file_get_contents( $cache_file)  == '' ) {

			// File is too old, refresh cache

			$query = http_build_query($params);
			$ch = curl_init($url . '?' . $query);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$response = curl_exec($ch);
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($response, 0, $header_size);
			$body = substr($response, $header_size);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);


			$api_results = ["header" => $header, "body" => json_decode($body), "code" => $code];
			$json_results = json_encode($api_results);

			// Remove cache file on error to avoid writing wrong xml
			if ( $api_results && $json_results )
				file_put_contents($cache_file, $json_results);
			else
				unlink( $cache_file);
		} else {
			$json_results = file_get_contents($cache_file);
		}

		return (array) json_decode($json_results);



//		return ["header" => $header, "body" => json_decode($body), "code" => $code];
	}

	public static function File( $url )
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);

		curl_close($ch);
		return $response;
	}

	/**
	 * @description Make HTTP-POST call
	 *
	 * @param       $url
	 * @param array $params
	 *
	 * @return array|false|string HTTP-Response body or an empty string if the request fails or is empty
	 */
	public static function HTTPPost( $url, array $params )
	{
		$query = http_build_query($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		return json_encode(["header" => $header, "body" => $body, "code" => $code]);
	}
}