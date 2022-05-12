<?php

/**
 * Classe container client
 */
class EJeux{

    /**
     * ctor 
     *
     * @param string $InEmail l'email de l'utilisateur
     * @param string $InNickname Son nickname
     * $name, $maxPlayer, $minPlayer, $price, $jeux, $date
     */
    public function __construct($INCode = -1,$InName = "")
    {
        $this->nom = $InName;
        $this->code = $INCode;


    }

    /**
     * @var string Email du client
     */
    public $nom;
    /**
     * @var string Nickname du client
     */
    public $id;


}