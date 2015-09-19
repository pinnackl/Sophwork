<?php
/**
 *	This file is a part of the sophwork project
 *	@Tested version : Sophwork.0.2.9
 *	@author : Syu93
 *	--
 *	Main application class
 */

namespace sophwork\app\app;

use sophwork\core\Sophwork;
use sophwork\app\view\AppView;
use sophwork\app\model\AppModel;
use sophwork\app\controller\AppController;

use sophwork\modules\kdm\SophworkDM;
use sophwork\modules\kdm\SophworkDMEntities;

class SophworkApp extends Sophwork{
	// public $appName;
	public $config;
	public $appView;
	public $appModel;
	public $appController;

	/**
	 *	@param none
	 *	instanciate all sophwork classes :
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
	public function __construct(){
		parent::__construct();
		$this->config = Sophwork::getConfig();
		$this->appView 			 = new AppView();
		// $this->appModel 		 = new AppModel($this->config);
		if(Sophwork::getConfig())
			$this->KDM = new SophworkDM(Sophwork::getConfig());
		if(!($this instanceof AppController))
			$this->appController 	= new AppController($this->appModel);
	}
	
	public function __set($param, $value) {
		$this->$param = $value;
	}

	public function __get($param){
		return $this->$param;
	}

	/**
	 * As we access to the AppvView class using this class
	 * the setViewData is usefull to fill the view class with data to display
	 * @param itemName : is the name reference for retrieving the data
	 * @param values : is the value to associate to the item name | it can be an associative array in which case the third param is used
	 * @param arrayKey (optonal) : is the associative key to get data from values array
	 */
	public function setViewData($itemName, $values, $arrayKey = null){
		if(gettype($values) == 'string'){
			$this->appView
				->viewData->$itemName = $values;
		}
		
		$list = new \StdClass();
		if(gettype($values) == 'array' && !is_null($arrayKey)){
			if(isset($this->appView->viewData->$itemName)){
				$list = $this->appView->viewData->$itemName;
			}
			if(!is_null($values[$arrayKey])){
				foreach ($values[$arrayKey] as $key => $value) {
					$itemObj = new \StdClass();
					$subItemName = $itemName.$key;
					if(isset($this->appView->viewData->$itemName->$subItemName)){
						$itemObj = $this->appView->viewData->$itemName->$subItemName;
					}
					$itemObj->$arrayKey = $value;
					// add
					$list->$subItemName = $itemObj;
				}
				// add
				$this->appView->viewData->$itemName = $list;
			}
			else{
				$this->appView->viewData->$itemName = $list;
			}
		}
	}

	/**
	 * When having to use unformated data and wanted to retrieved it from template
	 * @param itemName : is the name reference for retrieving the data
	 * @param values : is the value to associate to the item name
	 */
	public function setRawData($itemName, $value){
		$this->appView->viewData->$itemName = $value;
	}

	/**
	 * Use to render template of the specified page
	 * @param  name (optional) : template name to render (using index as default. See AppView->renderView)
	 * @param  path (optional) : path to the temple (using template folder by default. See AppView->renderView)
	 */
	public function callView($name = null, $path = null){
		if( !is_null($name) )
			$this->viewName = $name;
		$this->appView
			->renderView($this->viewName, $path);
	}

	/**
	 * Use to render theme template of the specified page
	 * @param  name (optional) : template name to render (using index as default. See AppView->renderThemeView)
	 * @param  path (optional) : path to the temple (using template folder by default. See AppView->renderThemeView)
	 */
	public function callThemeView($name = null, $path = null){
		if( !is_null($name) )
			$this->viewName = $name;
		$this->appView
			->renderThemeView($this->viewName, $path);
	}
}
