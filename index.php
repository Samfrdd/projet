<?php

/**
 * Module 151
 * @author Sam Freddi / Anthony Puchol
 * @copyright Sam Freddi
 * @version 1.0.0
 */

require_once './function.php';
session_start();

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
    <link href="./formulaire/css/style.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body id="page-top ">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top">Tournois</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars ms-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#services">Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="#portfolio">Tournoi</a></li>
                    <?php
                    if (Isset($_SESSION["pseudo"])) {
                        echo '<li class="nav-item"><a class="nav-link" href="#">'.$_SESSION["pseudo"].'</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="./deconnexion.php">Deconnexion</a></li>';

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
    <header class="masthead" >

    </header>
    <!-- Services-->
    <section class="page-section bg-dark" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase text-light">Team</h2>
                <h3 class="section-subheading text-muted">Rejoint une equipe !</h3>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3 text-light">Equipe 1</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3 text-light">Equipe 2</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3 text-light">Equipe 3</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Portfolio Grid-->
    <section class="page-section bg-dark" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase text-light">Tournoi</h2>
                <h3 class="section-subheading text-muted mb-4">Vous pouvez créez ou rejoindre des tournoi ici !</h3>
                <?php
                    if ($_SESSION["pseudo"] != "") {
                       echo  '<button type="button" class="btn btn-primary mb-4"> <a class="nav-link text-black" href="./formulaire/creationTournoi.php">Créez un tournoi</a></button>';
                    } else {
                    }
                    ?>
                
            </div>
            <div class="row" style="">
                <?php
                foreach ($allTournoi as $tournoi) {

                    echo '<div class="col-lg-4 col-sm-6 mb-4">';
                    echo '<div class="portfolio-item">';
                    echo ' <a class="portfolio-link" data-bs-toggle="modal" href="./tournoi.php?var=' . $tournoi->nom . '">';
                    echo '<div class="portfolio-hover">';
                    echo '<div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>';
                    echo '</div>';
                    echo  '<img class="img-fluid" src="assets/img/' . $tournoi->date . '.jpg" alt="..." />';
                    echo '</a>';
                    echo  '<div class="portfolio-caption">';
                    echo '<div class="portfolio-caption-heading">' . $tournoi->nom . '</div>';
                    echo '<div class="portfolio-caption-subheading text-muted">Cash prize : ' . $tournoi->prix . ' CHF</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
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