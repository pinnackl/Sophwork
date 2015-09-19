<?php
/**
 *	This file is a part of the sophwork project
 *	@Tested version : Sophwork.0.2.9
 *	@author : Syu93
 *	--
 *	Main controller class
 */

namespace sophwork\app\controller;

use sophwork\core\Sophwork;
use sophwork\app\app\SophworkApp;

class AppController extends SophworkApp{
	protected $page;
	protected $article;
	protected $else;

	public $appModel;

	public function __construct($appModel = null){
		parent::__construct();
		$this->appModel = $appModel;

		$this->page 	= Sophwork::getParam('p','index');
		$this->article 	= Sophwork::getParam('a',false);
		$this->else 	= Sophwork::getParam('e',false);		
	}

	public function __get($param){
		if(isset($this->$param))
			return $this->$param;
		return false;
	}

	public function __set($param, $value){
		$this->$param = $value;
	}
}