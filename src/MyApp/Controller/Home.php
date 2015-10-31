<?php

namespace MyApp\Controller;

use Sophwork\app\app\SophworkApp;
use Sophwork\app\view\AppView;
use Sophwork\modules\handlers\requests\Requests;
use Sophwork\modules\handlers\responses\Responses;
use Sophwork\modules\generators\urls\UrlGenerator;

class Home
{
	public function show(SophworkApp $app, Requests $requests) 
	{
		$view = $app->appView;
		$generators = new UrlGenerator();
		return '<h1>Hello World !</h1>';
		// return ['<h1>Hello World !</h1>'];
		// or
		// return $view->renderView('home');
		// or
		// return Responses('Error page not found', 404);
		// End up this application if something go wrong
		// return $app->abort();
	}

	public function hello(SophworkApp $app, Requests $requests, $name) 
	{
		// $view = $app->appView;
		// $generators = new UrlGenerator();
		// $responses = new Responses('Error page not found', 404);
		// return $app->abort();
		// return $view->renderView('home');
		return '<h1>Hello ' . $name . '</h1>';
	}

	public function blog(SophworkApp $app, Requests $requests, $id) 
	{
		return new Responses("Blog ID is : <b>" . $id . "</b>");
	}

	public function contact(SophworkApp $app) 
	{
		$view = $app->appView;

		return $view->renderView('contact', [
			'baseUrl' => $app->config['baseUrl'],
		]);
	}

	public function gameCategory (SophworkApp $app, Requests $requests, $category, $game)
	{
		echo'<pre>';
		var_dump('<b>category</b>');
		var_dump($category);
		var_dump('<b>game</b>');
		var_dump($game);
		echo'</pre>';
	}

	public function gameShow(SophworkApp $app, Requests $requests, $game) 
	{
		$baseUrl = $app->config['baseUrl'];
		$view = $app->appView;
		$app->inject(new UrlGenerator());
		$editUrl = $app->UrlGenerator->generate('gameEdit', ['game'=>$game]);
		return $view->renderView('gameShow', [
			'game' => $game,
			'editUrl' => $baseUrl.$editUrl,
		]);
	}

	public function gameEdit(SophworkApp $app, Requests $requests, $game) 
	{
		$baseUrl = $app->config['baseUrl'];
		$view = $app->appView;
		$app->inject(new UrlGenerator());
		$cancel = $app->UrlGenerator->generate('gameShow', ['game'=>$game]);
		$formAction = $app->UrlGenerator->generate('/form');
		return $view->renderView('gameEdit', [
			'game' => $game,
			'cancel' => $baseUrl.$cancel,
			'formAction' => $baseUrl.$formAction,
		]);
	}

	public function form(SophworkApp $app, Requests $requests) 
	{
		return new Responses("Your message :<br><b>" . $requests->get('gameId') . "</b>");
	}
}