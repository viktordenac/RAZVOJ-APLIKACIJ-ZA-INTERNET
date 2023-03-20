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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Seoska Bolha</title>
</head>
<body>
<div class="container-fluid">
    <h1>Oglasnik</h1>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Domov</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION["USER_ID"])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Oglasi
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="publish.php">Objavi oglas</a></li>
                                <li><a class="dropdown-item" href="myAds.php">Moji oglasi</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Odjava</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Prijava</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Registracija</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <form class="form-inline my-2 my-lg-0">
                <p style="color: aqua">Created by Viktor Denac</p>
            </form>
        </div>
    </nav>
    <hr>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
</body>
</html>