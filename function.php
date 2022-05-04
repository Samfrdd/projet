<?php


// Verifie si un utilisateur est co 
// Retourn false ou un utilisateur
function verificationConnection(){
    if($_SESSION["nom"] == null){
        return false;
    }else{
        return  "classe Utilisateur";
    }
}