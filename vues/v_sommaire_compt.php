<?php
   echo '<body style="background-image:url(\'./images/orange.jpeg\'); background-color:#FC7001 !important">';
?>
﻿    <!-- Division pour le sommaire -->
    <div id="container">

<div class="col-md-3">
    <div align ="center" class="panel panel-default">
              <div class="panel-body">
<div id="sidebar" class="sidebar-offcanvas">
      <div class="col-md-12">
        <h3>Comptable : <?php echo $_SESSION['prenom']."  ".$_SESSION['nom'] ?></h3>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="index.php?uc=validationFrais&action=afficheVisiteur" title="Valider les fiches de frais ">Valider les fiches de frais<span class="glyphicon glyphicon-ok" style="margin-left: 8px"></span></a></li>
          <li><a href="index.php?uc=validationFrais&action=afficheVisiteurReporté" title="Fiches de frais reportées ">Consulter fiches de frais reportées<span class="glyphicon glyphicon-search" style="margin-left: 8px"></span></a></li>
          <li><a href="index.php?uc=suiviPaiement&action=afficheSuiviPaiement" title="Mise en paiement">Mise en paiement</a></li>
		  <li><a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion<span class="glyphicon glyphicon-off" style="margin-left: 8px"></span></a></li>
        </ul>
      </div>
  </div>
              </div>
    </div>
        </div>
    </div>
    