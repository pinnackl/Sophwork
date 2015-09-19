<?php

namespace Sophwork\modules\handlers\requests;

use Sophwork\core\Sophwork;

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