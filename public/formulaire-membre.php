<?php

try {
    // Connexion à la BD
    $pdo = require "connexion.php";

    // Récupération de la liste des statuts
    $sql = "SELECT * FROM statuts";
    $query = $pdo->query($sql);
    $statusList = $query->fetchAll();

    // Traitement des données
    $isPosted = count($_POST) > 0;
    if($isPosted){
        // récupération de la saisie
        $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_STRING);
        $dateNaissance = filter_input(INPUT_POST, "date_naissance", FILTER_SANITIZE_NUMBER_INT);
        $statut = filter_input(INPUT_POST, "statut", FILTER_SANITIZE_NUMBER_INT);

        $sql = "INSERT INTO personnes (prenom, nom, date_naissance, id_statut)
                VALUES (?,?,?,?)";
        $statement = $pdo->prepare($sql);
        $statement->execute([$prenom, $nom, $dateNaissance, $statut]);

        header("location:/index.php");

    }
    

} catch(PDOException $ex){
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
            <h1>Nouveau membre</h1>
            <form method="post">
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="form-control">
                </div>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control">
                </div>
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control">
                </div>
                <div class="form-group">
                    <label>Statut</label>
                    <select name="statut" class="form-control">
                        <?php foreach($statusList as $status): ?>
                            <option value="<?= $status["id"] ?>">
                                <?= $status["libelle"] ?>
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