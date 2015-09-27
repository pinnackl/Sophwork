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

	protected $route;

	protected $debug;

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
		if (is_null($config))
			$this->config 			= Sophwork::getConfig();
		else
			$this->config 			= $config;

		$this->appView 			 	= new AppView($this->config);
		$this->appModel 		 	= new AppModel($this->config);

		if(!($this instanceof AppController))
			$this->appController 	= new AppController($this->appModel);
		
		if(!($this instanceof AppDispatcher))
			$this->appDispatcher 	= new AppDispatcher($this);

		$this->route 				= [];

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
	public function get($route, $toController) {
		$this->route['GET'][] = [
			'route' => $route,
			'toController' => $toController
		];
	}

	/**
	 * POST route method
	 * @param  [type] $route        [description]
	 * @param  [type] $toController [description]
	 */
	public function post($route, $toController) {
		$this->route['POST'][] = [
			'route' => $route,
			'toController' => $toController
		];
	}

	public function request($route, $toController) {

	}

	/**
	 * Run the Sophwork app
	 *
	 * FIXME : Find a other way if return response
	 */
	public function run(){
		try {
			$matche = $this->appDispatcher->matche();
			if (!is_object($matche))
				echo $matche;
			else
				echo $matche->getResponse();
		} catch (\Exception $e) {
			echo $e->getMessage(), "<br>";
			if ($this->debug){
				echo '<b>DEBUG MODE </b>: TRUE';
				die;
			}
		}
	}
}
