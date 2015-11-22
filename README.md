# Sophwork
## Simple PHP Oject Framework

 This micro-framework was first created to help people learning Object Oriented Programming through a framework.
 
 It's quite simple to get started and quite alike with a well known micro-framework : [Silex](https://github.com/silexphp/Silex) *(based on Symfony components)*.
 
The aim is not to provide a production ready framework that you should use in your enterprise project but mostly to try things and to bootstrap a small structured project very quickly.
 
# Installation
 
 The recommended way to install Sophwork is through [Composer](https://getcomposer.org/):
 
 _**$ composer require sophwork/sophwork "0.1.1"**_
 
 As you can also download it from : [here](https://github.com/pinnackl/Sophwork/archive/0.1.1.zip)
 
# Getting started
## Server configuration
You first need to configure your server to use Sophwork. You can take exemple on the .htaccess file in the sources folder. *(Make sure Apache2 mod_rewrite is enabled: here is a [quick tutorial](http://stackoverflow.com/a/5758551))*.
```php
#Default htaccess
Options -MultiViews
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```
## Using Sophwork
```php
<?php

use Sophwork\app\app\SophworkApp;
use Sophwork\modules\handlers\requests\Requests;

// If you're not using composer, you must define your sources path
$autoloader->sources = __DIR__ . '/../src/';

/*
 *	Create a new applicaion with the Sophwork class
 * 	It will create 3 new class :
 *		- appController class
 *		- appModel class
 *		- appView class
 *
 * 	Set up app parameters here or in the config file
 */
$app = new SophworkApp();
```

Then you need to define your routes

```php
<?php

/**
 * Simply declare your routes and the pattern to match
 * and link them to the controller
 *
 * You can match the following http requests
 * 		- get
 * 		- post
 * 	You can also attribute them a name so you can use UrlGenerator to create links
 */
$app->get('/', function(SophworkApp $app, Requests $request) {
	return "<h1>Hello World !</h1>";
});
```

As you can see you can declare controller directly through your router, but I mostly recommend to do it in a proper controller file :
```php
<?php
// src/MyApp/Controller/Home.php

namespace MyApp\Controller;

use Sophwork\app\app\SophworkApp;
use Sophwork\modules\handlers\requests\Requests;

class Home
{
	public function show(SophworkApp $app, Requests $requests) {
		return 'Hello World';
	}
}
```
And then call the right method in the controller you've just define :
```php
<?php
// index.php
//...
// Separate controller file (recommended)
$app->get('/', ['MyApp\Controller\Home' => 'show'], 'home');
```
Now you have configured your app, it's about time to actually run it *(It will not run any of your controllers, unless you call this method)*
```php
// Run the actual app
$app->run();
```
# Tests
Sophwork isn't tested yet. *(As I said it's not production ready yet)*.

# License
Sophwork is licensed under the MIT license.
