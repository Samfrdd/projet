<?php 
// Auteur : Sam Freddi
// Description : Page pour se déconnecter, détruit la session et redirige vers la page index

session_start();


$_SESSION[] = "";

session_destroy();


header("Location: ./index.php");
exit;




?>