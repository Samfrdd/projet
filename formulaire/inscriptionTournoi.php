<!--
Author: Colorlib
Author URL: https://colorlib.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->

<?php
session_start();
require_once '../db/database.php';
$erreur = "";
function getTeam($pseudo)
{
	$sql = "SELECT `utilisateurs`.`Pseudo`,`utilisateurs`.`Role`
            FROM `projet`.`utilisateurs`
            WHERE `utilisateurs`.idEquipe = (
                SELECT `utilisateurs`.`IdEquipe`
                FROM `projet`.`utilisateurs`
                WHERE `utilisateurs`.`Pseudo` = :p )
            ORDER BY utilisateurs.Role";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":p" => $pseudo));
		$resultat = $statement->fetchAll();
	} catch (PDOException $e) {
		echo $e;
		return false;
	}
	return $resultat;
}

function displayTeam($Team)
{
	foreach ($Team as $array) {
		foreach ($array as $key => $value) {
			if ($key === "Pseudo") {
				echo "<div class='col-md-3'>
            <span class='fa-stack fa-3x'>
                <i class='fas fa-circle fa-stack-2x text-primary'></i>
                <i class='fas fa-laptop fa-stack-1x fa-inverse'></i></span>
                <input type='checkbox' name='$value' >
				<label for='$value'><h4 class='my-3 text-light'> $value </h4></label>";
			}
			if ($key === "Role") {
				echo "<p class='text-muted'> $value </p>
				</div>";
			}
		}
	}
}

function verfieTeamRegister($nameTournoi, $pseudo)
{
	$sql = "SELECT `participation`.`idEquipe`
	FROM `projet`.`participation`
	WHERE `participation`.idEquipe = (
		SELECT utilisateurs.idEquipe
		FROM projet.utilisateurs
		WHERE utilisateurs.pseudo = :p)
	AND `participation`.idTournoi = (
		SELECT tournoi.idTournoi
		FROM projet.tournoi
		Where tournoi.Nom = :n
	)";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":p" => $pseudo,":n" => $nameTournoi));
		$resultat = $statement->fetchAll();
	} catch (PDOException $e) {
		echo $e;
		return false;
	}
	if (!$resultat) {
		return true;
	}
	else {
		return false;
	}
}

function addTeamParticipation ($nameTournoi, $pseudo){
	$sql = "INSERT INTO `projet`.`participation`
	(`IdEquipe`,
	`IdTournoi`)	
		SELECT utilisateurs.idEquipe, tournoi.idTournoi
		FROM projet.utilisateurs ,projet.tournoi
		WHERE utilisateurs.pseudo = :p and tournoi.Nom = :n	";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":p" => $pseudo,":n" => $nameTournoi));
	} catch (PDOException $e) {
		echo $e;
		return false;
	}
	return true;
}

if (isset($_POST["inscription"])) {
	$bValid = true;
	// Vérification de la case a cocher valider
	if (filter_has_var(INPUT_POST, 'valider')) {
		$valider = filter_input(INPUT_POST, 'valider', FILTER_SANITIZE_STRING);
		if ($valider === false || strlen($valider) == 0)
			$bValid = false;
	} else
		$bValid = false;

	// Est-ce qu'on a rencontré une erreur?
	if (verfieTeamRegister($_GET["nameTournoi"],$_SESSION["pseudo"]) == true) {
		// echo "$name + $minPlayer + $maxPlayer + $price + $jeux + $date" ;
		if (!addTeamParticipation($_GET["nameTournoi"],$_SESSION["pseudo"]) || !$valider) {
			$erreur = "vous devez accepter les conditons d'utilisations";
		} else {
			header("Location: ../index.php");
			exit;
		}
	} else {
		$erreur = 'Vous êtes déjà inscrit a se tournoi';
	}
}


?>

<!DOCTYPE html>
<html>

<head>
	<title>Creative Colorlib SignUp Form</title>
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
		<h1>Inscrivez vous aux tournoi</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="#" method="post">
					<label for="valider">
						<input type="checkbox" name="valider" required>
						Voulez-vous participer a se tournoi.
					</label>
					<?php
					if ($erreur != "") {
                        echo   '<div class="alert alert-danger" role="alert">' . $erreur . '</div>';
                    }
					?>
					<input type="submit"  class="btn btn-primary mb-4 text-black" name="inscription" value="Inscription">
				</form>
			</div>
		</div>
	</div>
</body>

</html>