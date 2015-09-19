<?php
/**
 *	This file is a part of the sophwork project
 *	@Tested version : Sophwork.0.2.9
 *	@author : Syu93
 *	--
 *	Sophpkwork module : Template Engine
 *	Loader class
 */

namespace sophwork\modules\kte;

class SophworkTELoader{
	protected $template;

	public function __construct(){ // FIXME : add the loading of multiple template in 1 loader (array)

	}

	public function __get($param){
		return $template;
	}

	public function loadFromArray($template){
		// not used yet
	}

	public function loadFromFile($template){
		if(file_exists($template))
			return $this->template = file_get_contents($template);
	}
}