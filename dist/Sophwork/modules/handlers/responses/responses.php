<?php

namespace Sophwork\modules\handlers\responses;

use Sophwork\core\Sophwork;

class Responses{
	protected $response;
	
	public function __construct($response = null) {
		$this->response = $response;
	}

	public function getResponse() {
		return $this->response;
	}
}