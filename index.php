<?php
//Rounting
if (isset($_GET['action'])){
    
    switch ($_GET['action']) { 
        
    //routeur
        case 'home':
        require_once('controllers/control_home.php');
        break;
        case 'generator':
        require_once('controllers/control_generator.php');
        break;
        case 'download':
        require_once('controllers/control_download.php');
        break;
        default:
        require_once('error.html');
    
    }
} else {
    require_once('controllers/control_home.php');
}

