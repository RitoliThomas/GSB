
﻿    <!-- Division pour le sommaire -->        
<div id="container">

<div class="col-md-3">
    <div align ="center" class="panel panel-default">
              <div class="panel-body">
<div id="sidebar" class="sidebar-offcanvas">
      <div class="col-md-12">
        <h3>Visiteur : <?php echo $_SESSION['prenom']."  ".$_SESSION['nom'] ?></h3>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais<span class="glyphicon glyphicon-pencil" style="margin-left: 8px"></span></a></li>
          <li><a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais<span class="glyphicon glyphicon-list" style="margin-left: 8px"></span></a></li>
          <li><a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion<span class="glyphicon glyphicon-off" style="margin-left: 8px"></span></a></li>
        </ul>
      </div>
  </div>
              </div>
    </div>
        </div>
    </div>
        
    </div>
    