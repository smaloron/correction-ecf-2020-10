<?php

try {
    // Connexion à la BD
    $pdo = require "connexion.php";

    // Récupération de la liste des tranches d'âge
    $sql = "SELECT * FROM tranche_age";
    $query = $pdo->query($sql);
    $ageList = $query->fetchAll();

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
            <h1>Nouveau camp</h1>
            <form method="post">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control">
                </div>
                <div class="form-group">
                    <label>Date de début</label>
                    <input type="date" name="date_debut" class="form-control">
                </div>
                <div class="form-group">
                    <label>Date de fin</label>
                    <input type="date" name="date_fin" class="form-control">
                </div>
                <div class="form-group">
                    <label>Nombre de places</label>
                    <input type="number" name="nb_place" class="form-control">
                </div>
                <div class="form-group">
                    <label>Tranche d'âge</label>
                    <select name="tranche_age" class="form-control">
                        <?php foreach ($ageList as $status) : ?>
                            <option value="<?= $status["id"] ?>">
                                <?= $status["libelle"] ?> de
                                <?= $status["age_min"] ?> à <?= $status["age_max"] ?> ans
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary btn-block">
                    Valider
                </button>
            </form>
        </div>
    </div>

</body>

</html>