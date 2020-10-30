<?php
try {
    // Connexion à la BD
    $pdo = require "connexion.php";

    // Récupération des infos du camp
    $sql = "SELECT * FROM camps";
    $query = $pdo->query($sql);
    $campList = $query->fetchAll();

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

<body>
    <ul>
        <li>
            <a href="formulaire-membre.php">
                Nouveau membre (enfant ou animateur)
            </a>
        </li>
        <li>
            <a href="formulaire-camp.php">
                Nouveau camp
            </a>
        </li>
    </ul>

    <h2>Liste des camps</h2>
    <ul>
        <?php foreach($campList as $camp): ?>
            <li>
                <a href="/affectation-camp.php?id=<?= $camp["id"]?>">
                    <?= $camp["nom"]?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>

</body>

</html>