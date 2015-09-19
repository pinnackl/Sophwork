<?php
/**
 *	This file is a part of the sophwork project
 *	@Tested version : Sophwork.0.2.9
 *	@author : Syu93
 *	--
 *	Main model class
 */

namespace sophwork\app\model;

use sophwork\app\app\SophworkApp;

class AppModel extends SophworkApp{

    public $config;
	protected $data;
    protected $link;

	public function __construct($config = null){
        if(!is_null($config) && $config != false)
            $this->config = $config;
        else
            $this->config = [
                'db_host' => null,
                'db_name' => null,
                'db_login' => null,
                'db_password' => null,
            ];
        $this->link = $this->connectDatabase();
	}

	public function __get($param){
		return $this->$param;
	}

	public function __set($param, $value){
        $this->$param = $value;
	}

	public function connectDatabase(){
        extract($this->config);
		try{
			$link = new \PDO('mysql:host='.$db_host.';dbname='.$db_name,$db_login,$db_password,
			array(
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
			));
		}
		catch(Exception $e){
			die("Erreur : ".$e->getMessage());
		}
		return $link;
	}
}