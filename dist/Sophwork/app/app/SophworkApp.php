<?php
/**
 *	This file is a part of the Sophwork project
 *	@version : Sophwork.0.3.0
 *	@author : Syu93
 *	--
 *	Main application class
 */

namespace Sophwork\app\app;

use Sophwork\core\Sophwork;
use Sophwork\app\view\AppView;
use Sophwork\app\model\AppModel;
use Sophwork\app\controller\AppController;
use Sophwork\modules\handlers\dispatchers\AppDispatcher;

class SophworkApp extends Sophwork
{
	public $appName;
	public $config;
	public $appView;
	public $appModel;
	public $appController;

	protected $routes;

	protected $debug;

	protected $errors;

	protected $before;
	protected $after;

	/**
	 *	@param none
	 *	instanciate all Sophwork classes :
	 *		AppView
	 *		AppController
	 *		AppModel
	 *
	 * 	This class is the base of the sophork architecture.
	 * 	All you need to do is instanciate this class in your index file
	 * 	and then, acccess throught it to all the others class
	 *
	 * 	Beacause all others classes and controllers inherite from this class
	 * 	appController is use as a singleton
	 */
	public function __construct($config = null) {
		parent::__construct();
		if (is_null($config)) {
			if (Sophwork::getConfig())
				$this->config 			= Sophwork::getConfig();
			else {
				$this->config 			= [
					"baseUrl"			=> "",
					"template"			=> "",
					];
			}
		}
		else
			$this->config 			= $config;

		$this->appView 			 	= new AppView($this->config);
		$this->appModel 		 	= new AppModel($this->config);

		if(!($this instanceof AppController))
			$this->appController 	= new AppController($this->appModel);
		
		if(!($this instanceof AppDispatcher))
			$this->appDispatcher 	= new AppDispatcher($this);

		$this->routes 				= [];

		$this->debug 				= false;
	}
	
	public function __set($param, $value) {
		$this->$param = $value;
	}

	public function __get($param) {
		return $this->$param;
	}

	/**
	 * GET route method
	 * @param  [type] $route        [description]
	 * @param  [type] $toController [description]
	 */
	public function get($route, $toController, $alias = null) {
		$route = [
			'route' => $route,
			'toController' => $toController,
			'alias' => $alias,
			'isDynamic' => preg_match("/{([^{}?&]+)}/", $route),
		];
		if (!is_null($alias))
			$this->routes['GET'][$alias] = $route;
		else
			$this->routes['GET'][] = $route;
	}

	/**
	 * POST route method
	 * @param  [type] $route        [description]
	 * @param  [type] $toController [description]
	 */
	public function post($route, $toController, $alias = null) {
		$route = [
			'route' => $route,
			'toController' => $toController,
			'alias' => $alias,
			'isDynamic' => preg_match("/{([^{}?&]+)}/", $route),
		];
		$this->routes['POST'][] = $route;
	}

	public function request($route, $toController) {

	}

	public function inject($depenency) {
		$depenencyName = $depenency->init($this);
		$this->$depenencyName = $depenency;
	}

	public function errors($callable = null) {
		$this->errors = $callable;
	}

	public function before($callable = null) {
		if (is_callable($callable))
			$this->before = $callable;
	}

	public function after($callable = null) {
		if (is_callable($callable))
			$this->after = $callable;
	}

	public function abort($errorCode = 500, $message = null) {
		if (class_exists("\Sophwork\\modules\\handlers\\responses\\Responses"))
			return new \Sophwork\modules\handlers\responses\Responses($message, $errorCode);
		else {
			http_response_code($errorCode);		
			return is_null($message) ? '' . $message : $message;
		}
	}

	public function run(){
		// custom hook
		if (!is_null($this->before))
			call_user_func_array($this->before, [$this]);

		// check if the Sophwork error exception handler is used for this application
		if (isset($this->ErrorHandler)) {
			// check if the custom error messages have been set
			// use the default exception messages
			if(!$this->errors) {
				try {
					// matche return the controller response object to set to the user
					// if no match happen the dispatchers send an exception with the appropriate http status code
					$matche = $this->appDispatcher->matche();
					if (!is_object($matche)) {
						if (!is_null($matche))
							echo $matche;
						else {
							http_response_code(500);
							throw new \Exception("<h3>Error !</h3>\"<b>Controller must return something !</b>\"");
						}
					} else
						echo $matche->getResponse();
				} catch (\Sophwork\modules\handlers\errors\exception\SophworkErrorException\ErrorHandler $e) {
					echo $e->getMessage(), "<br>";
					if ($this->debug){
						echo '<b>DEBUG MODE </b>: TRUE';
						exit;
					}
				}
			} else {
				// check if custom exception messages have been set into a callable
				if (is_callable($this->errors)) {
					try {
						// matche return the controller response object to set to the user
						// if no match happen the dispatchers send an exception with the appropriate http status code
						$matche = $this->appDispatcher->matche();
					} catch (\Exception $e) {
						// custom exception messages handling 
						ob_start();
						$response = call_user_func_array($this->errors, [$e, http_response_code()]);
						$directOutput = ob_get_contents();
						ob_clean();

						// check the response of the custom exception messages callable
						if (!is_object($response)) {
							if (!empty($directOutput)) {
								echo $directOutput;
								exit;
							} elseif (!empty($response)) {
								echo $response;
								exit;
							} else {
								http_response_code(500);
								throw new \Exception("<h3>Error !</h3>\"<b>Error handler must return something !</b>\"");
								exit;
							}
						} else {
							echo $response->getResponse();
							exit;
						}
					}
				}
			}
		} else {
			try {
				// matche return the controller response object to set to the user
				// if no match happen the dispatchers send an exception with the appropriate http status code				
				$matche = $this->appDispatcher->matche();
				if (!is_object($matche)) {
					if (!is_null($matche))
						echo $matche;
					else {
						http_response_code(500);
						throw new \Exception("<h3>Error !</h3>\"<b>Controller must return something !</b>\"");
					}
				} else
					echo $matche->getResponse();
			} catch (\Exception $e) {
				http_response_code(500);
				echo $e->getMessage(), "<br>";
				if ($this->debug){
					echo '<b>DEBUG MODE </b>: TRUE';
					exit;
				}
			}
		}

		// custom hook
		if (!is_null($this->after))
			call_user_func_array($this->after, [$this]);
	}
}
