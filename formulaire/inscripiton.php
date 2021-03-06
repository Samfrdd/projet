
<!--
Author: Sam Freddi
date : 04.05.2022
detail : Formulaire pour s'inscrire
-->

<?php
session_start();
require_once '../db/database.php';
$erreurMdp = "";
$erreurPseudo = "";


// Function qui verifie si un pseudo existe
function verifiePseudoExist($pseudo)
{
	$sql = "SELECT `utilisateurs`.`Pseudo` FROM `projet`.`utilisateurs` Where `utilisateurs`.`Pseudo` = '$pseudo'";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
		$sql = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
	} catch (PDOException $e) {
		return false;
	}
	if ($sql == "") {
		return true;
	} else {
		return false;
	}
}


// Ajouter un nouveau user
function addUsers($pseudo, $password)
{
	$sql = "INSERT INTO `projet`.`utilisateurs` (`Pseudo`,`MotDePasse`) VALUES(:p,:m)";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":p" => $pseudo, ":m" => password_hash($password, PASSWORD_DEFAULT, ['cost' => 14])));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
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
	// Vérification du champs prix
	if (filter_has_var(INPUT_POST, 'ConfirmPassword')) {
		$confirmPassword = $_POST["ConfirmPassword"];
	}

	if ($password == $confirmPassword) {
		$pseudoExist = verifiePseudoExist($pseudo);
	}
	else {
		$erreurMdp = "Les deux mot de passe ne son pas identique.";
	}
	if ($pseudoExist == true) {
		addUsers($pseudo, $password);
		$_SESSION["pseudo"] = $pseudo;
		header("Location: ../index.php");
		exit;
	}
	else{
		$erreurPseudo = "Le pseudo que vous avez saisie existe déjà.";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Inscription</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>

	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />

	<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">

</head>

<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1>Crée un compte</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="#" method="post">
					<input class="text" type="text" name="Pseudo" placeholder="Pseudo" required="">
					<?php
					if ($erreurPseudo != "") {
                        echo   '<div class="alert alert-danger mt-4" role="alert">' . $erreurPseudo . '</div>';
                    }
					?>
					<input class="text w3lpass" type="password" name="password" placeholder="Mot de passe" required="">
					<input class="text w3lpass" type="password" name="ConfirmPassword" placeholder="Confirmer Mot de passe" required="">
					<?php
					if ($erreurMdp != "") {
                        echo   '<div class="alert alert-danger" role="alert">' . $erreurMdp . '</div>';
                    }
					?>
					<input type="submit" class="btn btn-warning mb-4 text-black" name="submit" value="Inscription">
				</form>
				<p>Vous avez deja un compte ? <a href="./connexion.php"> Connectez-vous !</a></p>
			</div>
		</div>
	</div>
</body>

</html>