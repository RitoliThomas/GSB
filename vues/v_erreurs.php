<div class ="erreur">
<ul>
<?php 
foreach($_REQUEST['erreurs'] as $erreur)
	{
      echo "<div class='col-md-3'></div><div class='col-md-6'><div class='alert alert-dismissible alert-danger'><li>$erreur</li></div></div><div class='col-md-3'></div>";
	}
?>
</ul></div>
