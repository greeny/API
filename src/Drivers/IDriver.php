<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Api\Drivers;

use greeny\Api\Request;

interface IDriver
{
	/**
	 * @param Request $request
	 * @return mixed
	 */
	function send(Request $request);
} 
