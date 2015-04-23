
   
        <h3>Mois à sélectionner : </h3>
        <form action="index.php?uc=suiviPaiement&action=fichePersonelMois" method="post">
            <div class="panel panel-default">
                <div align="center">
                    <p>
                        <input type="text" name="lesVisiteurs" hidden value="<?php echo $leVisiteur; ?>">
                        <label for="lstMois" accesskey="n">Mois : </label>
                        <select id="lstMois" name="lstMois" class="form-control" style="width: 120px">
                            <option>Choisir</option>
                            <?php
                                foreach ($lesMois as $unMois)
                                {
                                    $mois = $unMois['mois'];
                                    $numAnnee =  $unMois['numAnnee'];
                                    $numMois =  $unMois['numMois'];
                                    if($mois == $moisASelectionner){                                
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
   

          <script type="text/javascript">
       $(document).ready(function(){
          $("#lstMois").change(function() {
            $.ajax({
               url : 'index.php?uc=suiviPaiement&action=fichePersonelMois', 
               type : 'POST', 
               dataType : 'html',
               data : $('input, select, textarea').serialize(),
               success : function(data){
                           document.getElementById('resultat').innerHTML = "";
                           $('#resultat').append(data);
                       }
         });
        });
      });
      
      </script>﻿