
  <?php
class MemeGenerator{
    protected $image;
    protected $topText;
    protected $bottomText;
    protected $fontSize;
    protected $fontSize2;
    protected $imageName;
    protected $textColor1;
    protected $textColor2;
    protected $id;
    protected $fontTypes;// tableau des fonts disponibles
    protected $fontType;// font sélectionnée
    const SMALL_FONT = 10;
    const MEDIUM_FONT = 30;// taille police en pt
    const LARGE_FONT = 50;
    const TEXT_MARGIN_TOP = 20;
    const TEXT_MARGIN_RIGHT = 20;// Standardisation des marges
    const TEXT_MARGIN_BOTTOM = 20;
    const TEXT_MARGIN_LEFT = 20;

    public function __construct($id, $url, $topText, $bottomText, $fontSize, $fontSize2, $textColor1, $textColor2){
        $this->id = $id;
        $this->image = $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/upload/". $url;
        $this->topText = strtoupper($topText);
        $this->bottomText = strtoupper($bottomText);
        $this->fontSize = $this->normalizeFontSize($fontSize);
        $this->fontSize2 = $this->normalizeFontSize($fontSize2);
        $this->imageName = $url;
        $this->textColor1 = $this->toRGB($textColor1);
        $this->textColor2 = $this->toRGB($textColor2);
        $this->fontTypes = array('font1' => 'Lato-Bold');
        $this->fontType = 'font1';
    }
    public function generateMemeFromJPG()
    { 
        $img=imagecreatefromjpeg($this->image);
        $img=$this->resize($img);
        putenv('GDFONTPATH=' . realpath('.' . '/assets/font/'));
        
        // traitement topText
        if (strlen($this->topText) > 0){
            // couleur texte top
            $textcolor = imagecolorallocate($img, $this->textColor1[0], $this->textColor1[1], $this->textColor1[2]);
            // on met le texte dans un rectangle virtuel pour prendre des mesures
            $bbox = imagettfbbox($this->fontSize, 0, $this->fontTypes[$this->fontType], $this->topText);
            // largeur texte
            $topTextSize = $bbox[2] - $bbox[0];
            // hauteur texte
            $topTextHeight = $bbox[1] - $bbox[7];
            // largeur image
            $imageWidth = imagesx($img);
            // calcul position x pour texte centré
            $xTopText = $imageWidth / 2 - $topTextSize / 2;
            // calcul position y pour point en bas à gauche du texte + marge Top
            $yTopText = $topTextHeight + self::TEXT_MARGIN_TOP;
            // insertion du text dans l'image
            imagettftext($img, $this->fontSize, 0, $xTopText, $yTopText, $textcolor, $this->fontTypes[$this->fontType], $this->topText);
        }
        // traitement bottomText
        if (strlen($this->bottomText) > 0){
            $bbox = imagettfbbox($this->fontSize2, 0, $this->fontTypes[$this->fontType], $this->bottomText);
            $bottomTextcolor = imagecolorallocate($img, $this->textColor2[0], $this->textColor2[1], $this->textColor2[2]);
            $bottomTextSize = $bbox[2] - $bbox[0];
            $bottomTextHeight = $bbox[1] - $bbox[7];
            $imageWidth = imagesx($img);
            $imageHeight = imagesy($img);
            $xBottomText = $imageWidth / 2 - $bottomTextSize / 2;
            $yBottomText = $imageHeight - self::TEXT_MARGIN_BOTTOM;
            imagettftext($img, $this->fontSize2, 0, $xBottomText, $yBottomText, $bottomTextcolor, $this->fontTypes[$this->fontType], $this->bottomText);
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
    private function normalizeFontSize($fontSize){
        switch ($fontSize) {
            case 'small':
                return self::SMALL_FONT;
                break;
            case 'medium':
                return self::MEDIUM_FONT;
                break;
            case 'large':
                return self::LARGE_FONT;
            break;
            default:
                return self::MEDIUM_FONT;
                break;
        }
    }
    private function toRGB($hex) {
        if (strlen($hex)==7) { //enlever #
            $hex=substr($hex, 1);
        }
 
        $rgb=array();
        $rgb[]=(int)hexdec(substr($hex,0,2));
        $rgb[]=(int)hexdec(substr($hex,2,2));
        $rgb[]=(int)hexdec(substr($hex,4,2));
        return $rgb;
    }

} 
