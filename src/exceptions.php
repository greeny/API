<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Api;

use Exception as Ex;

class Exception extends Ex {}

class DriverException extends Exception {}

class InvalidArgumentException extends Exception {}
