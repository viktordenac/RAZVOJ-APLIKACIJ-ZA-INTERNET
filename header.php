<?php
session_start();

//Seja poteče po 30 minutah - avtomatsko odjavi neaktivnega uporabnika
if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < 1800) {
    session_regenerate_id(true);
}
$_SESSION['LAST_ACTIVITY'] = time();

//Poveži se z bazo
$conn = new mysqli('localhost', 'root', '', 'vaja1');
//Nastavi kodiranje znakov, ki se uporablja pri komunikaciji z bazo
$conn->set_charset("UTF8");
?>
<html>
<head>
    <title>Vaja 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <h1>Oglasnik</h1>
    <nav>
        <ul class="list-group">
            <li class="list-group-item list-group-item-primary"><a href="index.php"
                                                                   class="text-decoration-none">Domov</a></li>
            <?php
            if (isset($_SESSION["USER_ID"])) {
                ?>
                <li class="list-group-item"><a href="publish.php" class="text-decoration-none">Objavi oglas</a></li>
                <li class="list-group-item"><a href="myAds.php" class="text-decoration-none">Moji oglasi</a></li>
                <li class="list-group-item"><a href="logout.php" class="text-decoration-none">Odjava</a></li>
                <?php
            } else {
                ?>
                <li class="list-group-item"><a href="login.php" class="text-decoration-none">Prijava</a></li>
                <li class="list-group-item"><a href="register.php" class="text-decoration-none">Registracija</a></li>
                <?php
            }
            ?>
        </ul>
    </nav>
</div>
<hr/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>