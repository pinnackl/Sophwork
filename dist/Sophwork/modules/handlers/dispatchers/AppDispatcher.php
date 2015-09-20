<?php
/**
 *	This file is a part of the Sophwork project
 *	@version : Sophwork.0.3.0
 *	@author : Syu93
 *	--
 *	Main dispatcher class
 */

namespace Sophwork\modules\handlers\dispatchers;

use Sophwork\core\Sophwork;
use Sophwork\app\app\SophworkApp;

class AppDispatcher
{
	protected $config;

	public function __construct(SophworkApp $app) {
		$this->app 		= $app;
	}

	public function matche() {
		if(!isset($_SERVER['REQUEST_METHOD']))
			return null;

		foreach ($this->app->route[$_SERVER['REQUEST_METHOD']] as $key => $value) {
			$controller = $this->dispatch($value['route'], $value['toController']);
			if(is_callable($controller))
				return call_user_func_array($controller, [$this->app]);
		}
		throw new \Exception("<h1>Error ! No route found </h1>");
	}

	protected function dispatch ($routes, $toController) {
		$route = $this->resolve($baseURL);
		if (is_callable($toController)){
			if ($route === $routes) {
				return $toController;
			} else {
				return null;
			}
		} else if (is_array($toController)) {
			if ($route === $routes) {
				$controller = array_keys($toController);
				$action 	= array_values($toController);

				return sprintf("%s::%s", $controller[0],$action[0]);
			} else {
				return null;
			}
		}
	}

	protected function resolve () {
		$baseURL = $this->app->config['baseUrl'];

		preg_match("#".$baseURL."(.*)#", $_SERVER['REQUEST_URI'], $matches);
		return isset($matches[1])? $matches[1] : false;
	}
}