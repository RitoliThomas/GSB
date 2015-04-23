<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $monPdo;
        private static $monPdoGsb=null;
        private $co; 
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	try {
        $connect_str = "mysql:host=localhost;dbname=gsbV2";
        $connect_user = "root";
        $connect_pass = "";
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $this->co = new PDO($connect_str, $connect_user, $connect_pass, $options);
        $this->co->exec("SET CHARACTER SET utf8");
        return $this->co ;
    }
    catch (PDOException $ex){
        throw new Exception("Erreur à la connexion \n".$ex->getMessage());
    }
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}

/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}

	/**
 * Retourne les informations d'un visiteur
 * 
 * @param char $login
 * @param varchar $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
 */
public function getInfosVisiteur($login, $mdp){
    $req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom, visiteur.comptable as comptable
            from visiteur 
            where visiteur.login = :login and visiteur.mdp = sha1(:mdp)";
    $res = $this->co->prepare($req);
    $res->bindValue(":login", $login);
    $res->bindValue(":mdp", $mdp);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_OBJ);
    $ligne=$res->fetch();
    return $ligne;
}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
public function getLesFraisHorsForfait($idVisiteur,$mois){
    $req = "SELECT *
            FROM lignefraishorsforfait 
            WHERE lignefraishorsforfait.idvisiteur = :idVisiteur 
            AND lignefraishorsforfait.mois = :mois ";
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->execute();
    $lesLignes = $res->fetchAll();
    $nbLignes = count($lesLignes);
    for ($i=0; $i<$nbLignes; $i++){
        $date = $lesLignes[$i]['date'];
        $lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
    }
    return $lesLignes; 
}

/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 *
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
public function getNbjustificatifs($idVisiteur, $mois){
    $req = "select fichefrais.nbjustificatifs as nb
            from  fichefrais 
            where fichefrais.idvisiteur =:idVisiteur
            and fichefrais.mois = :mois";
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->execute();
    $laLigne = $res->fetch();
    return $laLigne->nb;
}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 *
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
public function getLesFraisForfait($idVisiteur, $mois){
    $req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, lignefraisforfait.quantite as quantite 
            from lignefraisforfait inner join fraisforfait on fraisforfait.id = lignefraisforfait.idfraisforfait
            where lignefraisforfait.idvisiteur =:idVisiteur and lignefraisforfait.mois=:mois
            order by lignefraisforfait.idfraisforfait";	
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->execute();
    $lesLignes = $res->fetchAll();
    return $lesLignes; 
}

/**
 * Retourne tous les id de la table FraisForfait
 *
 * @return un tableau associatif 
*/
public function getLesIdFrais(){
    $req = "select fraisforfait.id as idfrais 
            from fraisforfait 
            order by fraisforfait.id";
    $res = $this->co->query($req);
    $res->execute();
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

/**
 * Met à jour la table ligneFraisForfait
 *
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 *
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
*/
public function majFraisForfait($idVisiteur, $mois, $lesFrais){
    $lesCles = array_keys($lesFrais);
    foreach($lesCles as $unIdFrais){
        $qte = $lesFrais[$unIdFrais];
        $req = "update lignefraisforfait set lignefraisforfait.quantite = :qte
                where lignefraisforfait.idvisiteur = :idVisiteur 
                and lignefraisforfait.mois = :mois
                and lignefraisforfait.idfraisforfait = :unIdFrais";
        $res = $this->co->prepare($req);
        $res->bindValue(":idVisiteur", $idVisiteur);
        $res->bindValue(":mois", $mois);
        $res->bindValue(":unIdFrais", $unIdFrais);
        $res->bindValue(":qte", $qte);
        $res->execute();
    }
}

/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 * 
 * @param type $idVisiteur
 * @param type $mois sous la forme aaaamm
 * @param type $nbJustificatifs
 */
public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
    $req = "update fichefrais set nbjustificatifs = :nbJustificatifs 
            where fichefrais.idvisiteur = :idVisiteur 
            and fichefrais.mois = :mois";
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->bindValue(":nbJustificatifs", $nbJustificatifs);
    $res->execute();	
}

