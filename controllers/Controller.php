<?php
// require_once ('vendor/autoload.php');
// $loader = new Twig_Loader_Filesystem('views');
// $twig = new Twig_Environment($loader,[
//     'cache' => false,
//  ]);

//echo $twig->render('home.html', array(''));
class Controller {
	protected $loader;
	protected $twig;

	public function __construct(){
		$this->loader = new Twig_Loader_Filesystem('views');
		$this->twig = new Twig_Environment($this->loader,[ 'cache' => false,
	]);
	}
}