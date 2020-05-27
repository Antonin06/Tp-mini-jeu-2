<?php

class Objet 
{
    private $nom;
    private $couleur;

    public function getCouleur(){
        return $this->couleur;
    }

    public function setCouleur($nomCouleur){
        $this->couleur = $nomCouleur;
    }

}

$toto = new Objet();

echo $toto->getCouleur();