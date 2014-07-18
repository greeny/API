<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Api;

use greeny\Api\Drivers\IDriver;

class Request
{
	const METHOD_GET = 'get';
	const METHOD_POST = 'post';
	const METHOD_PUT = 'put';
	const METHOD_DELETE = 'delete';

	/** @var IDriver */
	protected $driver;

	/** @var string */
	protected $url;

	/** @var array */
	protected $parameters = [];

	/** @var array */
	protected $headers = [];

	/** @var string */
	protected $method = self::METHOD_GET;

	public function __construct(IDriver $driver, $url)
	{
		$this->driver = $driver;
		$this->url = $url;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function setUrl($url)
	{
		$this->url = (string) $url;
		return $this;
	}

	public function addParameters($key, $value = NULL)
	{
		$this->add('parameters', $key, $value);
		return $this;
	}

	public function getParameters()
	{
		return $this->parameters;
	}

	public function setParameters(array $parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}

	public function addHeaders($key, $value = NULL)
	{
		$this->add('headers', $key, $value);
		return $this;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
		return $this;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	public function send()
	{
		return $this->driver->send($this);
	}

	protected function add($type, $key, $value)
	{
		if(is_array($key) || $key instanceof \Traversable) {
			foreach((array) $key as $k => $v) {
				$this->add($type, $k, $v);
			}
		} else if(is_scalar($value) || $value = (string) $value) {
			$arr = &$this->$type;
			$arr[(string) $key] = $value;
		} else {
			throw new InvalidArgumentException("Invalid value with key $key.");
		}
		return $this;
	}
}
