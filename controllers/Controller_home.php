<?php

class Controller_home extends Controller{

	protected $memeDb;
	const LIMIT_MEME_LIST = 15;// nombre de memes page accueil

	public function __construct(){
		parent::__construct();
		$this->memeDb = new MemeDbManager();
	}
	public function action_layout(){
		$posts = $this->memeDb->listByDate(" LIMIT " . self::LIMIT_MEME_LIST);
		var_dump($posts);
		echo $this->twig->render('home.html', array('posts' => $posts));
	}
}