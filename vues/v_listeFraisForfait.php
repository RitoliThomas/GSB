<div class="col-md-6">
    <div align ="center" class="panel panel-default">
              <div class="panel-body">
    <div align="left">
    <h3><span class="label label-primary">Renseigner ma fiche de frais du mois <?php echo $numMois."-".$numAnnee ?></span></h3>
                  </div>
    
    <br>
      <form method="POST"  action="index.php?uc=gererFrais&action=validerMajFraisForfait">
      <div class="panel panel-default">
          <div align="center">
          <fieldset>
            <legend>Eléments forfaitisés
            </legend>
			<?php
				foreach ($lesFraisForfait as $unFrais)
				{
					$idFrais = $unFrais['idfrais'];
					$libelle = $unFrais['libelle'];
					$quantite = $unFrais['quantite'];
			?>
					<p>
						<label for="idFrais"><?php echo $libelle ?></label>
						<input class="form-control" style="width: 200px" type="text" id="idFrais" name="lesFrais[<?php echo $idFrais?>]" size="10" maxlength="5" value="<?php echo $quantite?>" >
					</p>
			
			<?php
				}
			?>
			
			
			
			
           
          </fieldset>
          </div>
      </div>
      <div align="center">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" class='btn btn-primary' />
        <input id="annuler" type="reset" value="Effacer" size="20" class='btn btn-primary' />
      </p> 
      </div>
              </div>
    </div>
      </form>

  