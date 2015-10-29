<?php
/**
 *	This file is a part of the Sophwork project
 *	@version : Sophwork.0.2.0
 *	@author : Syu93
 *	--
 *	This file if for exemple purpose
 *	It's show you the way to use the Sophwork framework basics
 *	feel free to edit it or recreate your own for your project.
 *	
 *	NOTE : Uncomment the exemples bellow to see it works
 */
require_once(__DIR__ . '/../dist/autoloader.php');

use Sophwork\core\Sophwork;
use Sophwork\app\app\SophworkApp;
use Sophwork\modules\handlers\errors\errorHandler\ErrorHandler;

// Set up the source path for the autoloader
	// Unix env
// $autoloader->config = '/var/www/Sophwork/src';
	// Windows env
$autoloader->config = __DIR__ . '/../src/';

/*
 *	Create a new applicaion with the Sophwork class
 *	It will create 3 new class :
 *		- appController class
 *		- appModel class
 *		- appView class
 */
$app = new SophworkApp([
	// 'baseUri' => '', //	base uri
		// Unix env
	// 'template' => '/var/www/Sophwork/template',
		// Windows env
	// 'template' => __DIR__ . '/../template/',
]);

// $app->debug = true;
// $app->inject(new ErrorHandler());

$app->get('/', ['MyApp\Controller\Home' => 'show'], 'home');
$app->get('/hello/{name}', ['MyApp\Controller\Home' => 'hello'], 'hello');
// $app->get('/game/{game}', ['MyApp\Controller\Home' => 'gameShow'], 'gameShow');
// $app->get('/game/{game}/edit', ['MyApp\Controller\Home' => 'gameEdit'], 'gameEdit');
// $app->get('/game/{category}/{game}', ['MyApp\Controller\Home' => 'gameCategory'], 'gameCategory');
// $app->post('/form', ['MyApp\Controller\Home' => 'form']);
// $app->get('/blog/{id}', function($app, $request, $id){
	// echo'<pre>';
	// var_dump($id);
	// echo'</pre>';
	// return '';
// });

// $app->errors(function (\Exception $e, $errorCode){
// });

// $app->after(function ($app) {
// 	echo'<pre style="background:#ffffff">';
// 	var_dump($app);
// 	echo'</pre>';
// 	die;
// });

$app->run();