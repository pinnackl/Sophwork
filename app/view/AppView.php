<?php
/**
 *	This file is a part of the sophwork project
 *	@Tested version : Sophwork.0.2.9
 *	@author : Syu93
 *	--
 *	Main view class
 */

namespace sophwork\app\view;

use sophwork\app\app\SophworkApp;

class AppView extends SophworkApp{
	public $theme;
	public $modifiers = [
		'S' => 'htmlspecialchars',
		'U' => 'strtoupper',
		'L' => 'strtolower',
		'FU' => 'ucfirst',
		'FL' => 'lcfirst',
	];
	public $viewData;

	public function __construct() {
		$this->viewData = new \StdClass();
	}

	public function __set($param, $value) {
		$this->$param = $value;
	}
	
	public function __get($param) {
		return $this->$param;
	}

	public function isActive($page, $level = 'p'){
		if(isset($_GET[$level]) && $page == $_GET[$level])
			echo' active';
		elseif(!isset($_GET[$level]) && $page == 'index')
			echo' active';
	}

	public function renderView($template, $path = null){
		if(is_null($template))
			$template = 'index';
		if(!is_null($path)){
			include_once(dirname(dirname(__FILE__)) . '/..' .'/../' . $path . 'template/'.$template.'.tpl');
			return;
		}
		include_once(dirname(dirname(__FILE__)) . '/..' .'/../template/'.$template.'.tpl');
	}

	public function renderThemeView($template, $path = null){
		if(is_null($template))
			$template = 'index';
		if(!is_null($path)){
			include_once(dirname(dirname(__FILE__)) . '/..' .'/../' . $path . 'template/themes/'.$this->theme.'/'.$template.'.tpl');
			return;
		}
		include_once(dirname(dirname(__FILE__)) . '/..' .'/../template/themes/'.$this->theme.'/'.$template.'.tpl');
	}

	/** 
	 *	@param $value : string , object , array 
	 *	@param $item  : null, string , array
	 *	@param $modifier : ref public $modifiers[]
	 */
	public function show($value, $item = null, $modifier = 'S'){
		$method = $this->modifiers[$modifier];
		if(!is_null($item)){
			if(getType($value->$item) == "string"){
				if(isset($value->$item))
				echo $method($value->$item);
				return $method($value->$item);
			}else{
				return $value->$item;
			}	
		}
		if(isset($this->viewData->$value))
			echo $method($this->viewData->$value);
		return $method($this->viewData->$value);
	}

	public function e($value, $modifier = 'S'){
		$method = $this->modifiers[$modifier];
		echo $method($value);
	}

	public function get($value, $item = null, $modifier = 'S'){
		$method = $this->modifiers[$modifier];
		if(!is_null($item)){
			if(getType($value->$item) == "string"){
				if(isset($value->$item))

				return $method($value->$item);
			}else{
				return $value->$item;
			}	
		}
		if(isset($this->viewData->$value))

		return $method($this->viewData->$value);
	}
}