
<div class="col-md-6">
    <div align ="center" class="panel panel-default">
              <div class="panel-body">
                  <div align="left">
    <h3><span class="label label-warning">Liste fiche de frais</span></h3>
                  </div>
                  <br>
    <h3>Fiches de frais à valider:</h3>
    <form action="index.php?uc=validationFrais&action=voirFicheFrais" method="POST">
        <div class="panel panel-default">
            <div align="center">
                <p>
                    <label for="lesVisiteurs" accesskey="n">Visiteurs : </label>
                    <select id="lesVisiteurs" name="lesVisiteurs" class="form-control" style="width: 240px">
                        <option value="Choisir" selected="selected" disabled="disabled"> Choisir </option>
                        <?php 
                            $lesVisiteurs = $pdo->getLesVisiteursNonValider();
                            foreach ($lesVisiteurs as $unVisiteur)
                            {
                                $nom = $unVisiteur[0];
                                $prenom = $unVisiteur[1];
                                $num = $unVisiteur[2];
                                ?>
                                <option value="<?php echo $num ?>"><?php echo $nom." ".$prenom ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </p>
            </div>
        </div>
    </form>
    <div id="resultatfrais"></div>
              </div>
    </div>
</div>

  <script type="text/javascript">
       $(document).ready(function(){
          $("#lesVisiteurs").change(function() {
              
    
            $.ajax({
               url : 'index.php?uc=validationFrais&action=voirFicheFrais', 
               type : 'POST', 
               dataType : 'html',
               data : $(this).serialize(),
       

               success : function(data){
                           document.getElementById('resultatfrais').innerHTML = "";
                           $('#resultatfrais').append(data);
                       }

         });
        });
      });
      
 </script>﻿