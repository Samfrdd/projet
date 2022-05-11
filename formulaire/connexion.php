
<!--
Author: Colorlib
Author URL: https://colorlib.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->

<?php
session_start();
require_once '../db/database.php';

function verifieAccountExist($pseudo,$password)
{
	$sql = "SELECT `utilisateurs`.`Pseudo` ,`utilisateurs`.`MotDePasse` FROM `projet`.`utilisateurs` Where `utilisateurs`.`Pseudo` = '$pseudo' And  `utilisateurs`.`MotDePasse` = '$password'";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
		$sql=$statement->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT);
	} catch (PDOException $e) {
		return false;
	}
	if ($sql != "") {
		return true;
	}
	else {
		return false;
	}
}
if (isset($_POST["submit"])) {


	// Vérification du champs Pseudo
	if (filter_has_var(INPUT_POST, 'Pseudo')) {
		$pseudo = filter_input(INPUT_POST, "Pseudo", FILTER_SANITIZE_STRING);
	}
	// Vérification du champs password
	if (filter_has_var(INPUT_POST, 'password')) {
		$password = $_POST["password"];
	}
	
	$verifie = verifieAccountExist($pseudo,$password);
	if ($verifie == true) {
		$_SESSION["pseudo"] = $pseudo;
		header("Location: ../index.php");
		exit;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Creative Colorlib SignUp Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- //Custom Theme files -->
<!-- web font -->
<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
<!-- //web font -->
</head>
<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1>Connexion</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="#" method="post">
					<input class="text" type="text" name="Pseudo" placeholder="Pseudo" required="">
					<input class="text w3lpass" type="password" name="password" placeholder="Mot de passe" required="">
					<input type="submit" name="submit" value="Connexion">
				</form>
				<p>Vous n'avez pas de compte ? <a href="./inscripiton.php"> Créez en un  !</a></p>
			</div>
		</div>
	</div>
</body>
</html>