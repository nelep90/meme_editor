
  <?php
class MemeGenerator{
    protected $image;
    protected $topText;
    protected $bottomText;
    protected $fontSize;

    public function __construct($url, $topText, $bottomText,$fontSize){
        $this->image= $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/upload/". $url;
        $this->topText=$topText;
        $this->bottomText=$bottomText; 
        $this->fontSize=$fontSize;
    }
    public function generateMemeFromJPG()
    { 
        $img=imagecreatefromjpeg($this->image);
        $img=$this->resize($img);
        putenv('GDFONTPATH=' . realpath('.' . '/assets/font/'));
        $textcolor = imagecolorallocate($img, 255, 255, 255);

        if (strlen($this->topText) > 0){
            $bbox = imagettfbbox($this->fontSize, 0, 'Lato-Bold', $this->topText);
            $topTextSize = $bbox[2] - $bbox[0];
            $imageWidth = imagesx($img);

            $xTopText = $imageWidth / 2 - $topTextSize / 2;
            imagettftext($img, $this->fontSize, 0, $xTopText, 50, $textcolor, 'Lato-Bold', $this->topText);

        }  
        return $img;

     
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

 

} 
