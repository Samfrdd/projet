<?php

require_once './db/database.php';
require_once './Classes/tournoi.php';
function getAllTournoi()
{ {
        $arr = array();

        $sql = "SELECT `tournoi`.`idTournoi`,`tournoi`.`Nom`,`tournoi`.`NbEquipeMin`,`tournoi`.`NbEquipeMax`,`tournoi`.`Prix`,`tournoi`.`DateDebut`,`jeux`.`Nom` AS NomJeux, `tournoi`.`NbJoueurEquipe`
        FROM `projet`.`tournoi` 
        INNER JOIN jeux ON jeux.idJeux = tournoi.idJeux";
        $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        try {
            $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
        // On parcoure les enregistrements 
        while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            // On crée l'objet EClient en l'initialisant avec les données provenant
            // de la base de données
            $c = new ETournoi(intval($row['idTournoi']), $row['Nom'], intval($row['NbEquipeMin']), intval($row['NbEquipeMax']), intval($row['NbJoueurEquipe']), intval($row['Prix']), $row['DateDebut'], $row['NomJeux']);
            // On place l'objet EClient créé dans le tableau
            array_push($arr, $c);
        }

        // Done
        return $arr;
    }
}

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

function addInvitation($pseudo)
{
    $sql = "INSERT INTO `projet`.`invitation` (`idUtilisateur`,`idEquipe`,`date`)
    SELECT :p , idEquipe, NOW()
    From projet.utilisateurs
    where Pseudo = :n;";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":p" => $pseudo, ":n" => $_SESSION["pseudo"]));
    } catch (PDOException $e) {
        return false;
    }
}

$allTournoi = getAllTournoi();

function getTournoi($nomTournoi)
{
    $sql = "SELECT `tournoi`.`idTournoi`,`tournoi`.`Nom`,`tournoi`.`NbEquipeMin`,`tournoi`.`NbEquipeMax`,`tournoi`.`Prix`,`tournoi`.`DateDebut`,`jeux`.`Nom` AS NomJeux, `tournoi`.`NbJoueurEquipe`
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
        $c = new ETournoi(intval($row['idTournoi']), $row['Nom'], intval($row['NbEquipeMin']), intval($row['NbEquipeMax']), intval($row['NbJoueurEquipe']), intval($row['Prix']), $row['DateDebut'], $row['NomJeux']);
        // On place l'objet EClient créé dans le tableau
    }

    // Done
    return $c;
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
                <h4 class='my-3 text-light'> $value </h4>";
            }
            if ($key === "Role") {
                echo "<p class='text-muted'> $value </p> 
            </div>";
            }
        }
    }
}


function verifieTeam($pseudo)
{
    $sql = "SELECT `equipe`.`Nom`
            FROM `projet`.`equipe`
            WHERE `equipe`.idEquipe = (
                SELECT `utilisateurs`.`IdEquipe`
                FROM `projet`.`utilisateurs`
                WHERE `utilisateurs`.`Pseudo` = :p )";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":p" => $pseudo));
        $resultat = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
    if ($resultat) {
        return $resultat["Nom"];
    } else {
        return false;
    }
}

function nbTeamRegister($tournoi)
{
    $sql = "SELECT count(`participation`.`IdTournoi`) AS nbTeam
    FROM `projet`.`participation`
    WHERE `participation`.`IdTournoi` = (
        SELECT tournoi.idTournoi
        FROM projet.tournoi
        WHERE tournoi.Nom = :t)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":t" => $tournoi));
        $resultat = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
    if ($resultat) {
        return $resultat["nbTeam"];
    } else {
        return false;
    }
}

function verifieIsCaptaine($pseudo)
{
    $sql = "SELECT `utilisateurs`.`Role`
    FROM `projet`.`utilisateurs`
    WHERE `utilisateurs`.pseudo = :p And `utilisateurs`.`Role` = 'Capitaine'";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":p" => $pseudo));
        $resultat = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
    } catch (PDOException $e) {
        echo $e;
        return false;
    }
    if ($resultat) {
        return true;
    } else {
        return false;
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

function tournoiDateExpired (){
    $sql = "DELETE FROM `projet`.`tournoi`
            WHERE `tournoi`.`dateDebut` < now()";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		echo $e;
		return false;
	}
	return true;
}
