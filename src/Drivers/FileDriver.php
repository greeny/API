<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Api\Drivers;

use greeny\Api\DriverException;
use greeny\Api\Request;

class FileDriver implements IDriver
{
	/** @var array */
	protected $context = [
		'http' => [
			'ignore_errors' => TRUE,
			'header' => []
		],
	];

	public function __construct($defaultContext = [])
	{
		$this->context = array_merge($this->context, $defaultContext);
	}

	/**
	 * @param Request $request
	 * @throws DriverException
	 * @return mixed
	 */
	public function send(Request $request)
	{
		$context = $this->createContext($request);
		$response = file_get_contents($request->getUrl(), FALSE, $context);
		if($response !== FALSE) {
			return $response;
		} else {
			throw new DriverException("Cannot complete {$request->getMethod()} request: {$request->getUrl()}");
		}
	}

	protected function createContext(Request $request)
	{
		$context = $this->context;
		$context['http']['method'] = $request->getMethod();
		$headers = $request->getHeaders();
		foreach($headers as $key => $value) {
			$context['http']['header'][] = "$key: $value";
		}
		if(count($request->getParameters())) {
			if($request->getMethod() !== Request::METHOD_GET) {
				$context['http']['header'][] = 'Content-type: application/x-www-form-urlencoded';
				$context['http']['context'] = http_build_query($request->getParameters(), '', '&');
			} else {
				$url = $request->getUrl();
				$request->setUrl($url . (strpos($url, '?') ? '&' : '?') .
					http_build_query($request->getParameters(), '', '&'));
			}
		}
		return stream_context_create($context);
	}
}
