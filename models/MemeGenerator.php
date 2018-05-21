
  <?php
class MemeGenerator{
    protected $image;
    protected $topText;
    protected $bottomText;
    protected $fontSize;// taille police texte top
    protected $fontSize2;// taille police texte bottom
    protected $imageName;
    protected $textColor1;// couleur texte top
    protected $textColor2;// couleur text bot
    protected $id;
    protected $fontTypes;// tableau des fonts disponibles
    protected $fontType1;// font sélectionnée top
    protected $fontType2;// font sélectionnée bot
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
        $this->fontType1 = 'font1';
        $this->fontType2 = 'font1';
    }
    public function generateMemeFromJPG()
    { 
        $img=imagecreatefromjpeg($this->image);
        // image max width 600;
        $img=$this->resize($img);
        putenv('GDFONTPATH=' . realpath('.' . '/assets/font/'));        
        // traitement topText
        if (strlen($this->topText) > 0){
            // couleur texte top
            $topTextColor = $this->set_textColor($img, $this->textColor1);
            $textWrap = $this->textWrap($img, $this->fontSize, $this->fontTypes[$this->fontType1], $this->topText);
            for ($i = 1; $i <= count($textWrap); $i++) {
                // calcule des tailles retourne tableau ['textWidth' => $textWidth, 'textHeight' => $textHeight, 'imageWidth' => $imageWidth, 'imageHeight' => $imageHeight]
                $topTextSizes = $this->computeTextSize($img, $this->fontSize, $this->fontTypes[$this->fontType1], $textWrap[$i - 1]);
                $topTextSizes['textHeight'] = ($topTextSizes['textHeight'] * $i) + (($i - 1) * self::TEXT_MARGIN_TOP);
                // insertion du texte dans l'image
                $topTextInsert = $this->add_top_text($img, $topTextSizes, $topTextColor, $this->fontTypes[$this->fontType1], $this->fontSize, $textWrap[$i - 1]);
            }
        }        
        // traitement bottomText
        if (strlen($this->bottomText) > 0){
            // couleur bottom texte
            $bottomTextColor = $this->set_textColor($img, $this->textColor2);
            $bottomTextWrap = $this->textWrap($img, $this->fontSize2, $this->fontTypes[$this->fontType2], $this->bottomText);
            for ($i = 1; $i <= count($bottomTextWrap); $i++) {
                // calcule des tailles retourne tableau ['textWidth' => $textWidth, 'textHeight' => $textHeight, 'imageWidth' => $imageWidth, 'imageHeight' => $imageHeight]
                $bottomTextSizes = $this->computeTextSize($img, $this->fontSize2, $this->fontTypes[$this->fontType2], $bottomTextWrap[$i - 1]);
                $bottomTextSizes['textHeight'] = (count($bottomTextWrap) - $i) * ($bottomTextSizes['textHeight'] + self::TEXT_MARGIN_BOTTOM);
                // insertion du texte dans l'image
                $topTextInsert = $this->add_bottom_text($img, $bottomTextSizes, $bottomTextColor, $this->fontTypes[$this->fontType2], $this->fontSize2, $bottomTextWrap[$i - 1]);
            }
        }
        // si il y a du texte en haut ou en bas
        if ((strlen($this->topText) > 0) || (strlen($this->bottomText) > 0)){
            // si le nom du meme n'existe pas
            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $this->imageName)){
                imagejpeg($img, $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $this->imageName);
        
                imagedestroy($img);
                return 'meme_' . $this->imageName;
            // sinon trouver un autre nom
            } else {
                $memeName = $this->findValidName(1);
                imagejpeg($img, $_SERVER["DOCUMENT_ROOT"] . "/meme_editor/assets/img/meme/meme_" . $memeName);
        
                imagedestroy($img);
                return 'meme_' . $memeName;
            }            
        }              
    }
    private function set_textColor($img, $rgb){
        $textColor = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        return $textColor;
    }
    // Césure du texte, quelle misère!!!!!
    private function textWrap($img, $fontSize, $fontType, $text){
        $max_width = imagesx($img) - (self::TEXT_MARGIN_LEFT + self::TEXT_MARGIN_RIGHT);
        // explose la string en tableau de chaque mots
        $split = explode(" ", $text);

        $string = "";
        $new_string = "";
        $textWrap = array();
        for ($i = 0; $i < count($split); $i++){
            $new_string .= $split[$i] . " ";
            // vérif taille string
            $tbbox = imagettfbbox($fontSize, 0, $fontType, $new_string);
            if ($tbbox[4] < $max_width)
            {
                $string = $new_string;
            } else {
                $i --;
                $textWrap[] = rtrim($string);
                $string = '';
                $new_string = '';
            }
            if (($i === (count($split) - 1)) && (strlen($string) > 0)){
                $textWrap[] = rtrim($string);
            }
        }
        return $textWrap;
    }
    private function computeTextSize($img, $fontSize, $fontType, $text){
        // largeur image
        $imageWidth = imagesx($img);
        // hauteur image
        $imageHeight = imagesy($img);
        // on met le texte dans un rectangle virtuel pour prendre des mesures
        $bbox = imagettfbbox($fontSize, 0, $fontType, $text);
        // largeur texte
        $textWidth = $bbox[2] - $bbox[0];
        // hauteur texte
        $textHeight = $bbox[1] - $bbox[7];
        
        return ['textWidth' => $textWidth, 'textHeight' => $textHeight, 'imageWidth' => $imageWidth, 'imageHeight' => $imageHeight];
    }
    private function add_top_text($img, $sizes, $color, $fontType, $fontSize, $text){
        // calcul position x pour texte centré
        $xText = $sizes['imageWidth'] / 2 - $sizes['textWidth'] / 2;
        // calcul position y pour point en bas à gauche du texte + marge Top
        $yText = $sizes['textHeight'] + self::TEXT_MARGIN_TOP;
        // insertion du texte dans l'image
        return imagettftext($img, $fontSize, 0, $xText, $yText, $color, $fontType, $text);
    }
    private function add_bottom_text($img, $sizes, $color, $fontType, $fontSize, $text){
        // calcul position x pour texte centré
        $xText = $sizes['imageWidth'] / 2 - $sizes['textWidth'] / 2;
        // calcul position y (hauteur image - marge bottom)
        $yText = ($sizes['imageHeight'] - self::TEXT_MARGIN_BOTTOM) - $sizes['textHeight'];
        // insertion du texte dans l'image
        return imagettftext($img, $fontSize, 0, $xText, $yText, $color, $fontType, $text);
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
