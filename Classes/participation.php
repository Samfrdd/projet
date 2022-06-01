<?php

/**
 * Classe container participation
 */
class EParticipant{

    /**
     * ctor 
     *
     * @param string $InEmail l'email de l'utilisateur
     * @param string $InNickname Son nickname
     * $name, $maxPlayer, $minPlayer, $price, $jeux, $date
     */
    public function __construct($INom = "")
    {
        $this->nom = $INom;
    }
    /**
     * @var string Nom de l'équipe
     */
    public $nom;
}