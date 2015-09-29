<?php

namespace MyApp\Controller;

use Sophwork\app\app\SophworkApp;
use Sophwork\app\view\AppView;
use Sophwork\modules\handlers\responses\Responses;
use Sophwork\modules\generators\urls\UrlGenerator;

class Home
{
	public function show(SophworkApp $app) {
		$view = $app->appView;		
		$generators = new UrlGenerator();
		return $view->renderView('home');
	}

	public function blog(SophworkApp $app, $id) {
		return new Responses("Blog ID is : <b>" . $id . "</b>");
	}

	public function contact(SophworkApp $app) {
		$view = $app->appView;

		return $view->renderView('contact', [
			'baseUrl' => $app->config['baseUrl'],
		]);
	}

	public function gameCategory (SophworkApp $app, $category, $game){
		echo'<pre>';
		var_dump('<b>category</b>');
		var_dump($category);
		var_dump('<b>game</b>');
		var_dump($game);
		echo'</pre>';
	}

	public function gameShow(SophworkApp $app, $game) {
		$baseUrl = $app->config['baseUrl'];
		$view = $app->appView;
		$app->inject(new UrlGenerator());
		// echo $generator = (new UrlGenerator($app->routes))->generate('gameShow', ['game'=>'zelda']);
		// echo $generator = (new UrlGenerator($app->routes))->generate('gameCategory', ['category'=>'nintendo' ,'game'=>'zelda']);
		$editUrl = $app->UrlGenerator->generate('gameEdit', ['game'=>'zelda']);
		return $view->renderView('gameShow', [
			'game' => $game,
			'editUrl' => $baseUrl.$editUrl,
		]);
	}

	public function gameEdit(SophworkApp $app, $game) {
		$view = $app->appView;

		return $view->renderView('gameEdit', [
			'game' => $game,
		]);
	}

	public function form(SophworkApp $app) {		
		return new Responses("Your message :<br><b>" . $_POST['message'] . "</b>");
	}
}