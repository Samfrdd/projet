<!--
Author: Sam Freddi
date : 04.05.2022
detail : Formulaire de creation d'un tournoi
-->

<?php
session_start();
require_once '../db/database.php';
require_once '../Classes/tournoi.php';
require_once '../Classes/jeux.php';



$name = "";
$minPlayer = "";
$maxPlayer = "";
$price = "";
$jeux = "";
$date = "";
$nbJoueurEquipe = 0;



$id = $_GET["nameTournoi"];

$tournoi = getTournoi($id);

function getTournoi($nomTournoi)
{
    $sql = "SELECT `tournoi`.`idTournoi`,`tournoi`.`Nom`,`tournoi`.`NbEquipeMax`,`tournoi`.`NbEquipeMin`,`tournoi`.`Prix`,`tournoi`.`DateDebut`,`jeux`.`Nom` AS NomJeux, `tournoi`.`NbJoueurEquipe`, `tournoi`.`Createur`
    FROM `projet`.`tournoi` 
    INNER JOIN jeux ON jeux.idJeux = tournoi.idJeux
    WHERE tournoi.nom = :n";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $nomTournoi));
    } catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet EClient en l'initialisant avec les données provenant
        // de la base de données
        $c = new ETournoi(intval($row['idTournoi']), $row['Nom'], intval($row['NbEquipeMax']), intval($row['NbEquipeMin']), intval($row['NbJoueurEquipe']), intval($row['Prix']), $row['DateDebut'], $row['NomJeux'], $row['Createur']);
        // On place l'objet EClient créé dans le tableau
    }

    // Done
    return $c;
}

function getIdJeux($nomJeux){

}

function getJeux()
{
    $arr = array();

    $sql = "SELECT `jeux`.`IdJeux`,`jeux`.`Nom`FROM `projet`.`jeux`";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array());
    } catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet EClient en l'initialisant avec les données provenant
        // de la base de données
        $c = new EJeux(intval($row['IdJeux']), $row['Nom']);
        // On place l'objet EClient créé dans le tableau
        array_push($arr, $c);
    }

    // Done
    return $arr;
}

$allJeux = getJeux();

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

if (!checkCreateurTournoi($_SESSION["pseudo"], $tournoi->code)) {
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
                // || floatval($maxPlayer) % 2 != 0
                $bValid = false;
        } else {
            $bValid = false;
        }


        // Vérification du champs quantité
        if (filter_has_var(INPUT_POST, 'minPlayer')) {
            $minPlayer = filter_input(INPUT_POST, 'minPlayer', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
            if ($minPlayer === false || floatval($minPlayer) == 0 || $minPlayer > $maxPlayer || floatval($minPlayer) % 2 != 0)
                $bValid = false;
        } else {
            $bValid = false;
        }

        // Vérification du champs quantité
        if (filter_has_var(INPUT_POST, 'nbJoueurEquipe')) {
            $nbJoueurEquipe = filter_input(INPUT_POST, 'nbJoueurEquipe', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
            if ($nbJoueurEquipe === false || floatval($nbJoueurEquipe) == 0 || floatval($nbJoueurEquipe) > 5)
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
            if ($date === false || $date < date("m.d.y"))
                $bValid = false;
        } else {
            $bValid = false;
        }

        // Est-ce qu'on a rencontré une erreur?
        if ($bValid && verifyTournoiExist($name) == true) {
            // echo "$name + $minPlayer + $maxPlayer + $price + $jeux + $date" ;
            if (!modifTournoi($name, floatval($maxPlayer), floatval($minPlayer), floatval($nbJoueurEquipe), floatval($price), $jeux, $date, $_SESSION["pseudo"], intval($tournoi->code))) {
                echo "asda";
            } else {
                header("Location: ../index.php");
                exit;
            }
        } else {
            echo 'Ce nom de tournoi existe deja';
        }
    }
}

function modifTournoi($name, $maxPlayer, $minPlayer, $nbJoueurEquipe, $price, $jeux, $date, $createur, $idTournoi)
{
    $sql = "UPDATE `projet`.`tournoi`
    SET
    `idTournoi` = :id,
    `Nom` = :n,
    `NbEquipeMin` = :mi,
    `NbEquipeMax` =:ma,
    `Prix` = :p,
    `DateDebut` = :d,
    `IdJeux` = :j,
    `NbJoueurEquipe` = :nb,
    `Createur` = :c
    WHERE `idTournoi` = :id;";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":id" => $idTournoi,":n" => $name, ":ma" => $maxPlayer, ":mi" => $minPlayer, ":nb" => $nbJoueurEquipe, ":p" => $price, ":j" => $jeux, ":d" => $date, ":c" => $createur));
    } catch (PDOException $e) {
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
    <title>Modification Tournoi</title>
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
        <h1>Modifier votre tournoi</h1>
        <div class="main-agileinfo">
            <div class="agileits-top">
                <form action="#" method="post">
                    <label class="text-light">Nom :</label>
                    <input class="text mb-4" type="text" name="nomTournoi" value="<?= $tournoi->nom ?>" placeholder="Nom" required=""></input>

                    <label class="text-light">Maximum de joueur :</label>
                    <input class="text mb-4" type="text" name="maxPlayer" value="<?= $tournoi->maxPlayer ?>" placeholder="Nombre de joueur Maximum" required=""></input>

                    <label class="text-light">Minimum de joueur :</label>
                    <input class="text mb-4" type="text" name="minPlayer" value="<?= $tournoi->minPlayer ?>" placeholder="Nombre de joueur Minimum" required=""></input>

                    <label class="text-light">Nombre de joueur par équipe :</label>
                    <input class="text mb-4" type="text" name="nbJoueurEquipe" value="<?= $tournoi->nbJoueurEquipe ?>" placeholder="Nombre de joueur par équipe" required=""></input>

                    <label class="text-light">Prix :</label>
                    <input class="text mb-4" type="text" name="Price" value="<?= $tournoi->prix ?>" placeholder="Récompence du tournoi en CHF" required=""></input>
                    <!-- <input class="text mb-4" type="text" name="jeux" value="" placeholder="Jeux du tournoi" required=""> -->
                    <label class="text-light">Jeux :</label>
                    <select name="jeux" class="mb-4 bg-dark text-light">
                        <?php
                        foreach ($allJeux as $jeu) {
                            echo '<option class="text-light bg-dark" value="' . $jeu->code . '">' . $jeu->nom . '</option>';
                        }
                        ?>
                    </select>
                    <br>

                    <label class="text-light">Date :</label>
                    <input class="text bg-dark mb-4 text-light center" type="date" value="<?= $tournoi->date ?>" name="date" placeholder="Date du tournoi" required=""></input>
                    <input type="submit" name="submit" value="Valider"></input>
                </form>
                <p> <a href="../index.php">Retour</a></p>
            </div>
        </div>
    </div>
</body>

</html>