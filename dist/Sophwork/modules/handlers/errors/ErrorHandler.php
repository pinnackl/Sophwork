<?php

namespace Sophwork\modules\handlers\errors;

class ErrorHandler
{
	public function __construct() {
		$this->init();
	}

	public function init() {
		set_error_handler([$this, "exception_error_handler"]);
	}

	public function exception_error_handler($severity, $message, $file, $line) {
	    if (!(error_reporting() & $severity)) {
	        // Ce code d'erreur n'est pas inclu dans error_reporting
	        return;
	    }
	    throw new \ErrorException($message, 0, $severity, $file, $line);
	}
}