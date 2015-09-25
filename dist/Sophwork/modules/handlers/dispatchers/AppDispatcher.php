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
			$controllersAndArgs = $this->dispatch($value['route'], $value['toController']);
			if (isset($controllersAndArgs['controller']) && is_callable($controllersAndArgs['controller'])){
				$controllers = preg_split("/::/", $controllersAndArgs['controller']);
				$controler = new $controllers[0];
				return call_user_func_array([$controler, $controllers[1]], $controllersAndArgs['args']);
			} else if (isset($controllersAndArgs['controllerClosure']) && is_callable($controllersAndArgs['controllerClosure'])){
				return call_user_func_array($controllersAndArgs['controllerClosure'], $controllersAndArgs['args']);
			}
		}
		throw new \Exception("<h1>Error ! No route found </h1>");
	}

	/**
	 * Dispatch the route to the right controller
	 * @param  String $routes       String to match in URI
	 * @param  String $toController Controller to use when mattch
	 * @return String/Object        Class controller to use when match case
	 */
	protected function dispatch ($routes, $toController) {
		/**
		 * $routes - Routes from the list of declared routes
		 * $route  - Actual route from the URI
		 */
		$route = $this->resolve();
		
		preg_match_all("/{([^{}]+)}/", $routes, $matches);

		if (empty($matches[0])) {
			if (is_callable($toController)){
				if ($route === $routes) {
					return [
						'controllerClosure' => $toController,
						'args' => [$this->app],
					];
				} else {
					return null;
				}
			} else if (is_array($toController)) {
				if ($route === $routes) {
					$controller = array_keys($toController);
					$action 	= array_values($toController);

					return [
						'controller' => sprintf("%s::%s", $controller[0],$action[0]),
						'args' => [$this->app],
					];
				} else {
					return null;
				}
			}

		} else {
			$routes = str_replace("/", "\/", $routes);
			$routes = preg_replace("/{([^{}]+)}/", "([^\/]+)", $routes);

			if (is_callable($toController)){
				if (preg_match_all("#$routes#", $route, $matchRoute)) {
					array_shift($matchRoute);
					
					$args = [$this->app];
					foreach ($matchRoute as $key => $value) {
						$args[] = $value[0];
					}

					return [
						'controllerClosure' => $toController,
						'args' => $args,
					];
				} else {
					return null;
				}
			} else if (is_array($toController)) {
				if (preg_match_all("#^$routes$#", $route, $matchRoute)) {
					array_shift($matchRoute);

					$controller = array_keys($toController);
					$action 	= array_values($toController);

					$args = [$this->app];
					foreach ($matchRoute as $key => $value) {
						$args[] = $value[0];
					}

					return [
						'controller' => sprintf("%s::%s", $controller[0],$action[0]),
						'args' => $args,
					];
				} else {
					return null;
				}
			}
		}
	}

	protected function resolve () {
		$baseURL = $this->app->config['baseUrl'];

		preg_match("#".$baseURL."(.*)#", $_SERVER['REQUEST_URI'], $matches);
		return isset($matches[1])? $matches[1] : false;
	}
}