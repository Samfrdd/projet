<!--
Author: Sam Freddi
date : 04.05.2022
detail : Formulaire de creation d'un tournoi
-->

<?php
session_start();
require_once '../db/database.php';
require_once '../Classes/tournoi.php';


$name = "";
$minPlayer = "";
$maxPlayer = "";
$price = "";
$jeux = "";
$date = "";


if (isset($_POST['submit'])) {
    $bValid = true;

    // Vérification du champs nom
    if (filter_has_var(INPUT_POST, 'nomTournoi')) {
        $name = filter_input(INPUT_POST, 'nomTournoi', FILTER_SANITIZE_STRING);
        if ($name === false || strlen($name) == 0)
            $bValid = false;
    } else
        $bValid = false;

    // Vérification du champs quantité
    if (filter_has_var(INPUT_POST, 'maxPlayer')) {
        $maxPlayer = filter_input(INPUT_POST, 'maxPlayer', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
        if ($maxPlayer === false || floatval($maxPlayer) == 0)
            $bValid = false;
    } else {
        $bValid = false;
    }


    // Vérification du champs quantité
    if (filter_has_var(INPUT_POST, 'minPlayer')) {
        $minPlayer = filter_input(INPUT_POST, 'minPlayer', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
        if ($minPlayer === false || floatval($minPlayer) == 0)
            $bValid = false;
    } else {
        $bValid = false;
    }

    // Vérification du champs prix
    if (filter_has_var(INPUT_POST, 'Price')) {
        $price = filter_input(INPUT_POST, 'Price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
        if ($price === false || floatval($price) == 0)
            $bValid = false;
    } else {
        $bValid = false;
    }

    // Vérification du champs quantité
    if (filter_has_var(INPUT_POST, 'jeux')) {
        $jeux = filter_input(INPUT_POST, 'jeux', FILTER_SANITIZE_STRING);
        if ($jeux === false || strlen($jeux) == 0)
            $bValid = false;
    } else {
        $bValid = false;
    }

    // Vérification du champs quantité
    if (filter_has_var(INPUT_POST, 'date')) {
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        if ($date === false || $date > date("D:M:Y"))
            $bValid = false;
    } else {
        $bValid = false;
    } 

    // Est-ce qu'on a rencontré une erreur?
    if ($bValid && verifyTournoiExist($name) == true) {
       // echo "$name + $minPlayer + $maxPlayer + $price + $jeux + $date" ;
        if (!addTournoi($name, floatval($maxPlayer), floatval($minPlayer), floatval($price), $jeux, $date)){
            echo "asda";
        }
           
    } else {
        echo 'Ce nom de tournoi existe deja';
    }
}


function addTournoi($name, $maxPlayer, $minPlayer, $price, $jeux, $date){
    $sql = "INSERT INTO projet.tournoi ( `Nom`, `NbEquipeMin`, `NbEquipeMax`, `Prix`, `DateDebut`, `IdJeux`)VALUES(:n,:ma,:mi,:p,:d,(SELECT `jeux`.`idJeux` FROM projet.jeux Where jeux.nom = :j ))";
    $statement = EDatabase::prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":n" => $name, ":ma" => $maxPlayer, ":mi" => $minPlayer, ":p" => $price, ":j" => $jeux, ":d" => $date));
	}
	catch (PDOException $e) {
        echo $e;
		return false;
	}
	// Done
	return true;
}

verifyTournoiExist($name);
function verifyTournoiExist($name)
{
    $sql = "SELECT `tournoi`.`Nom` FROM `projet`.`tournoi` Where `tournoi`.`Nom` = '$name'";
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





// foreach ($allTournoi as $key => $tournoi) {
//     echo $tournoi->nom;
//     echo "<br>";
// }

?>
<!DOCTYPE html>
<html>

<head>
    <title>Creation Tournoi</title>
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
    <link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
    <!-- //Custom Theme files -->
    <!-- web font -->
    <!-- //web font -->
</head>

<body>
    <!-- main -->
    <div class="main-w3layouts wrapper">
        <h1>Crée un Tournoi</h1>
        <div class="main-agileinfo">
            <div class="agileits-top">
                <form action="#" method="post">
                    Nom : <input class="text mb-4" type="text" name="nomTournoi" value="<?= $name ?>" placeholder="Nom" required="">
                    <input class="text mb-4" type="text" name="maxPlayer"  value="<?= $maxPlayer ?>"placeholder="Nombre de joueur Maximum" required="">
                    <input class="text mb-4" type="text" name="minPlayer"  value="<?= $minPlayer ?>" placeholder="Nombre de joueur Minimum" required="">
                    <input class="text mb-4" type="text" name="Price"  value="<?= $price ?>" placeholder="Récompence du tournoi en CHF" required="">
                    <input class="text mb-4" type="text" name="jeux"  value="<?= $jeux ?>" placeholder="Jeux du tournoi" required="">
                    <input class="text bg-dark mb-4 center" type="date"  value="<?= $date ?>" name="date" placeholder="Date du tournoi" required="">
                    <input type="submit" name="submit" value="Valider">
                </form>
                <p> <a href="../index.php">Retour</a></p>
            </div>
        </div>
    </div>
</body>

</html>