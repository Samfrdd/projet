<?php
require_once './db/database.php';
require_once './function.php';
require_once './Classes/jeux.php';

require_once './Classes/tournoi.php';
session_start();
$ab = "";
$nomTournoi = $_GET["var"];
$connection = "";
if (Isset($_SESSION["pseudo"])) {
    $connection = '<form action="#" method="POST"> <input type="submit" name="submit" value="S\'inscrire"> </form>';
}else{
    $connection = 'Veuillez vous connectez pour pouvoir participer a ce tournoi ! ';
}

function getTournoi($nomTournoi){
    $arr = array();
    
    $sql = "SELECT `tournoi`.`idTournoi`,`tournoi`.`Nom`,`tournoi`.`NbEquipeMin`,`tournoi`.`NbEquipeMax`,`tournoi`.`Prix`,`tournoi`.`DateDebut`,`tournoi`.`IdJeux`FROM `projet`.`tournoi` WHERE tournoi.nom = :n";
    $statement = EDatabase::prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $nomTournoi));
    }
    catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row=$statement->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)){
        // On crée l'objet EClient en l'initialisant avec les données provenant
        // de la base de données
        $c = new ETournoi(intval($row['idTournoi']), $row['Nom'], intval($row['NbEquipeMin']), intval($row['NbEquipeMax']), intval($row['Prix']), $row['DateDebut'], intval($row['IdJeux']));
        // On place l'objet EClient créé dans le tableau
        array_push($arr, $c);
    }        

    // Done
    return $arr;
}

$tournoi = getTournoi($nomTournoi);
if($tournoi == array()){
    echo "Une erreur est survenue";
}

$jeuxTournoi = getAJeux($tournoi[0]->date);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tournoi</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body id="page-top ">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="./index.php">Menu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars ms-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <?php
                    if (Isset($_SESSION["pseudo"])) {
                        echo '<li class="nav-item"><a class="nav-link" href="#">'.$_SESSION["pseudo"].'</a></li>';
                    } else {
                    ?>
                        <li class="nav-item"><a class="nav-link" href="./formulaire/inscripiton.php">Inscription</a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead" style="background-image: url(./assets/img/<?=$tournoi[0]->date?>.jpg)">

    </header>

    <section class="page-section bg-dark" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase text-light"><?=$tournoi[0]->nom ?></h2>
                <h3 class="section-subheading text-muted"><?= $connection?></h3>
            </div>
            <div class="text-light">
                    <h3>Cash prize : <?=$tournoi[0]->prix?> </h3>
                    <h3>Nombre de joueur minimum  : <?=$tournoi[0]->minPlayer?> </h3>
                    <h3>Nombre de joueur Maxmimum  : <?=$tournoi[0]->maxPlayer?> </h3>
                    <h3>Jeux du tournoi  : <?=$jeuxTournoi[0]->nom?> </h3>
                    <h3>Date du tournoi  : <?=$tournoi[0]->jeux?> </h3>
                    
                    

            </div>
        </div>
    </section>
    
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>