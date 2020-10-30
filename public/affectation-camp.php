<?php

try {
    // Connexion à la BD
    $pdo = require "connexion.php";

    // Récupération de l'id du camp
    $idCamp = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    // Récupération des infos du camp
    $sql = "SELECT * FROM camps WHERE id= ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$idCamp]);
    $camp = $statement->fetch();

    // Liste des enfants qui peuvent participer au camp
    $sql = "SELECT * FROM vue_enfants WHERE id_tranche_age= ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$camp["id_tranche_age"]]);
    $children = $statement->fetchAll();


    // Traitement des données
    $isPosted = count($_POST) > 0;
    if ($isPosted) {
        // récupération de la saisie
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING);
        $nbPlaces = filter_input(INPUT_POST, "nb_place", FILTER_SANITIZE_NUMBER_INT);
        $dateDebut = filter_input(INPUT_POST, "date_debut", FILTER_SANITIZE_NUMBER_INT);
        $dateFin = filter_input(INPUT_POST, "date_fin", FILTER_SANITIZE_NUMBER_INT);
        $trancheAge = filter_input(INPUT_POST, "tranche_age", FILTER_SANITIZE_NUMBER_INT);

        $sql = "INSERT INTO camps (nom, date_debut, date_fin, nb_place,id_tranche_age)
                VALUES (?,?,?,?,?)";
        $statement = $pdo->prepare($sql);
        $statement->execute([$nom, $dateDebut, $dateFin, $nbPlaces, $trancheAge]);

        header("location:/index.php");
    }
} catch (PDOException $ex) {
    echo $ex->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Scouts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1>Affectation au camp <?= $camp["nom"] ?></h1>

            <div class="col-md-6">
                <table>
                    <?php foreach ($children as $child) : ?>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>
                                <?= $child["prenom"] ?> <?= $child["nom"] ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>

        </div>
    </div>

</body>

</html>