

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
        case 'home':
        require_once('controllers/control_home.php');
        break;
        case 'generator':
        require_once('controllers/Controller_generator.php');
        break;
        case 'download':
        require_once('controllers/Controller_download.php');
        var_dump($_POST);
        if ((isset($_POST['topText'])) 
            && (isset($_POST['bottomText'])) 
                && (isset($_POST['textColor1'])) 
                    && (isset($_POST['textSize']))
                        && (isset($_POST['url']))
                            && (isset($_POST['textColor2']))
                                && (isset($_POST['id'])))
        {
            echo 'coucou';
            $memeData = [ 'topText' => $_POST['topText'],
                            'bottomText' => $_POST['bottomText'],
                            'textColor1' => $_POST['textColor1'],
                            'textColor2' => $_POST['textColor2'],
                            'textSize' => $_POST['textSize'],
                            'url' => $_POST['url'],
                            'id' => $_POST['id']];
            $action = new Controller_download($memeData);
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

