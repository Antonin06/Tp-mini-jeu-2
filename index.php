<?php

// On enregistre notre autoload.
/* function chargerClasse($classname) {
require $classname.'.php';
}

spl_autoload_register('chargerClasse');*/




// On fait appel à la classe Personnage
require 'class/Personnage.php';
// On fait appel à la classe PersonnagesManager
require 'class/PersonnagesManager.php';

session_start(); // On appelle session_start()

if (isset($_GET['deconnexion'])) {
  session_destroy();
  header('Location: .');
  exit();
}

// On fait appel à la connexion à la bdd
require 'config/init.php';

// On fait appel à le code métier
require 'combat.php';

function getClasseColor($persoClasse){
  switch($persoClasse){
    case "Mage":
      $color="blue";
        break;

    case "Hunter":
      $color="green";
        break;

    case "Warrior":
      $color="red";
        break;

  }
  return $color;
}


?>
<!DOCTYPE html>
<html>
<head>
  <title>🥋Vs🥋 Fight ! </title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
  integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <meta charset="utf-8" />
</head>
<body>
  <p class="text-center">Nombre de personnages créés : <?= $manager->count() ?></p>
  <?php
  // On a un message à afficher ?
  if (isset($message)) {
    echo '<b>', $message, '</b>'; // Si oui, on l'affiche.
  }
  // Si on utilise un personnage (nouveau ou pas).
  if (isset($perso)) {
    ?>
    <p class="text-center"><a href="?deconnexion=1">Déconnexion</a></p>

    <fieldset class="text-center">
      <legend>Mes informations</legend>
      <p>
        Nom : <?= htmlspecialchars($perso->nom()) ?><br />
        Dégâts : <?= $perso->degats() ?><br />
        Level : <?= $perso->level() ?><br />
        Force : <?= $perso->strength() ?><br />
        Classe : <?= $perso->classe() ?><br />

      </p>
    </fieldset>
    <hr>
    <fieldset>
      <legend class="text-center">Qui frapper ?</legend>
      <p>
        <?php
        $persos = $manager->getList($perso->nom());
        if (empty($persos)) {
          echo 'Personne à frapper !';
        }
        else { ?>
          <div class="container mt-4">
            <div class="row">
              <?php foreach ($persos as $unPerso)
              {
                ?>
                <div class="col-6 mb-4">
                  <div class="card shadow" style="background-color:<?php echo getClasseColor($unPerso->classe()) ?>">
                    <div class="card-body text-center">
                      <?php echo '<a href="?frapper=', $unPerso->id(), '">',
                      htmlspecialchars($unPerso->nom()),
                      '</a>' ?>
                      <ul class="list-group">
                        <li class="list-group-item">Dégats : <?php echo $unPerso->degats() ?></li>
                        <li class="list-group-item">Level : <?php echo htmlspecialchars($unPerso->level()) ?></li>
                        <li class="list-group-item">Force : <?php echo htmlspecialchars($unPerso->strength()) ?></li>
                        <li class="list-group-item">Classe : <?php echo htmlspecialchars($unPerso->classe()) ?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <?php
              }
            }
            ?>
          </div>
        </div>
      </p>
    </fieldset>
    <?php
  }
  // Sinon on affiche le formulaire de création de personnage
  else {
    ?>
    <div class="container mt-4 w-25">
      <form action="" method="post">
        <p>
          Nom : <input class="form-control" type="text" name="nom" maxlength="50" /><br/>
          Classe : <select class="custom-select mb-3" name="classe">
            <option value="Warrior">Warrior</option>
            <option value="Hunter">Hunter</option>
            <option value="Mage">Mage</option>
          </select><br/>
          <input type="submit" class="btn btn-success" value="Créer ce personnage" name="creer" />
          <input type="submit" class="btn btn-warning" value="Utiliser ce personnage" name="utiliser" />
        </p>
      </form>
    </div>

  <?php } ?>


</body>
</html>
<?php
// Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
if (isset($perso)) {
  $_SESSION['perso'] = $perso;
}
