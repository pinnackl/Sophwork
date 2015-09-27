<?php
/**
 *	This file is a part of the Sophwork project
 *	@Tested version : Sophwork.0.2.0
 *	@author : Syu93
 *	--
 *	This file if for exemple purpose
 *	It's show you the way to use the Sophwork framework basics
 *	feel free to edit it or recreate your own for your project.
 *	
 *	NOTE : Uncomment the exemples bellow to see it works
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__ . '/../dist/autoloader.php');

use Sophwork\core\Sophwork;
use Sophwork\app\app\SophworkApp;

$autoloader->config = '/var/www/Sophwork/src';

/*
 *	Create a new applicaion with the Sophwork class
 *	It will create 3 new class :
 *		- appController class
 *		- appModel class
 *		- appView class
 */
$app = new SophworkApp([
	'baseUrl' => '/Sophwork/web',
	'template' => '/var/www/Sophwork/template',
]);

$app->get('/', ['MyApp\Controller\Home' => 'show'], 'home');
$app->get('/game/{game}', ['MyApp\Controller\Home' => 'gameShow'], 'gameShow');
$app->get('/game/{game}/edit', ['MyApp\Controller\Home' => 'gameEdit'], 'gameEdit');
$app->get('/game/{category}/{game}', ['MyApp\Controller\Home' => 'gameCategory'], 'gameCategory');
// $app->get('/post', ['MyApp\Controller\Home' => 'blog']);
// $app->get('/about', ['MyApp\Controller\Home' => 'blog']);
// $app->get('/contact', ['MyApp\Controller\Home' => 'contact']);
// $app->post('/form', ['MyApp\Controller\Home' => 'form']);
// $app->get('/blog/{id}', function($app, $id){
// 	echo'<pre>';
// 	var_dump($id);
// 	echo'</pre>';
// });

$app->run();