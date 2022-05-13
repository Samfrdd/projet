<!--
Author: Colorlib
Author URL: https://colorlib.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->

<?php
session_start();
require_once '../db/database.php';
?>

<!DOCTYPE html>
<html>

<head>
	<title>Creative Colorlib SignUp Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!-- Custom Theme files -->
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<!-- //Custom Theme files -->
	<!-- web font -->
	<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
	<!-- //web font -->
</head>

<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1>Crée un compte</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="#" method="post">
					<input class="text" type="text" name="Pseudo" placeholder="Pseudo" required="">
					<input class="text w3lpass" type="password" name="password" placeholder="Mot de passe" required="">
					<input class="text w3lpass" type="password" name="ConfirmPassword" placeholder="Confirmer Mot de passe" required="">
					<input type="submit" name="submit" value="Inscription">
				</form>
				<p>Vous avez deja un compte ? <a href="./connexion.php"> Connectez-vous !</a></p>
			</div>
		</div>
	</div>
</body>

</html>