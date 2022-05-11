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


$allTournoi = getAllTournoi();