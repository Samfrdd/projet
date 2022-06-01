<?php

/**
 * @author Sam Freddi / Anthony Puchol
 * @copyright Sam Freddi
 * @version 1.0.0
 */
$erreurInvit = "";

require_once './function.php';
session_start();
$tournoisExpired = getTournoiDateExpired();
if ($tournoisExpired) {
    foreach ($tournoisExpired as $key => $id) {
        deleteParticipant($id["0"]);
    }
    deleteTournoiDateExpired();
}
$allTournoi = getAllTournoi();

if (isset($_POST["submit"])) {
    // Vérification du champs palyer
    if (filter_has_var(INPUT_POST, 'searchPlayer')) {
        $pseudo = filter_input(INPUT_POST, "searchPlayer", FILTER_SANITIZE_STRING);
    }
    //$search = searchBar($pseudo);
    $pseudoExist = verifiePseudoExist($pseudo);
    if (!$pseudoExist) {
        if ($pseudo != $_SESSION["pseudo"]) {
            addInvitation($pseudo);
        }
    } else {
        $erreurInvit = "Ce pseudo n'existe pas";
    }
}

if (isset($_POST["invAccept"])) {
    if (filter_has_var(INPUT_POST, 'nomEquipe')) {
        $equipe = filter_input(INPUT_POST, "nomEquipe", FILTER_SANITIZE_STRING);
    }
    if (addEquipe($equipe, $_SESSION["pseudo"]) == false) {
        echo "Un probleme est survenue";
    } else {
        if (addRoleJoueur($_SESSION["pseudo"]) == false) {
            echo "Un probleme est survenue";
        } else {
            refuseInvitation($equipe, $_SESSION["pseudo"]);
        }
    }
    // acceptInvation($equipe, $_SESSION["pseudo"]);
}

if (isset($_POST["invDenied"])) {
    if (filter_has_var(INPUT_POST, 'nomEquipe')) {
        $equipe = filter_input(INPUT_POST, "nomEquipe", FILTER_SANITIZE_STRING);
    }
    refuseInvitation($equipe, $_SESSION["pseudo"]);
    // acceptInvation($equipe, $_SESSION["pseudo"]);
}

