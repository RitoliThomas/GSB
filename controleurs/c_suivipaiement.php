<?php
$action = filter_input(INPUT_GET,'action');

if($action == null){
    $action = filter_input(INPUT_POST,'action');
    if($action == null){
	$action = 'afficheSuiviPaiement';
    }
}

if($action == 'afficheSuiviPaiement'){
    include("vues/v_sommaire_compt.php");
}


switch($action){
    case 'afficheSuiviPaiement':{
        include ('vues/v_suiviPaiement.php');
        break;
    }
    case 'suiviPaiementPersonel':{
        // Variable qui récupère l'id du visiteur séléctionné
        $leVisiteur = filter_input(INPUT_POST, 'lesVisiteurs');
        $leMois = filter_input(INPUT_POST,'lstMois'); 
        $lesMois=$pdo->getLesMoisDisponibles($leVisiteur);
        $fraisForfaits = $pdo->getLesFraisForfait($leVisiteur, $leMois);
        $fraisHorsForfaits = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
        include ("vues/v_listeMoisRemboursement.php");
        break;
    }
    case 'fichePersonelMois':{
        // Variable qui récupère l'id du visiteur séléctionné
        $leVisiteur = filter_input(INPUT_POST, 'lesVisiteurs');
        $leMois = filter_input(INPUT_POST,'lstMois'); 
        // Varriable pour afficher l'année
        $numAnnee =substr( $leMois,0,4);
        // Variable pour afficher le mois
        $numMois =substr( $leMois,4,2);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois);
        $libEtat = $lesInfosFicheFrais->libEtat;
        $montantValide = $lesInfosFicheFrais->montantValide;
        $nbJustificatifs = $lesInfosFicheFrais->nbJustificatifs;
        $dateModif =  $lesInfosFicheFrais->dateModif;
        $dateModif =  dateAnglaisVersFrancais($dateModif);
        $lesMois=$pdo->getLesMoisDisponibles($leVisiteur);
        $fraisForfaits = $pdo->getLesFraisForfait($leVisiteur, $leMois);
        $fraisHorsForfaits = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
        include("vues/v_fraisARembourser.php");
        break;
    }
    case 'rembourserUnHorsForfait':{
        $leVisiteur = filter_input(INPUT_POST, 'employe');
        $leMois = filter_input(INPUT_POST,'mois'); 
        $leTotal = filter_input(INPUT_POST, 'total');
        $pdo->miseEnPaiement($leVisiteur,$leMois);
        $pdo->setPrixFinal($leVisiteur, $leMois, $leTotal);
        header('Location: index.php?uc=suiviPaiement&action=afficheSuiviPaiement');
        echo "<script>alert('Fiche de frais remboursée !');</script>";
        break;
    }
}