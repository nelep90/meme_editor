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
	public function action_layout($id=null){
		// random image sauf précisé(après sélection ou upload)
		// getList de image
		$posts = $this->imageDb->listByDate();
		if ($id === null){
			$mainPicture = $this->imageDb->selectRandom();
		} else {
			$mainPicture = $this->imageDb->read($id);
		}
		
		// var_dump($posts);
		// echo '<br>';
		// var_dump($mainPicture);
		echo $this->twig->render('generator.html', array('posts' => $posts, 'mainPicture' => $mainPicture ));
	}
	// upload image client
	public function action_upload(){

	}
	public function action_download(){

	}
	public function action_imageSelected(){

	}


}