if (isset($_POST["leaveTeam"])) {
    if (verifieIsCaptaine($_SESSION["pseudo"])) {
        $idTeam = getIdTeam($_SESSION["pseudo"]);
        deleteInvitation($idTeam);
        deleteParticipation($idTeam);
        updateTeam($idTeam);
        deleteTeam($idTeam);
    } else {
        leaveTeam($_SESSION["pseudo"]);
    }
}


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



    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/notif.css" rel="stylesheet" />
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
                    if (isset($_SESSION["pseudo"])) {
                        $invitation = getTeamInvitation($_SESSION["pseudo"]);
                        echo '<div class="icon mr-2" id="bell"> <img src="https://i.imgur.com/AC7dgLA.png" alt=""> </div>
                        <div class="notifications" id="box">';
                        echo '<h2>Notifications - <span>' . count($invitation) . '</span></h2>';

                        foreach ($invitation as $team) {
                            echo '<form id="notif" action="#" method="POST">';
                            echo '<div class="notifications-item"> <img src="./assets/img/equipe.png" alt="img">';
                            echo   '<div class="text">';
                            echo '<h4>' . $team->nom . '</h4>';
                            echo '<input class="btn  btn-success btn-sm mr-3" style="width: 90px; padding: 5px; font-size: 0.8rem; color: rgb(232, 230, 227) !important;
                            background-color: rgb(20, 108, 67)!important;
                            border-color: rgb(32, 175, 109)!important;" type="submit" name="invAccept" value="Accepter">';
                            echo '<input class="btn  btn-danger btn-sm !important"  style="width: 90px; padding: 5px; font-size: 0.8rem;    color: #fff;
                            background-color: #dc3545;
                            border-color: #dc3545;" type="submit" name="invDenied" value="Refuser">';
                            echo '<input type="hidden" name="nomEquipe" value="' . $team->nom . '">';
                            echo '</div>  </div> ';
                            echo '</form>';
                        }

                        echo '</div>';
                        echo '<li class="nav-item"><a class="nav-link" href="#">' . $_SESSION["pseudo"] . '</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="./deconnexion.php"><img src="./assets/img/deconnexion.png"></a></li>';
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
    <header class="masthead">

    </header>
    <!-- Services-->
    <?php
    if (isset($_SESSION["pseudo"])) {
        if (!verifieRole($_SESSION["pseudo"])) {
            $team = getTeam($_SESSION["pseudo"])
    ?>
            <section class="page-section bg-dark" id="services">
                <div class="container">
                    <div class="text-center">
                        <?php
                        if (verifieTeam($_SESSION["pseudo"])) {
                            echo "<h2 class='section-heading text-uppercase text-light'>" . verifieTeam($_SESSION["pseudo"]) . "</h2>";
                            echo "<h3 class='section-subheading text-muted mb-1'>Votre équipe</h3>";
                            echo '<form id="notif" action="#" method="POST">';
                            echo '<input class="btn  btn-danger btn-sm mb-7 "  style="width: 200px; padding: 10px; font-size: 1rem;    color: #fff;
                            background-color: #dc3545;
                            border-color: #dc3545;" type="submit" name="leaveTeam" value="Quitter l\'équipe">';
                            echo '</form>';
                        }
                        ?>


                    </div>
                    <div class="row text-center mb-5">
                        <?php
                        displayTeam($team);
                        ?>
                    </div>

                    <form action="#" method="POST">
                        <?php
                        if ($erreurInvit != "") {
                            echo   '<div class="alert alert-danger" role="alert">' . $erreurInvit . '</div>';
                        }
                        ?>
                        <input type="text" class="form-control rounded" name="searchPlayer" placeholder="Chercher un joueur">
                        <input type="submit" class="btn btn-primary mb-4 text-black" name="submit" value="Invitation">
                    </form>
                </div>
            </section>
    <?php
        } else {
            echo " <section class='page-section bg-dark' id='services'><div class='container'><div class='text-center'><h2 class='section-heading text-uppercase text-light'>Rejoint une equipe !</h2>";
            echo "<h3 class='section-subheading text-muted'>Si tu veux participer a des tournois</h3>";
            echo  '<button type="button" class="btn btn-primary mb-4"> <a class="nav-link text-black" href="./formulaire/creationTeam.php">Créez une équipe</a></button></div></div></section>';
        }
    }
    ?>
    <!-- Portfolio Grid-->
    <section class="page-section bg-dark" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase text-light">Tournoi</h2>
                <h3 class="section-subheading text-muted mb-4">Vous pouvez créez ou rejoindre des tournoi ici !</h3>
                <?php
                if (isset($_SESSION["pseudo"])) {
                    echo  '<button type="button" class="btn btn-primary mb-4"> <a class="nav-link text-black" href="./formulaire/creationTournoi.php">Créez un tournois</a></button>';
                } else {
                }
                ?>

            </div>
            <div class="row" style="height: 100vh">
                <?php
                foreach ($allTournoi as $tournoi) {
                    echo '<div class="col-lg-4 col-sm-6 mb-4">';
                    echo '<div class="portfolio-item">';
                    echo ' <a class="portfolio-link" data-bs-toggle="modal" href="./tournoi.php?var=' . $tournoi->nom . '">';
                    echo '<div class="portfolio-hover">';
                    echo '<div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>';
                    echo '</div>';
                    echo  '<img class="img-fluid" src="assets/img/' . $tournoi->jeux . '.jpg" alt="..." />';
                    echo '</a>';
                    echo  '<div class="portfolio-caption">';
                    echo '<div class="portfolio-caption-heading  text-black">' . $tournoi->nom . '</div>';
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
    <script>
        $(document).ready(function() {
            var down = false;
            $('#bell').click(function(e) {
                var color = $(this).text();
                if (down) {
                    $('#box').css('height', '0px');
                    $('#box').css('opacity', '0');
                    down = false;
                } else {
                    $('#box').css('height', 'auto');
                    $('#box').css('opacity', '1');
                    down = true;
                }
            });
        });
    </script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>