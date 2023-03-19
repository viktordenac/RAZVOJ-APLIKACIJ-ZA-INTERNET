<?php 
include_once('header.php');
global $conn;
//Funkcija izbere oglas s podanim ID-jem. Doda tudi uporabnika, ki je objavil oglas.
function get_ad($id){
	global $conn;
	$id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT ads.*, users.username, category.Value FROM ads 
    LEFT JOIN users ON users.id = ads.user_id 
    JOIN category ON ads.fk_idCategory = category.idCat                                         
    WHERE ads.id = $id;";
    $res = $conn->query($query);
	if($obj = $res->fetch_object()){
		return $obj;
	}
	return null;
}
$id = $_GET["id"];

if(!isset($_COOKIE['userAdVisit'.$id])){
    // Count only the views from logged-in users
        if(isset($_SESSION["USER_ID"])){
            $user_id = $_SESSION["USER_ID"];
            $views = "SELECT views FROM ads";
            $resViews = $conn->query($views);
            $view = $resViews->fetch_object()->views;
            $query = "UPDATE ads SET views = $view+1";
            $conn->query($query);
            setcookie("userAdVisits", $user_id);
        }
    }
    // Count all views for this ad
    $query = "SELECT views FROM ads WHERE id = '$id'";
    $res = $conn->query($query);
    $view_count = $res->fetch_object()->views;

if(!isset($_GET["id"])){
	echo "Manjkajoči parametri.";
	die();
}
$ad = get_ad($id);
if($ad == null){
	echo "Oglas ne obstaja.";
	die();
}
?>
    <html>
    <head>
        <title>Ad Page</title>
    </head>
    <style>
        img {
            width: 400px;
            height: 200px;
        }
    </style>
    <body>
    <div class="container">
        <h2><?php echo "Naslov: ".$ad->title;?></h2>
        <p><?php echo "Opis: ". $ad->description;?></p>
        <p><?php echo "Kategorija: ". $ad->Value;?></p>
        <p><?php echo "<img src='".$ad->image."'>"?></p>
        <p><?php echo "Published: ".$ad->lastUpdate;?></p>
        <p>Objavil: <?php echo $ad->username; ?></p>
        <p><?php echo "Views: ".$ad->views;?></p>
        <a href="index.php"><button>Nazaj</button></a>
    </div>
    </body>
    </html>
	<?php

include_once('footer.php');
?>