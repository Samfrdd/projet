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
    $connection = '<form action="./formulaire/inscriptionTournoi.php" method="POST"> <input type="submit" name="submit" value="S\'inscrire"> </form>';
}else{
    $connection = 'Veuillez vous connectez pour pouvoir participer a ce tournoi ! ';
}



$tournoi = getTournoi($nomTournoi);
if($tournoi == array()){
    echo "Une erreur est survenue";
}

$jeuxTournoi = getAJeux($tournoi->jeux);

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
    <header class="masthead" style="background-image: url(./assets/img/<?=$tournoi->jeux?>.jpg)">

    </header>

    <section class="page-section bg-dark" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase text-light"><?=$tournoi->nom ?></h2>
                <h3 class="section-subheading text-muted"><?= $connection?></h3>
            </div>
            <div class="text-light">
                    <h3>Cash prize : <?=$tournoi->prix?> </h3>
                    <h3>Nombre d'équipe minimum  : <?=$tournoi->minPlayer?> </h3>
                    <h3>Nombre d'équipe Maximum  : <?=$tournoi->maxPlayer?> </h3>
                    <h3>Nombre de joueur par équipe : <?=$tournoi->nbJoueurEquipe?> </h3>
                    <h3>Jeux du tournoi  : <?=$jeuxTournoi->nom?> </h3>
                    <h3>Date du tournoi  : <?=$tournoi->date?> </h3>
                    
                    

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