<!--
Auteur: Sam / Anthony
Description : Formulaire de creation d'une equipe
-->

<?php
session_start();
require_once '../db/database.php';

function verifieTeamExist($Nom)
{
    $sql = "SELECT `equipe`.`Nom` FROM `projet`.`equipe` Where `equipe`.`Nom` = :n";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $Nom));
        $resultat = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
    if ($resultat == "") {
        return true;
    } else {
        return false;
    }
}

function addTeam($Nom)
{
    $sql = "INSERT INTO `projet`.`equipe` (`Nom`) VALUES(:n)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $Nom));
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
    // Done
    return true;
}

function addRole($Nom)
{
    $sql = "UPDATE `projet`.`utilisateurs` SET `Role` = 'Capitaine' WHERE `Pseudo` = :n";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $Nom));
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
    // Done
    return true;
}

function addIdEquipe($Nom)
{
    $sql = "UPDATE `projet`.`utilisateurs` 
            SET `IdEquipe` = (Select `equipe`.`IdEquipe` 
                              From `projet`.`equipe` 
                              where `equipe`.`Nom` = :n)  
            WHERE `Pseudo` = :p";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $Nom,":p" => $_SESSION["pseudo"]));
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
    // Done
    return true;
}

function verifieRole($Nom)
{
    $sql = "SELECT `utilisateurs`.`Role` FROM `projet`.`utilisateurs` Where `utilisateurs`.`Pseudo` = :n";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $Nom));
        $resultat = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
     if ($resultat["Role"] == "") {
         return true;
     } else {
         return false;
     }
}

if (isset($_POST["submit"])) {
    // Vérification du champs Nom
    if (filter_has_var(INPUT_POST, 'Nom')) {
        $Nom = filter_input(INPUT_POST, "Nom", FILTER_SANITIZE_STRING);
    }
    if (verifieRole($_SESSION["pseudo"]) == true ) {
        echo "salut";
        if (verifieTeamExist($Nom) == true) {
            echo "salut";
            addTeam($Nom);
            echo($_SESSION["pseudo"]);
            addRole($_SESSION["pseudo"]);
            addIdEquipe($Nom);
            header("Location: ../index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Creation Team</title>
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

    <!-- //web font -->
</head>

<body>
    <!-- main -->
    <div class="main-w3layouts wrapper">
        <h1>Crée une equipe</h1>
        <div class="main-agileinfo">
            <div class="agileits-top">
                <form action="#" method="post">
                    <input class="text" type="text" name="Nom" placeholder="Nom d'équipe" required="">
                    <input type="submit" class="btn btn-warning mb-4 text-black" name="submit" value="Inscription">
                </form>
                <p><a href="../index.php"> Retour</a></p>
            </div>
        </div>
    </div>
</body>

</html>