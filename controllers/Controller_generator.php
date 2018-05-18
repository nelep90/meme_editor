<?php

// require_once ('../vendor/autoload.php');
// $loader = new Twig_Loader_Filesystem('../views');
// $twig = new Twig_Environment($loader,[
//     'cache' => false,
//  ]);

// echo $twig->render('generator.html', array(''));

class Controller_generator extends Controller{

	protected $imageDb;
	protected $image;

	public function __construct(){
		parent::__construct();
		$this->imageDb = new ImageDbManager();
		
	}	
	public function action_layout(){
		// random image sauf précisé(après sélection ou upload)
		// getList de image
		$posts = $this->imageDb->listByDate();
		$mainPicture = $this->imageDb->selectRandom();
		var_dump($posts);
		echo '<br>';
		var_dump($mainPicture);
		echo $this->twig->render('generator.html', array(''));
	}
	// upload image client
	public function action_upload(){

	}
	public function action_download(){

	}
	public function action_imageSelected(){

	}


}