<?php

namespace sophwork\modules\handlers\requests;

use sophwork\core\Sophwork;

class Requests{

	protected $requestMethod;

	public function __construct(array $headers = [], callable $calback){
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		foreach ($headers as $key => $value) {
			header($value);
		}
		$calback();
	}
}