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

use Sophwork\modules\handlers\requests\Requests;

// Set up the source path for the autoloader
	// Unix env
// $autoloader->config = '/var/www/Sophwork/src';
	// Windows env
$autoloader->config = __DIR__ . '/../src/';

/*
 *	Create a new applicaion with the Sophwork class
 * 	It will create 3 new class :
 *		- appController class
 *		- appModel class
 *		- appView class
 *
 * 	Set up app parameters here or in the config file
 */
$app = new SophworkApp([
	// 'baseUri' => '',
		// Unix env
	// 'template' => '/var/www/Sophwork/template',
		// Windows env
	// 'template' => __DIR__ . '/../template/',
]);

/**
 * Sophwork provide in its module a simple error handle
 * to display error more readable
 */

// Force the application to display errors
// $app->debug = true;

$app->inject(new ErrorHandler());

/**
 * Simply declare your routes and the patern to match
 * and link them to the controller in your sources
 *
 * You can match the following http requests
 * 		- get
 * 		- post
 * 	You can also attribute them a name so you can use UrlGenerator to create links
 */
$app->get('/', ['MyApp\Controller\Home' => 'show'], 'home');	// Separate controller file (recommended)
$app->get('/hello/{name}', function(SophworkApp $app, requests $request, $name){		// Inline controller
	return "<h1>Hello " . $name . "</h1>";
});
$app->post('/form', ['MyApp\Controller\Home' => 'form']);

/**
 * Declare your own error handler and customise your message to the user
 *
 * NOTE : this only work if your injet the Sophwork errorHandler  in your app !
 */
$app->errors(function (\Exception $e, $errorCode){
	if ($errorCode == 404) {
		return "<h1>Oups ! =(<br>Jim have died !</h1>";
	}
});

$app->before(function ($app, $requests) {

});

$app->after(function ($app, $requests) {

});

$app->run();