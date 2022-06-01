<!--
Author: Colorlib
Author URL: https://colorlib.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->

<?php

// Auteur : Sam Freddi
// Description : Page connexion

session_start();
require_once '../db/database.php';
$erreur = "";

// Function qui vérifie si le pseudo existe deja
function verifieAccountExist($pseudo)
{
	$sql = "SELECT `utilisateurs`.`MotDePasse` FROM `projet`.`utilisateurs` Where `utilisateurs`.`Pseudo` = :p";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':p' => $pseudo));
		$sql = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
	} catch (PDOException $e) {
		return false;
	}
	if ($sql != "") {
		return $sql["MotDePasse"];
	} else {
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

	$verifie = verifieAccountExist($pseudo);
	if (password_verify($password, $verifie)) {
		$_SESSION["pseudo"] = $pseudo;
		header("Location: ../index.php");
		exit;
	} else {
		$erreur = "le pseudo ou le mot de passe que vous avez saisie est incorrect !";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Connexion</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
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
					<?php
					if ($erreur != "") {
                        echo   '<div class="alert alert-danger" role="alert">' . $erreur . '</div>';
                    }
					?>
					<input type="submit" class="btn btn-warning mb-4 text-black" name="submit" value="Connexion">
				</form>
				<p>Vous n'avez pas de compte ? <a href="./inscripiton.php"> Créez en un !</a></p>
			</div>
		</div>
	</div>
</body>

</html>