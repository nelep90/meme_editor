<?php
//Rounting
if (isset($_GET['action'])){
    
    switch ($_GET['action']) { 
        
    //routeur
        //case 'affiche':
        //require_once('controleur/affiche.php');
        //break;
        case 'generator':
        require_once('controllers/control_generator.php');
            echo 'coucou le grand';
        break;
        default:
        require_once('error.html');
    
    }
} else {
    require_once('controllers/control_generator.php');
}

