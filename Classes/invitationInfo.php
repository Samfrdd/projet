<?php

/**
 * Classe container invitation
 */
class EInvitationInfo{

    /**
     * ctor 
     *
     */
    public function __construct($INom = "")
    {
        $this->nom = $INom;
    }
    /**
     * @var string Nom 
     */
    public $nom;
}