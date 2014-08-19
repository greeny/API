<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Api\Drivers;

use greeny\Api\DriverException;
use greeny\Api\Request;

class CurlDriver implements IDriver
{

	public function __construct()
	{
		if(!function_exists('curl_init')) {
			throw new DriverException('No cUrl support, sorry.');
		}
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	function send(Request $request)
	{
		if(($method = $request->getMethod()) === $request::METHOD_GET) {
			$url = $request->getUrl();
			$request->setUrl($url . (strpos($url, '?') ? '&' : '?') .
				http_build_query($request->getParameters(), '', '&'));
		}
		$resource = curl_init($request->getUrl());
		curl_setopt($resource, CURLINFO_HEADER_OUT, TRUE);
		curl_setopt($resource, CURLOPT_RETURNTRANSFER, TRUE);
		$headers = [];
		foreach($request->getHeaders() as $key => $value) {
			$headers[] = "$key: $value ";
		}
		curl_setopt($resource, CURLOPT_HTTPHEADER, $headers);
		if($method === $request::METHOD_GET) {
			curl_setopt($resource, CURLOPT_HTTPGET, TRUE);
		} else if($method === $request::METHOD_POST) {
			curl_setopt($resource, CURLOPT_POST, TRUE);
		}
		$return = curl_exec($resource);
		curl_close($resource);
		return $return;
	}
}
