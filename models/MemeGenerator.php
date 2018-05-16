
  <?php
class MemeGenerator{
  protected $image;
  protected $topText;
  protected $bottomText;
  protected $fontSize;
 
  public function __construct($url, $topText, $bottomText,$fontSize){
      $this->image= $_SERVER["DOCUMENT_ROOT"]. "/meme_editor/assets/img/upload/". $url;
      $this->topText=$topText;
      $this->bottomText=$bottomText; 
      $this->fontSize=$fontSize;
  }
     public function generateMemeFromJPG()
     { 
     $img=imagecreatefromjpeg($this->image);
     $img=$this->resize($img);

     $textcolor = imagecolorallocate($img, 255,255, 255);
     imagettftext($img,50,0,300,20,$textcolor,"asman.ttf",$this->topText);    
   
    
     // Affichage de l'image
    header('Content-type: image/jpeg');
    imagejpeg($img);
    
    imagedestroy($img);
    }
   public function resize($img)
   {
       if(imagesx($img) > 600)
       {
           return  imagescale($img,600);
       }
       else{
           return $img;
       }
   
       
   }
   
   // fonts
   $font1='font/asman.ttf';
   $font2='font/Lato-Regular.ttf';
   $font3='font/Lato-Bold.ttf';
   $font4='font/Lato-Italic.ttf';
   $font5='font/Lato-Light.ttf';

 
}