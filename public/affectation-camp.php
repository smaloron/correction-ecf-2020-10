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
    $sql = "SELECT * FROM vue_enfants 
            LEFT JOIN affectation_camps ON
                id_camp = ? AND id_participant = vue_enfants.id 
            WHERE id_tranche_age= ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$camp["id_tranche_age"], $idCamp]);
    $children = $statement->fetchAll();

    // Liste des encadrants qui peuvent participer au camp
    $sql = "SELECT * FROM vue_animateurs 
            LEFT JOIN affectation_camps ON
                id_camp = ? AND id_participant = vue_animateurs.id";
    $statement = $pdo->prepare($sql);
    $statement->execute([$idCamp]);
    $leaders = $statement->fetchAll();

    // Traitement des données
    $isPosted = count($_POST) > 0;
    if ($isPosted) {

        if (filter_has_var(INPUT_POST, "childrenSubmit")) {
            $selectedChildren = filter_input(INPUT_POST, "children", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

            // Suppression des affectations
            $sql = "DELETE FROM affectation_camps 
            WHERE id_camp= ? AND id_participant IN 
            (SELECT id FROM personnes WHERE id_statut=1)";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idCamp]);


            $sql = "INSERT INTO affectation_camps (id_camp, id_participant) 
                VALUES (?,?)";
            $statement = $pdo->prepare($sql);

            for ($i = 0; $i < count($selectedChildren); $i++) {
                $statement->execute([$idCamp, $selectedChildren[$i]]);
            }
        }

        if (filter_has_var(INPUT_POST, "packLeaderSubmit")) {
            $selectedLeader = filter_input(INPUT_POST, "leaders", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

            // Suppression des affectations
            $sql = "DELETE FROM affectation_camps 
            WHERE id_camp= ? AND id_participant IN 
            (SELECT id FROM personnes WHERE id_statut=2)";
            $statement = $pdo->prepare($sql);
            $statement->execute([$idCamp]);


            $sql = "INSERT INTO affectation_camps (id_camp, id_participant) 
                VALUES (?,?)";
            $statement = $pdo->prepare($sql);

            for ($i = 0; $i < count($selectedLeader); $i++) {
                $statement->execute([$idCamp, $selectedLeader[$i]]);
            }
        }



        header("location:/affectation-camp.php?id=$idCamp");
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

            <div class="row">
                <div class="col-md-6">
                    <form method="post">
                        <table>
                            <?php foreach ($children as $child) : ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" value="<?= $child["id"] ?>" name="children[]" <?= $child["id_camp"] == null ? "" : "checked" ?>>
                                    </td>
                                    <td>
                                        <?= $child["prenom"] ?> <?= $child["nom"] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </table>

                        <button type="submit" name="childrenSubmit">
                            Valider
                        </button>
                    </form>
                </div>

                <div class="col-md-6">
                    <form method="post">
                        <table>
                            <?php foreach ($leaders as $leader) : ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" value="<?= $leader["id"] ?>" name="leaders[]" <?= $leader["id_camp"] == null ? "" : "checked" ?>>
                                    </td>
                                    <td>
                                        <?= $leader["prenom"] ?> <?= $leader["nom"] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </table>

                        <button type="submit" name="packLeaderSubmit">
                            Valider
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

</body>

</html>