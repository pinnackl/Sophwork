<?php

namespace MyApp\Controller;

use Sophwork\app\app\SophworkApp;
use Sophwork\app\view\AppView;
use Sophwork\modules\handlers\requests\Requests;
use Sophwork\modules\handlers\responses\Responses;
use Sophwork\modules\generators\urls\UrlGenerator;

class Home
{
	public function show(SophworkApp $app, Requests $requests) {
		$view = $app->appView;
		$generators = new UrlGenerator();
		// $responses = new Responses('Error page not found', 404);
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
		$editUrl = $app->UrlGenerator->generate('gameEdit', ['game'=>$game]);
		return $view->renderView('gameShow', [
			'game' => $game,
			'editUrl' => $baseUrl.$editUrl,
		]);
	}

	public function gameEdit(SophworkApp $app, $game) {
		$baseUrl = $app->config['baseUrl'];
		$view = $app->appView;
		$app->inject(new UrlGenerator());
		$cancel = $app->UrlGenerator->generate('gameShow', ['game'=>$game]);
		return $view->renderView('gameEdit', [
			'game' => $game,
			'cancel' => $baseUrl.$cancel,
		]);
	}

	public function form(SophworkApp $app) {		
		return new Responses("Your message :<br><b>" . $_POST['message'] . "</b>");
	}
}