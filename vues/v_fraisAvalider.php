<div>
    <form action="index.php?uc=validationFrais&action=validerFicheFrais" method="POST">
        <div align="center">
         <table class="table tablecomptable">
  	   <caption>Eléments forfaitisés </caption>
        <tr>
         <?php
         foreach ( $fraisForfaits as $unFraisForfait ) 
		 {
			$libelle = $unFraisForfait['libelle'];
		?>	
			<th> <?php echo $libelle?></th>
		 <?php
        }
		?>
		</tr>
        <tr>
        <?php
        $idinput=0;
          foreach (  $fraisForfaits as $unFraisForfait  ) 
		  {
				$quantite = $unFraisForfait['quantite'];            
		?>
            <td><input id="inputqte_<?php echo $idinput ?>" name="inputqte_<?php echo $idinput ?>" class="form-control" value="<?php echo $quantite?>"></input></td>
		 <?php
                 $idinput ++;
          }
		?>
		</tr>
    </table>  
        </div>
        <div align="center">
            <table class="table tablecomptable">
  	   <caption>Eléments hors-forfait </caption>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Date</th>
            <th>Prix</th>
            <th>Action</th>
		</tr>
        <tr>
           <?php
         foreach ( $fraisHorsForfaits as $unFraisHorsForfaitAvalider ) 
		 {
                        $id = $unFraisHorsForfaitAvalider[0];
			$libelle = $unFraisHorsForfaitAvalider[3];
                        $datefrais = $unFraisHorsForfaitAvalider[4];
                        $datefinale = $datefrais[8].$datefrais[9]."/".$datefrais[5].$datefrais[6]."/".$datefrais[0].$datefrais[1].$datefrais[2].$datefrais[3];
                        $montant = $unFraisHorsForfaitAvalider[5]." €";
            
		?>

        <tr>
                        <td><?php echo $id?></td>
			<td><?php 
                       if(strpos($libelle, "REFUSE : ") !== false){
                           echo substr($libelle, 9);
                       }
                       else{
                           echo $libelle;
                       }
                       ?></td>
                        <td><?php echo $datefinale?></td>
                        <td><?php echo $montant?></td>
                        <?php if(strpos($libelle, "REFUSE : ") !== false){ ?>
                               <td><div align="center"><a href="index.php?uc=validationFrais&action=autoriserUnHorsForfait&idhf=<?php echo $unFraisHorsForfaitAvalider[0] ?>&libellehf=<?php echo $libelle ?>" 
                                                          onclick="return confirm('Voulez-vous vraiment autoriser ce frais?');">Autoriser</a></div></td>
                       <?php }
                       else{ ?>
                               <td><div align="center"><a href="index.php?uc=validationFrais&action=refuserUnHorsForfait&idhf=<?php echo $unFraisHorsForfaitAvalider[0] ?>&libellehf=<?php echo $libelle ?>" 
                                                  onclick="return confirm('Voulez-vous vraiment refuser ce frais?');" style="color: red !important;">Refuser</a></div></td>
                       <?php } ?>
        </tr>
        
		 <?php
        }
		?>

		</tr>
    </table>  
            <h4>Nombre de justificatifs fournit :</h4>
            <input id="idvisit" name="idvisit" type="text" hidden size="3" value="<?php echo $leVisiteur ?>" class='input-sm'/>
            <input id="nbjusti" name="nbjusti" type="number" size="3" class='input-sm' pattern="^(0|[1-9][0-9]*)$" />
            <br>
            <br>
            <input id="validfichefrais" type="submit" value="Valider la fiche de frais" size="20" class='btn btn-warning' />
        </div>
    </form>
</div>

