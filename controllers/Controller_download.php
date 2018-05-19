<?php
// echo $twig->render('validation.html', array(''));

class Controller_download extends Controller{

	protected $memeData;
	protected $memeDb;

	public function __construct($memeData = null){
		parent::__construct();
		$this->memeData = $memeData;
		$this->memeDb = new MemeDbManager();
	}
	// @param array $memeData: topText, bottomText, textColor, textSize, url, id 
	public function genereAndDownload(){
		// constructeur attend : $id, $url, $topText, $bottomText, $fontSize, $textColor1, $textColor2
		$memeData = $this->memeData;
		$memeGen = new MemeGenerator(	$memeData['id'],
										$memeData['url'],
										$memeData['topText'],
										$memeData['bottomText'],
										$memeData['textSize'],
										$memeData['textColor1'],
										$memeData['textColor2']);

		$idImage = $memeData['id'];
		$testJpg = "/^(.*)(\.jpg|\.jpeg)$/";
		$testPng = "/^(.*)(\.png)$/";
		if (preg_match($testJpg, $memeData['url'])){
			$newMemeName = $memeGen->generateMemeFromJPG();
		} elseif (preg_match($testPng, $memeData['url'])){
			$newMemeName = $memeGen->generateMemeFromPNG();
		}
		$idMeme = $this->memeDb->create($newMemeName);
		$linkToImage = new MemeByImageManager();
		$linkToImage->insert($idImage, $idMeme);
		$this->action_render($idMeme);

	}
	public function action_render($idMeme){
		
		$memeToLayout = $this->memeDb->read($idMeme);
	
		echo $this->twig->render('validation.html', array('meme' => $memeToLayout));

	}
	public function action_download($memeName){
        
		// header("Content-Type: application/force-download");
		// header("Content-Type: image/jpeg");
		// header("Content-Type: application/download");
		// header("Content-Disposition: attachment; filename=" . $memeName);
		// header("Pragma: public");
		// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		switch(strrchr(basename($memeName), ".")) {
			case ".png": 
			$type = "image/png"; 
			break;
			case ".gif": 
			$type = "image/gif"; 
			break;
			case ".jpg": 
			$type = "image/jpeg"; 
			break;
			default: 
			$type = "application/octet-stream"; 
			break;
}
	
	// echo ;
		$url_image = realpath('./')."/assets/img/meme/" . $memeName;

		header('Content-Description: File Transfer'); 
		header("Content-Disposition: attachment; filename=" . basename($memeName)); 
		header("Content-Type: application/force-download"); 
		header("Content-Transfer-Encoding: " . $type . "\n");
		header("Content-Length: " . filesize(realpath('./')."/assets/img/meme/" . $memeName)); 
		header("Pragma: no-cache"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public"); 
		header("Expires: 0"); 


		readfile($url_image); 
	}
}

// switch(strrchr(basename($fichier), ".")) {
// case ".png": $type = "image/png"; break;
// case ".gif": $type = "image/gif"; break;
// case ".jpg": $type = "image/jpeg"; break;
// default: $type = "application/octet-stream"; break;
// }
 
// header("Content-disposition: attachment; filename=$fichier"); 
// header("Content-Type: application/force-download"); 
// header("Content-Transfer-Encoding: $type\n");
// header("Content-Length: ".filesize($chemin . $fichier)); 
// header("Pragma: no-cache"); 
// header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public"); 
// header("Expires: 0"); 
// readfile($chemin . $fichier); 






