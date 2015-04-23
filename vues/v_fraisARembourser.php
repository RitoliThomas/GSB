
<h3>Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : 
    </h3>
    <div class="panel panel-default">
    <p>
        Etat : <?php echo $libEtat; $total = 0;?> depuis le <?php echo $dateModif?> <br> Montant validé : <?php echo $montantValide?>
              
                     
    </p>
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
        $i=0;
          foreach (  $fraisForfaits as $unFraisForfait  ) 
		  {
				$quantite = $unFraisForfait['quantite'];
                                if ($i==0){
                                    $total=$total+($unFraisForfait['quantite']*110);
                                }
                                if ($i==1){
                                    $total=$total+($unFraisForfait['quantite']*0.63);
                                }
                                if ($i==2){
                                    $total=$total+($unFraisForfait['quantite']*80);
                                }
                                if ($i==3){
                                    $total=$total+($unFraisForfait['quantite']*29);
                                }
		?>
                <td class="qteForfait"><?php echo $quantite?> </td>
		 <?php
          }
		?>
		</tr>
    </table>
  	<table class="table tablecomptable">
  	   <caption>Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus -
       </caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>                
             </tr>
        <?php      
         
          foreach ( $fraisHorsForfaits as $unFraisHorsForfait ) 
		  {
			$date = $unFraisHorsForfait['date'];
			$libelle = $unFraisHorsForfait['libelle'];
			$montant = $unFraisHorsForfait['montant'];
                        $refuser = strstr($libelle, ' ', true);
                        if($refuser != "Refuser"){
                            $total = $total+$montant;
                        }
		?>
             <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
             </tr>
        <?php 
          }
		?>
    </table>
  </div>
  </div>
 
    <br>
    <form method="POST" action="index.php?uc=suiviPaiement&action=rembourserUnHorsForfait">
    <input type="text" hidden value="<?php echo $total; ?>" name="total" />
    <input type="text" hidden value='<?php echo filter_input(INPUT_POST,'lstMois');  ?> ' name='mois' />
    <input type="text" hidden value='<?php echo filter_input(INPUT_POST,'lesVisiteurs') ?>' name='employe' />
    <input id="validfichefrais" type="submit" value="Rembourser la fiche de frais" size="20" class='btn btn-warning' />
    </form>


<script type="text/javascript">
$(document).ready(function(){
    $("#validfichefrais").click(function() {
        $.ajax({
           url : 'index.php?uc=suiviPaiement&action=rembourserUnHorsForfait', 
           type : 'POST', 
           dataType : 'html',
           data : 'valeur1=' + mois + '&valeur2=' + employe,
           success : function(data){
                       document.getElementById('resultat').innerHTML = "";
                   $('#resultat').append(data);
            }
        });
    });
});
</script>