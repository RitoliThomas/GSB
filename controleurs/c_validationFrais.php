<?php
$action = filter_input(INPUT_GET,'action');
if($action != 'voirFicheFrais'){
    include("vues/v_sommaire_compt.php");
}
if($action == null){
    $action = filter_input(INPUT_POST,'action');
    if($action == null){
	$action = 'afficheVisiteur';
    }
}

switch($action){
    case 'afficheVisiteur':{
        include("vues/v_validation.php");
        break;
    }
    case 'afficheVisiteurReporté':{
        $lesFichesFraisReport = $pdo->getLesFichesReporter();
        include("vues/v_validationReport.php");
        break;
    }
    case 'voirFicheFrais':{
        // Variable qui récupère l'id du visiteur séléctionné
        $leVisiteur = filter_input(INPUT_POST, 'lesVisiteurs');
        // Variable qui contient le mois actuel au format de la base de donnée
        $mois = getMois(date("d/m/Y"));
        // Varriable pour afficher l'année
        $numAnnee =substr( $mois,0,4);
        // Variable pour afficher le mois
        $numMois =substr( $mois,4,2);
        $fraisForfaits = $pdo->getLesFraisForfait($leVisiteur, $mois);
        $fraisHorsForfaits = $pdo->getLesFraisHorsForfait($leVisiteur, $mois);
        include("vues/v_fraisAvalider.php");
        
        break;
    }
    case 'voirFicheFraisReport':{
        // Variable qui récupère l'id du visiteur séléctionné
        $leVisiteur = filter_input(INPUT_POST, 'lesVisiteurs');
        // Variable qui contient le mois actuel au format de la base de donnée
        $mois = getMois(date("d/m/Y"));
        // Varriable pour afficher l'année
        $numAnnee =substr( $mois,0,4);
        // Variable pour afficher le mois
        $numMois =substr( $mois,4,2);
        
        $fraisForfaits = $pdo->getLesFraisForfait($leVisiteur, $mois);
        $fraisHorsForfaits = $pdo->getLesFraisHorsForfait($leVisiteur, $mois);
        include("vues/v_fraisAvalider.php");
        
        break;
    }
    case 'validerFicheFrais':{
        $nbjustificatif = filter_input(INPUT_POST, 'nbjusti');
        $idvisiteur = filter_input(INPUT_POST, 'idvisit');
        $qteHf_0 = filter_input(INPUT_POST, 'inputqte_0');
        $qteHf_1 = filter_input(INPUT_POST, 'inputqte_1');
        $qteHf_2 = filter_input(INPUT_POST, 'inputqte_2');
        $qteHf_3 = filter_input(INPUT_POST, 'inputqte_3');
        $mois = getMois(date("d/m/Y"));
        $pdo->updateFicheFrais($idvisiteur, $nbjustificatif, $mois, $qteHf_0, $qteHf_1, $qteHf_2, $qteHf_3);
        include("vues/v_validation.php");
        echo "<script>alert('Fiche de frais validée !');</script>";
        
        break;
    }
    case 'refuserUnHorsForfait':{
       $idHf = filter_input(INPUT_GET, 'idhf');
       $libelleHf = filter_input(INPUT_GET, 'libellehf');
       $pdo->majRefusFicheFrais($idHf, $libelleHf);
       echo "<script>alert('Frais refusé !');</script>";
       include("vues/v_validation.php");
       break;
   }
   case 'autoriserUnHorsForfait':{
       $idHf = filter_input(INPUT_GET, 'idhf');
       $libelleHf = filter_input(INPUT_GET, 'libellehf');
       $pdo->majAutoriserFicheFrais($idHf, $libelleHf);
       echo "<script>alert('Frais autorisé !');</script>";
       include("vues/v_validation.php");
       break;
   }
}