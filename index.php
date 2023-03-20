<?php
include_once('header.php');

// Funkcija prebere oglase iz baze in vrne polje objektov
function get_ads(){
	global $conn;
	$query = "SELECT * FROM ads ORDER BY lastUpdate DESC;";
	$res = $conn->query($query);
	$ads = array();
	while($ad = $res->fetch_object()){
		array_push($ads, $ad);
	}
	return $ads;
}
// Funkcija prebere potrebne kategorije iz baze ter vrne polje objektov
function get_categories($ad){
    $ad_id = $ad->id;
    $query_ad_category = "SELECT fk_idCategory FROM ads_categories WHERE fk_idAds='$ad_id'";
    global $conn;
    $result_ad_category = mysqli_query($conn, $query_ad_category);
    $my_categories = array();
    while ($row = mysqli_fetch_assoc($result_ad_category)) {
        $category_id = $row['fk_idCategory'];
        $query_category = "SELECT value FROM category WHERE idCat='$category_id'";
        $result_category = mysqli_query($conn, $query_category);
        $category = mysqli_fetch_assoc($result_category);
        array_push($my_categories, $category['value']);
    }
    return $my_categories;
}

//Preberi oglase iz baze
$ads = get_ads();

//Izpiši oglase
//Doda link z GET parametrom id na oglasi.php za gumb 'Preberi več'
foreach($ads as $ad){
	?>
	<div class="ad">
        <div class="container-fluid">
		<h4><?php echo $ad->title;?></h4>
		<p><?php echo "Opis: ".$ad->description;?></p>
        <p> <b>Kategorija:</b>
            <?php
            echo implode(", ", get_categories($ad));
            ?>
        </p>
        <a href="ad.php?id=<?php echo $ad->id;?>" class="btn btn-info" role="button" aria-pressed="true">Preberi več</a>
	    </div>
    </div>
    <hr/>
    <?php

}
include_once('footer.php');
?>