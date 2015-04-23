<?php
$action = filter_input(INPUT_GET,'action');
if($action == null){
	$action = 'demandeConnexion';
}

switch($action){
	case 'demandeConnexion':{
            include("vues/v_connexion.php");
            break;
	}
	case 'valideConnexion':{
            $login = filter_input(INPUT_POST,'login');
            $mdp = filter_input(INPUT_POST,'mdp');
            $visiteur = $pdo->getInfosVisiteur($login,$mdp);
            $visiteurCo = 1;
            if(!($visiteur = $pdo->getInfosVisiteur($login,$mdp))){
                $visiteurCo = 0;
            }
            if($visiteurCo == 0){
                    ajouterErreur("Login ou mot de passe incorrect");
                    include("vues/v_erreurs.php");
                    include("vues/v_connexion.php");
            }
            else{
                $id = $visiteur->id;
                $nom =  $visiteur->nom;
                $prenom = $visiteur->prenom;
                $comptable = $visiteur->comptable;
                connecter($id,$nom,$prenom,$comptable);
                if ($comptable == 'non'){
                    include("vues/v_sommaire.php");
                }
                else if ($comptable == 'oui'){
                    include("vues/v_sommaire_compt.php");
                }
            }
            break;
	}
	default :{
            include("vues/v_connexion.php");
            break;
	}
}
?>