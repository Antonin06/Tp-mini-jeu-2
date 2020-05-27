<?php

$manager = new PersonnagesManager($db);

// Si la session perso existe, on restaure l'objet.
if (isset($_SESSION['perso'])) {
  $perso = $_SESSION['perso'];
}

// Si on a voulu créer un personnage.
if (isset($_POST['creer']) && isset($_POST['nom'])) {
  // On crée un nouveau personnage.
  $perso = new Personnage(['nom' => $_POST['nom'], 'classe' => $_POST['classe']] ); 
  // Si le nom est invalide (string vide) on revoit une erreur
  if (!$perso->nomValide()) {
    $message = 'Le nom choisi est invalide.';
    unset($perso);
  }
  // Si le nom existe déjà
  elseif ($manager->exists($perso->nom())) {
    $message = 'Le nom du personnage est déjà pris.';
    unset($perso);
  }
  // Sinon on crée un nouveau personnage
  else {
    $manager->add($perso);
  }
}
// Si on a voulu utiliser un personnage.
elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) {
  // Si celui-ci existe.
  if ($manager->exists($_POST['nom'])) {
    $perso = $manager->get($_POST['nom']);
  }
  else {
    $message = 'Ce personnage n\'existe pas !';
  }
}
// Si on a cliqué sur un personnage pour le frapper.
elseif (isset($_GET['frapper'])) {
  // S'il n'y a pas de personnage
  if (!isset($perso)) {
    $message = 'Merci de créer un personnage ou de vous identifier.';
  }

  else {
    if (!$manager->exists((int) $_GET['frapper']))
    {
      $message = 'Le personnage que vous voulez frapper n\'existe pas !';
    }

    else {
      $persoAFrapper = $manager->get((int) $_GET['frapper']);

      // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
      $retour = $perso->frapper($persoAFrapper);

      switch ($retour) {
        case Personnage::CEST_MOI :
          $message = 'Mais... pourquoi voulez-vous vous frapper ???';
          break;

        case Personnage::PERSONNAGE_FRAPPE :
          $message = 'Le personnage a bien été frappé !';

          $manager->update($perso);

          $manager->update($persoAFrapper, $perso->strength());

          break;

        case Personnage::PERSONNAGE_TUE :
          $message = 'Vous avez tué ce personnage !';

          $manager->update($perso);
          $manager->delete($persoAFrapper);

          break;
      }
    }
  }
}
