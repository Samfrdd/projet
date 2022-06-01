<!--
Author: Colorlib
Author URL: https://colorlib.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->

<?php
session_start();
require_once '../db/database.php';



$id = $_GET["nameTournoi"];
function deleteTournoi($id)
{
	$sql = "DELETE FROM `projet`.`tournoi`
    WHERE idTournoi = :id";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":id" => $id));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

function deleteParticipant($id)
{
	$sql = "DELETE FROM `projet`.`participation`
    WHERE idTournoi = :id";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":id" => $id));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}


function checkCreateurTournoi($name, $idTournoi)
{


	$sql = ' SELECT true  from `projet`.`tournoi` where `tournoi`.`Createur` = :n AND  `tournoi`.`idTournoi` = :t  ';
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":n" => $name, ":t" => $idTournoi));
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

if (!checkCreateurTournoi($_SESSION["pseudo"], $id)) {
	if (isset($_POST["delete"])) {
		$bValid = true;
		// Vérification de la case a cocher valider
		if (filter_has_var(INPUT_POST, 'valider')) {
			$valider = filter_input(INPUT_POST, 'valider', FILTER_SANITIZE_STRING);
			if ($valider === false || strlen($valider) == 0)
				$bValid = false;
		} else
			$bValid = false;

		// Est-ce qu'on a rencontré une erreur?
		if ($bValid) {
			// echo "$name + $minPlayer + $maxPlayer + $price + $jeux + $date" ;
			if (deleteParticipant($id) == true && deleteTournoi($id) == true) {
				header("Location: ../index.php");
				exit;
			} else {
				echo "Il y'a eu un probleme avec la supression du tournoi";
			}
		} else {
			echo 'Il y a un probleme avec la supression';
		}
	}
}




?>

<!DOCTYPE html>
<html>

<head>
	<title>Delete TOurnoi</title>
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
	<!-- Custom Theme files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<!-- //Custom Theme files -->
	<!-- web font -->
	<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
	<!-- //web font -->
</head>

<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1>Supprimez votre tournoi </h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="#" method="post">
					<label for="valider" class="text-light">
						<input type="checkbox"  name="valider" required>
						Voulez vous vraiment supprimez votre tournoi ?
					</label>
					<input class="btn btn-warning mb-4 text-black" type="submit" name="delete" value="Supprimer">
				</form>
			</div>
		</div>
	</div>
</body>

</html>