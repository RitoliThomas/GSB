<?php
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
$uc = filter_input(INPUT_GET,'uc');
$action = filter_input(INPUT_GET, 'action');

if($action != NULL){
    if($action != 'voirEtatFrais'){
        if($action != 'voirFicheFrais'){
            if($action != 'suiviPaiementPersonel'){
                if($action != 'fichePersonelMois'){
                    include("vues/v_entete.php");
                }
            }
        }
    }
}
else{
    include("vues/v_entete.php");
}

if(!isset($uc) || !$estConnecte){
    $uc = 'connexion';
}	 
    
switch($uc){
	case 'connexion':{
				include("controleurs/c_connexion.php");break;
		}
	case 'gererFrais' :{
				include("controleurs/c_gererFrais.php");break;
		}
	case 'etatFrais' :{
				include("controleurs/c_etatFrais.php");break; 
		}
    case 'validationFrais' :{
				include("controleurs/c_validationFrais.php");break; 
		}
	case 'suiviPaiement':{
                include("controleurs/c_suivipaiement.php");break;
        }
        
}
include("vues/v_pied.php") ;
?>

