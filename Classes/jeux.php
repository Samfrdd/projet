<?php

/**
 * Classe container jeux
 */
class EJeux{

    /**
     * ctor 
     *

     */
    public function __construct($INCode = -1,$InName = "")
    {
        $this->nom = $InName;
        $this->code = $INCode;


    }

    /**
     * @var string Nom
     */
    public $nom;
    /**
     * @var string Id
     */
    public $id;


}