/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 *
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
public function estPremierFraisMois($idVisiteur,$mois)
{
    $ok = false;
    $req = "select count(*) as nblignesfrais 
            from fichefrais 
            where fichefrais.mois = :mois and fichefrais.idvisiteur = :idVisiteur";
    $res = $this->co->prepare($req);
    $res->bindValue(":mois", $mois);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_OBJ);
    $ligne=$res->fetch();
    if($ligne->nblignesfrais == 0){
        $ok = true;
    }
    return $ok;
}

/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
public function dernierMoisSaisi($idVisiteur){
    $req = "select max(mois) as dernierMois
            from fichefrais 
            where fichefrais.idvisiteur = :idVisiteur";
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $laLigne = $res->fetch();
    $dernierMois = $laLigne->dernierMois;
    return $dernierMois;
}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
public function creeNouvellesLignesFrais($idVisiteur,$mois){
    $dernierMois = $this->dernierMoisSaisi($idVisiteur);
    $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
    if($laDerniereFiche->idEtat == 'CR'){
        $this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
    }
    $req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
            values(:idVisiteur,:mois,0,0,now(),'CR')";
    $res = $this->co->prepare($req);
    $res->bindValue(":mois", $mois);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->execute();
    $lesIdFrais = $this->getLesIdFrais();
    foreach($lesIdFrais as $uneLigneIdFrais){
        $unIdFrais = $uneLigneIdFrais['idfrais'];
        $req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
                values(:idVisiteur,:mois,:unIdFrais,0)";
        $res = $this->co->prepare($req);
        $res->bindValue(":mois", $mois);
        $res->bindValue(":idVisiteur", $idVisiteur);
        $res->bindValue(":unIdFrais", $unIdFrais);
        $res->execute();
    }
}
	
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 * 
 * @param type $idVisiteur
 * @param type $mois sous la forme aaaamm
 * @param type $libelle : le libelle du frais
 * @param type $date : la date du frais au format français jj//mm/aaaa
 * @param type $montant : le montant
 */
public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
    $dateFr = dateFrancaisVersAnglais($date);
    $req = "insert into lignefraishorsforfait 
            values('', :idVisiteur, :mois, :libelle, :dateFr, :montant)";
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->bindValue(":libelle", $libelle);
    $res->bindValue(":dateFr", $dateFr);
    $res->bindValue(":montant", $montant);
    $res->execute();
}
	
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 * @param $idFrais 
*/
public function supprimerFraisHorsForfait($idFrais){
    $req = "delete from lignefraishorsforfait where lignefraishorsforfait.id = :idFrais ";
    $res = $this->co->prepare($req);
    $res->bindValue(":idFrais", $idFrais);
    $res->execute();
}
	
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 * 
 * @param type $idVisiteur
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
public function getLesMoisDisponibles($idVisiteur){
    $req = "select fichefrais.mois as mois 
            from  fichefrais 
            where fichefrais.idvisiteur =:idVisiteur 
            order by fichefrais.mois desc";
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->execute();
    $lesMois =array();
    $laLigne = $res->fetch();
    while($laLigne != null)	{
            $mois = $laLigne['mois'];
            $numAnnee =substr( $mois,0,4);
            $numMois =substr( $mois,4,2);
            $lesMois["$mois"]=array("mois"=>"$mois","numAnnee"  => "$numAnnee","numMois"  => "$numMois");
            $laLigne = $res->fetch(); 		
    }
    return $lesMois;
}
	
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 * 
 * @param type $idVisiteur
 * @param type $mois
 * @return type
 */
