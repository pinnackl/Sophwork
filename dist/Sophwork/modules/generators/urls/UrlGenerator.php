<?php

namespace Sophwork\modules\generators\urls;

use Sophwork\app\app\SophworkApp;

class UrlGenerator
{
	protected $generatedUrl;
	
	private $routes;

	public function __construct(Array $routes = null) {
		$this->routes 		= [];
		if (!is_null($routes))
			$this->routes 	= $routes;

		$this->generatedUrl = "";
		return $this;
	}

	public function generate($route = "/", Array $parameters = [], $rewrited = true) {
		if ($rewrited) {
			foreach ($this->routes as $key => $routes) {
				if (array_key_exists($route, $routes)) {
					if ($routes[$route]['isDynamic']) {
						$this->generatedUrl = $routes[$route]['route'];
						foreach ($parameters as $key => $value) {
							$this->generatedUrl = preg_replace("/{($key)}/", $value, $this->generatedUrl);
						}
						return $this->generatedUrl;
					} else {
						$separator = "?";
						$this->generatedUrl = $routes[$route]['route'];
						foreach ($parameters as $key => $value) {
							$this->generatedUrl .= $separator . $key . "=" . $value;
							$separator = "&";
						}
						return $this->generatedUrl;
					}
				} else {
					$separator = "?";
					$this->generatedUrl = $route;
					foreach ($parameters as $key => $value) {
						$this->generatedUrl .= $separator . $key . "=" . $value;
						$separator = "&";
					}
					return $this->generatedUrl;
				}
			}
		} else {
			$separator = "?";
			$this->generatedUrl = $route;
			foreach ($parameters as $key => $value) {
				$this->generatedUrl .= $separator . $key . "=" . $value;
				$separator = "&";
			}
			return $this->generatedUrl;
		}
	}
}