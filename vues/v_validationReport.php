
<div class="col-md-6">
    
    <div align ="center" class="panel panel-default">
        <div class="panel-body">
                  <div align="left">
                    <h3><span class="label label-warning">Liste fiche de frais reportées</span></h3>
                  </div>
            <form action="index.php?uc=validationFrais&action=voirFicheFraisReport" method="POST">
              <div class="panel-body">
                  
                  <label for="lesVisiteursReport" accesskey="n">Visiteurs ayant des fiches reportées : </label>
                    <select id="lesVisiteursReport" name="lesVisiteursReport" class="form-control" style="width: 240px">
                        <option value="Choisir" selected="selected" disabled="disabled"> Choisir </option>
                        <?php 
                            $lesVisiteursReport = $pdo->getLesVisiteursReport();
                            foreach ($lesVisiteursReport as $unVisiteur)
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

              </div>
            </form>
        <div id="resultatfraisReport"></div>
              </div>
    </div>
    
    <script type="text/javascript">
       $(document).ready(function(){
          $("#lesVisiteursReport").change(function() {
              
    
            $.ajax({
               url : 'index.php?uc=validationFrais&action=voirFicheFraisReport', 
               type : 'POST', 
               dataType : 'html',
               data : $(this).serialize(),
       

               success : function(data){
                           document.getElementById('resultatfraisReport').innerHTML = "";
                           $('#resultatfraisReport').append(data);
                       }

         });
        });
      });
      
 </script>