public function getLesInfosFicheFrais($idVisiteur,$mois){
    $req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
            ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
            where fichefrais.idvisiteur = :idVisiteur and fichefrais.mois = :mois";
    $res = $this->co->prepare($req);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_OBJ);
    $laLigne = $res->fetch();
    return $laLigne;
}
	
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * 
 * @param type $idVisiteur
 * @param type $mois
 * @param type $etat
 */
public function majEtatFicheFrais($idVisiteur,$mois,$etat){
    $req = "update ficheFrais set idEtat = :etat, dateModif = now() 
            where fichefrais.idvisiteur = :idVisiteur 
            and fichefrais.mois = :mois";
    $res = $this->co->prepare($req);
    $res->bindValue(":etat", $etat);
    $res->bindValue(":idVisiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->execute();
}

/**
 * Selectionne tout les visiteurs qui ont des fiches a valider
 * @return type $lesLignes
 */
public function getLesVisiteursNonValider(){
    $req = "SELECT DISTINCT visiteur.nom AS nom, visiteur.prenom AS prenom , visiteur.id AS numero
            FROM visiteur INNER JOIN fichefrais ON visiteur.id = fichefrais.idVisiteur
            WHERE fichefrais.idEtat = 'CR'
            AND visiteur.comptable = 'non'
            ORDER BY nom";
    $res = $this->co->query($req);
    $res->execute();
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

/**
 * Selectionne tout les visiteurs qui ont des fiches reportées à valider
 * @return type $lesLignes
 */
public function getLesVisiteursReport(){
    $req = "SELECT DISTINCT visiteur.nom AS nom, visiteur.prenom AS prenom , visiteur.id AS numero
            FROM visiteur INNER JOIN fichefrais ON visiteur.id = fichefrais.idVisiteur
            WHERE fichefrais.idEtat = 'CR'
            AND visiteur.comptable = 'non'
            AND fichefrais.mois < date_format(now(), '%Y%m')
            ORDER BY nom";
    $res = $this->co->query($req);
    $res->execute();
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

/**
* Modifie le libelle d'une fiche de frais hors forfait pour indiquer "REFUSE : "
* 
* @param type $idLigne
* @param type @libelle
*/
public function majRefusFicheFrais($idLigne, $libelle){
    if(strpos($libelle, "REFUSE : ") === false){
   $libfinal = "REFUSE : ".$libelle;
   $req = "update lignefraishorsforfait set libelle = :libfinal where lignefraishorsforfait.id = :idLigne and lignefraishorsforfait.libelle = :libelle";
   $res = $this->co->prepare($req);
   $res->bindValue(":libfinal", $libfinal);
   $res->bindValue(":idLigne", $idLigne);
   $res->bindValue(":libelle", $libelle);
   $res->execute();
    }
}

/**
* Modifie le libelle d'une fiche de frais hors forfait pour enlever "REFUSE : "
* 
* @param type $idLigne
* @param type @libelle
*/
public function majAutoriserFicheFrais($idLigne, $libelle){
  $libfinal = substr($libelle, 9);
  $req = "update lignefraishorsforfait set libelle = :libfinal where lignefraishorsforfait.id = :idLigne and lignefraishorsforfait.libelle = :libelle";
  $res = $this->co->prepare($req);
  $res->bindValue(":libfinal", $libfinal);
  $res->bindValue(":idLigne", $idLigne);
  $res->bindValue(":libelle", $libelle);
  $res->execute();
}

/**
* Permet de mettre de clôturer la fiche de frais et de mettre à jour les données si besoin
* 
* @param type $idvisiteur
* @param type $nbjustificatif
* @param type $mois 
* @param type $qteHf_etape 
* @param type $qteHf_km
* @param type $qteHf_hotel
* @param type $qteHf_resto
*/
public function updateFicheFrais($idvisiteur, $nbjustificatif, $mois, $qteHf_etape, $qteHf_km, $qteHf_hotel, $qteHf_resto){
    $req="update fichefrais set idEtat='CL', nbJustificatifs=:nbjusti WHERE idVisiteur=:visiteur AND mois=:mois  ;";
    $res = $this->co->prepare($req);
    $res->bindValue(":visiteur", $idvisiteur);
    $res->bindValue(":nbjusti", $nbjustificatif);
    $res->bindValue(":mois", $mois);
    $res->execute();
    $req2="update lignefraisforfait set quantite=:qte_etape WHERE idVisiteur=:visiteur AND mois=:mois AND idFraisForfait='ETP';";
    $res2 = $this->co->prepare($req2);
    $res2->bindValue(":qte_etape", $qteHf_etape);
    $res2->bindValue(":mois", $mois);
    $res2->bindValue(":visiteur", $idvisiteur);
    $res2->execute();
    $req3="update lignefraisforfait set quantite=:qte_km WHERE idVisiteur=:visiteur AND mois=:mois AND idFraisForfait='KM';";
    $res3 = $this->co->prepare($req3);
    $res3->bindValue(":qte_km", $qteHf_km);
    $res3->bindValue(":mois", $mois);
    $res3->bindValue(":visiteur", $idvisiteur);
    $res3->execute();
    $req4="update lignefraisforfait set quantite=:qte_hotel WHERE idVisiteur=:visiteur AND mois=:mois AND idFraisForfait='NUI';";
    $res4 = $this->co->prepare($req4);
    $res4->bindValue(":qte_hotel", $qteHf_hotel);
    $res4->bindValue(":mois", $mois);
    $res4->bindValue(":visiteur", $idvisiteur);
    $res4->execute();
    $req5="update lignefraisforfait set quantite=:qte_resto WHERE idVisiteur=:visiteur AND mois=:mois AND idFraisForfait='REP';";
    $res5 = $this->co->prepare($req5);
    $res5->bindValue(":qte_resto", $qteHf_resto);
    $res5->bindValue(":mois", $mois);
    $res5->bindValue(":visiteur", $idvisiteur);
    $res5->execute();
}

public function getLesFichesReporter(){
    $req = "SELECT fichefrais.idVisiteur, visiteur.nom, visiteur.prenom, fichefrais.mois, fichefrais.nbJustificatifs, fichefrais.montantValide
            FROM visiteur INNER JOIN fichefrais ON visiteur.id = fichefrais.idVisiteur
            WHERE fichefrais.idEtat = 'CR'
            AND fichefrais.mois < date_format(now(), '%Y%m')
            ORDER BY nom";
    $res = $this->co->query($req);
    $res->execute();
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

/**
 * Met en paiement la fichefrais selectionner
 * 
 * @param type $idVisiteur
 * @param type $mois
 */
public function miseEnPaiement($idVisiteur, $mois){
    $req="UPDATE fichefrais set idEtat='MP' 
          WHERE idVisiteur = :visiteur 
          AND mois = :mois";
    $res = $this->co->prepare($req);
    $res->bindValue(":visiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->execute();
}

/**
 * Selectionne tout les visiteurs qui ont des fiches a valider
 * @return type $lesLignes
 */
public function getLesVisiteursNonRemboursé(){
    $req = "SELECT DISTINCT visiteur.nom AS nom, visiteur.prenom AS prenom , visiteur.id AS numero
            FROM visiteur INNER JOIN fichefrais ON visiteur.id = fichefrais.idVisiteur
            WHERE fichefrais.idEtat = 'CL'
            AND visiteur.comptable = 'non'
            ORDER BY nom";
    $res = $this->co->query($req);
    $res->execute();
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

public function setPrixFinal($idVisiteur, $mois, $total){
    $req="UPDATE fichefrais set montantValide=:total
          WHERE idVisiteur = :visiteur 
          AND mois = :mois";
    $res = $this->co->prepare($req);
    $res->bindValue(":visiteur", $idVisiteur);
    $res->bindValue(":mois", $mois);
    $res->bindValue(":total", $total);
    $res->execute();
    
}

}
?>