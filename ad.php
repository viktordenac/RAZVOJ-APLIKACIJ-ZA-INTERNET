<?php
include_once('header.php');
global $conn;
//Funkcija izbere oglas s podanim ID-jem. Doda tudi uporabnika, ki je objavil oglas.
function get_ad($id){
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT ads.*, users.username FROM ads LEFT JOIN users ON users.id = ads.user_id WHERE ads.id = $id;";
    $res = $conn->query($query);
    if($obj = $res->fetch_object()){
        return $obj;
    }
    return null;
}

function get_category($ad){
    $ad_id = $ad->id;
    $query = "SELECT fk_idCategory FROM ads_categories WHERE fk_idAds='$ad_id'";
    global $conn;
    $res = mysqli_query($conn, $query);
    $my_categories = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $category_id = $row['fk_idCategory'];
        $query_category = "SELECT value FROM category WHERE idCat='$category_id'";
        $result_category = mysqli_query($conn, $query_category);
        $category = mysqli_fetch_assoc($result_category);
        array_push($my_categories, $category['value']);
    }
    return $my_categories;
}

if(!isset($_GET["id"])){
    echo "Težava z GET id";
    die();
}
$id = $_GET["id"];
$ad = get_ad($id);
if($ad == null){
    echo "Oglas z podanim ID-jem ne obstaja";
    die();
}

error_reporting(E_ERROR | E_PARSE);

if(!isset($_COOKIE['userAdVisit'.$id])){
    // Count only the views from logged-in users
    if(isset($_SESSION["USER_ID"])){
        $user_id = $_SESSION["USER_ID"];
        $views = "SELECT views FROM ads WHERE ads.id = $id;";
        $resViews = $conn->query($views);
        $view = $resViews->fetch_object()->views;
        $query = "UPDATE ads SET views = $view+1 WHERE ads.id = $id;";
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


?>
<html>
<head>
        <title>Ads Page</title>
</head>
    <style>
        img {
            width: 400px;
            height: 200px;
        }
    </style>
<body>
    <div class="ad">
        <div class="container-fluid">
            <h4><?php echo $ad->title;?></h4>
            <?php
            global $conn;
            $query = "SELECT value FROM category WHERE idCat= '$ad->category_id'";
            $res = mysqli_query($conn, $query);
            $my_category = mysqli_fetch_assoc($res);
            echo $my_category['value'];
            ?>
            <p><b></b><?php echo "<b>Opis:</b> ".$ad->description;?></p>
            <p><?php echo "<img src='".$ad->image."'>"?></p>
            <p> <b>Kategorija:</b>
                <?php
                echo implode(", ", get_category($ad));
                ?>
            </p>
            <p><b>Published:</b> <?php echo $ad->username; ?></p>
            <p><b>Datum objave:</b> <?php echo $ad->lastUpdate ?></p>
            <p><b>Ogledov:</b> <?php echo $ad->views ?></p>
            <a href="index.php"><button class="btn btn-outline-primary">Nazaj</button></a>

        </div>
    </div>
    <hr/>
</body>
</html>
<?php

include_once('footer.php');
?>