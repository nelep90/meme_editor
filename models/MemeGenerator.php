
  <?php
class MemeGenerator{
    protected $image;
    protected $topText;
    protected $bottomText;
    protected $fontSize;
    protected $imageName;
    protected $textColor1;
    protected $textColor2;
    protected $id;

    public function __construct($id, $url, $topText, $bottomText, $fontSize, $textColor1, $textColor2){
        $this->id = $id;
        $this->image = $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/upload/". $url;
        $this->topText = $topText;
        $this->bottomText = $bottomText; 
        $this->fontSize = $fontSize;
        $this->imageName = $url;
        $this->textColor1 = $textColor1;
        $this->textColor2 = $textColor2;
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
        if (strlen($this->bottomText) > 0){
            $bbox = imagettfbbox($this->fontSize, 0, 'Lato-Bold', $this->bottomText);
            $bottomTextSize = $bbox[2] - $bbox[0];
            $bottomTextHeight = $bbox[1] - $bbox[6];
            $imageWidth = imagesx($img);
            $imageHeight = imagesy($img);
            $xBottomText = $imageWidth / 2 - $bottomTextSize / 2;
            $yBottomText = $imageHeight - $bottomTextHeight - 20;
            imagettftext($img, $this->fontSize, 0, $xBottomText, $yBottomText, $textcolor, 'Lato-Bold', $this->bottomText);
        }
        if ((strlen($this->topText) > 0) || (strlen($this->bottomText) > 0)){
            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $this->imageName)){
                imagejpeg($img, $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $this->imageName);
        
                imagedestroy($img);
                return 'meme_' . $this->imageName;
            } else {
                $memeName = $this->findValidName(1);
                imagejpeg($img, $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $memeName);
        
                imagedestroy($img);
                return 'meme_' . $memeName;
            }
             
        }              
    }
    public function generateMemeFromPNG()
    { 
        $img=imagecreatefrompng($this->image);
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
        if (strlen($this->bottomText) > 0){
            $bbox = imagettfbbox($this->fontSize, 0, 'Lato-Bold', $this->bottomText);
            $bottomTextSize = $bbox[2] - $bbox[0];
            $bottomTextHeight = $bbox[1] - $bbox[6];
            $imageWidth = imagesx($img);
            $imageHeight = imagesy($img);
            $xBottomText = $imageWidth / 2 - $bottomTextSize / 2;
            $yBottomText = $imageHeight - $bottomTextHeight - 20;
            imagettftext($img, $this->fontSize, 0, $xBottomText, $yBottomText, $textcolor, 'Lato-Bold', $this->bottomText);
        }
        if ((strlen($this->topText) > 0) || (strlen($this->bottomText) > 0)){
            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $this->imageName)){
                imagepng($img, $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $this->imageName);
        
                imagedestroy($img);
                return 'meme_' . $this->imageName;
            } else {
                $memeName = $this->findValidName(1);
                imagepng($img, $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $memeName);
        
                imagedestroy($img);
                return 'meme_' . $memeName;
            }
             
        }
                     
    }
    // recursive Rules!!!!!
    public function findValidName($number){
        $path = $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/";
        $arrayName = explode('.', $this->imageName);
        $name = $arrayName[0];
        $ext = "." . $arrayName[1];
        if (file_exists($path . "meme_" . $name . $number . $ext)){
            $number += 1;
            return $this->findValidName($number);
        } else {
            return $name . $number . $ext;
        }
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
