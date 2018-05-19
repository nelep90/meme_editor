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
	const MIN_IMG_SIZE = 30720; // image 30ko min
	const MAX_IMG_SIZE = 3145728; // image 3 mega maxi;

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

		$this->image = new Bulletproof($_FILES);

		if($this->image["uploadImage"]){
			// Pass a custom name, or leave it if you want it to be auto-generated
			// $image->setName($name); 

			// define the min/max image upload size (size in bytes) 
			$this->image->setSize(self::MIN_IMG_SIZE, self::MAX_IMG_SIZE); 

			// define allowed mime types to upload
			$this->image->setMime(array('jpeg', 'png'));  

			// set the max width/height limit of images to upload (limit in pixels)
			$this->image->setDimension(2000, 2000); 

			// pass name (and optional chmod) to create folder for storage
			$this->image->setLocation(realpath('.') . '/assets/img/upload');
			
		    $upload = $this->image->upload(); 
			
		    if($upload){
		    	
		    	// echo $this->image->getName() . "." . $this->image->getMime();
		        $idImage = $this->imageDb->create($this->image->getName() . "." . $this->image->getMime());
		        $this->action_layout($idImage);
		    }else{
		        echo $this->image["error"]; 
		    }
		}
	}
	
	


}