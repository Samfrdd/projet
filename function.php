<?php

require_once './db/database.php';
require_once './Classes/tournoi.php';
function getAllTournoi(){
    {
        $arr = array();
    
        $sql = "SELECT `tournoi`.`idTournoi`,`tournoi`.`Nom`,`tournoi`.`NbEquipeMin`,`tournoi`.`NbEquipeMax`,`tournoi`.`Prix`,`tournoi`.`DateDebut`,`tournoi`.`IdJeux`FROM `projet`.`tournoi`";
        $statement = EDatabase::prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        try {
            $statement->execute();
        }
        catch (PDOException $e) {
            return false;
        }
        // On parcoure les enregistrements 
        while ($row=$statement->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)){
            // On crée l'objet EClient en l'initialisant avec les données provenant
            // de la base de données
            $c = new ETournoi(intval($row['idTournoi']), $row['Nom'], intval($row['NbEquipeMin']), intval($row['NbEquipeMax']), intval($row['Prix']), $row['DateDebut'], intval($row['IdJeux']));
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
		$sql=$statement->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT);
	} catch (PDOException $e) {
		return false;
	}
	if ($sql == "") {
		return true;
	}
	else {
		return false;
	}
}

function addInvitation($pseudo){
    $sql = "INSERT INTO `projet`.`invitation` (`idUtilisateur`,`idEquipe`,`date`)
    SELECT :p , idEquipe, NOW()
    From projet.utilisateurs
    where Pseudo = :n;";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":p"=> $pseudo,":n" => $_SESSION["pseudo"]));
	} catch (PDOException $e) {
		return false;
	}
}

$allTournoi = getAllTournoi();

function getAJeux($id)
{
    $arr = array();

    $sql = "SELECT `jeux`.`IdJeux`,`jeux`.`Nom`FROM `projet`.`jeux` WHERE IdJeux = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":i" => $id));
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