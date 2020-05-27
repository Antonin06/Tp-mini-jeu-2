<?php

class Personnage
{
  private $degats,
          $id,
          $nom,
          $xp ,
          $level ,
          $strength,
          $classe;

  const CEST_MOI = 1; // Constante renvoyée par la méthode `frapper` si on se frappe soi-même.
  const PERSONNAGE_TUE = 2; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant.
  const PERSONNAGE_FRAPPE = 3; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage.


  public function __construct(array $donnees)
  {
    $this->hydrate($donnees);
  }

  public function frapper(Personnage $perso)
  {
    if($perso->id() == $this->id)
    {
      return self::CEST_MOI;
    }

    $this->xp += 25;
    // On indique au personnage qu'il doit recevoir des dégâts.
    // Puis on retourne la valeur renvoyée par la méthode : self::PERSONNAGE_TUE ou self::PERSONNAGE_FRAPPE
    return $perso->recevoirDegats();
  }

  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);

      if(method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }

  public function recevoirDegats()
  {
    $this->degats +=5 ;

    // Si on a 100 de dégâts ou plus, on dit que le personnage a été tué.
    if($this->degats >= 100)
    {
      return self::PERSONNAGE_TUE;
    }

    // Sinon, on se contente de dire que le personnage a bien été frappé.
    return self::PERSONNAGE_FRAPPE;
  }


  // GETTERS //
  public function degats()
  {
    return $this->degats;
  }

  public function id()
  {
    return $this->id;
  }

  public function nom()
  {
    return $this->nom;
  }

  public function xp()
  {
    return $this->xp;
  }

  public function level()
  {
    return $this->level;
  }

  public function strength()
  {
    return $this->strength;
  }

  public function classe()
  {
    return $this->classe;
  }

  public function setDegats($degats)
  {
    $degats = (int) $degats;

    if($degats >= 0 && $degats <= 100)
    {
      $this->degats = $degats;
    }
  }

  public function setId($id)
  {
    $id = (int) $id;

    if($id > 0)
    {
      $this->id = $id;
    }
  }

  public function setNom($nom)
  {
    if(is_string($nom))
    {
      $this->nom = $nom;
    }
  }
  public function nomValide()
  {
      if($this->nom != "") {
          return true;
      }
  }

  public function setXp($xp)
  {
    $this->xp = $xp;
  }

  public function setLevel($level)
  {
    $this->level = $this->level + $level;

  }

  public function setStrength($strength)
  {
    $this->strength =  $strength;

  }

  public function setClasse($classe)
  {
    $this->classe = $classe;
  }
}
