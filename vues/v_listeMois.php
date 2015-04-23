 
    <div id="contenu" class="col-md-6">
        <div align ="center" class="panel panel-default">
              <div class="panel-body">
      <div align="left">
    <h3><span class="label label-primary">Mes fiches de frais</span></h3>
                  </div>
      <h3>Mois à sélectionner : </h3>
      <form action="index.php?uc=etatFrais&action=voirEtatFrais" method="post">
      <div class="panel panel-default">
          <div align="center">
      <p>
	 
        <label for="lstMois" accesskey="n">Mois : </label>
        <select id="lstMois" name="lstMois" class="form-control" style="width: 120px">
            <?php
			foreach ($lesMois as $unMois)
			{
			    $mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
				if($mois == $moisASelectionner){
				?>
				<option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
			
			}
           
		   ?>    
            
        </select>
      </p>
          </div>
      </div>

          <div id="resultat"></div>
      </form>
              </div>
        </div>
          <script type="text/javascript">
       $(document).ready(function(){
          $("#lstMois").change(function() {
              
              
            $.ajax({
               url : 'index.php?uc=etatFrais&action=voirEtatFrais', 
               type : 'POST', 
               dataType : 'html',
               data : $(this).serialize(),
       
       
               success : function(data){
                           document.getElementById('resultat').innerHTML = "";
                           $('#resultat').append(data);
                       }
    

        
         });
        });
      });
      
      </script>﻿