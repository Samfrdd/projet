<?php

/**
 * Classe container client
 */
class ETournoi{

    /**
     * ctor 
     *
     * @param string $InEmail l'email de l'utilisateur
     * @param string $InNickname Son nickname
     * $name, $maxPlayer, $minPlayer, $price, $jeux, $date
     */
    public function __construct($INCode = -1,$InName = "", $InMaxPlayer = "", $InMinPlayer = "", $InPrice = "", $InJeux = "", $InDate = "")
    {
        $this->nom = $InName;
        $this->maxPlayer = $InMaxPlayer;
        $this->minPlayer = $InMinPlayer;
        $this->prix = $InPrice;
        $this->jeux = $InJeux;
        $this->date = $InDate;
        $this->code = $INCode;


    }

    /**
     * @var string Email du client
     */
    public $nom;
    /**
     * @var string Nickname du client
     */
    public $maxPlayer;

    public $minPlayer;

    public $prix;

    public $jeux;

    public $date;

    public $code;

}