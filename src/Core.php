<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Api;

use greeny\Api\Drivers\IDriver;

class Core
{
	/** @var IDriver */
	protected $driver;

	/** @var string */
	protected $basePath;

	/** @var array */
	protected $defaultQuery = [];

	/** @var array */
	protected $defaultParameters = [];

	/** @var array */
	protected $defaultHeaders = [];

	public function __construct(IDriver $driver)
	{
		$this->driver = $driver;
	}

	public function getBasePath()
	{
		return $this->basePath;
	}

	public function setBasePath($basePath)
	{
		$this->basePath = $basePath;
		return $this;
	}

	public function getDefaultParameters()
	{
		return $this->defaultParameters;
	}

	public function setDefaultParameters(array $parameters)
	{
		$this->defaultParameters = $parameters;
		return $this;
	}

	public function getDefaultHeaders()
	{
		return $this->defaultHeaders;
	}

	public function setDefaultHeaders(array $defaultHeaders)
	{
		$this->defaultHeaders = $defaultHeaders;
		return $this;
	}

	public function createRequest($url)
	{
		return $this->createUrlRequest($this->basePath . $url);
	}

	public function createUrlRequest($url)
	{
		$request = new Request($this->driver, $url);
		$request->addParameters($this->defaultParameters)
			->addHeaders($this->defaultHeaders);
		return $request;
	}

	public function createEmptyRequest($url)
	{
		return new Request($this->driver, $url);
	}
}
