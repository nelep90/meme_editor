<?php

// config.
function loadModel($classe)
{
  require 'models/' . $classe . '.php';
}
spl_autoload_register('loadModel');
require_once ('vendor/autoload.php');
require_once ('controllers/Controller.php');
// fin config.

//Rounting
if (isset($_GET['action'])){
    
    switch ($_GET['action']) { 
        
    //routeur
        // Page d'accueil
        case 'home':
        require_once('controllers/control_home.php');
        break;
        // Page Générateur de Meme
        case 'generator':
        require_once('controllers/Controller_generator.php');
        if ((isset($_GET['upload'])) && ($_GET['upload'] == "true")){
            if ((isset($_FILES['uploadImage'])) && (!empty($_FILES['uploadImage']))){
                $action = new Controller_generator();
                $action->action_upload();                
            }
        } else {
            if ((isset($_GET['id'])) && (!empty($_GET['id']))){
                $id = (int)$_GET['id'];
                $action = new Controller_generator();
                $action->action_layout($id);
            } else {
                $action = new Controller_generator();
                $action->action_layout();
            }
        }
        
        // index.php?action=generator&upload=true
        break;
        case 'download':
        require_once('controllers/Controller_download.php');
        if(!isset($_POST['textSize1'])) { $_POST['textSize1'] = 'medium';}
        if(!isset($_POST['textSize2'])) { $_POST['textSize2'] = 'medium';}
        if ((isset($_POST['topText'])) 
            && (isset($_POST['bottomText'])) 
                && (isset($_POST['textColor1'])) 
                    && (isset($_POST['textSize1']))
                        && (isset($_POST['url']))
                            && (isset($_POST['textColor2']))
                                && (isset($_POST['id']))
                                    && (isset($_POST['textSize2'])))
        {
            
            $memeData = [ 'topText' => Secure::html($_POST['topText']),
                            'bottomText' => Secure::html($_POST['bottomText']),
                            'textColor1' => $_POST['textColor1'],
                            'textColor2' => $_POST['textColor2'],
                            'textSize' => $_POST['textSize1'],
                            'textSize2' => $_POST['textSize2'],
                            'url' => Secure::html($_POST['url']),
                            'id' => $_POST['id']];
                            
            $action = new Controller_download($memeData);
            $action->genereAndDownload();

        } elseif ((isset($_GET['memeId'])) && (isset($_GET['memeName']))){

            $action = new Controller_download();
            $action->action_download($_GET['memeName']);
            $action->action_render($_GET['memeId']);
        }
        break;
        default:
        require_once('error.html');
    
    }
} else {
    require_once('controllers/Controller_generator.php');
    $action = new Controller_generator();
    $action->action_layout();
}

