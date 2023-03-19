<?php
include_once('header.php');

// Funkcija prebere oglase iz baze in vrne polje objektov
function get_ads(){
	global $conn;
	$query = "SELECT id, title, description, lastUpdate, user_id, image, category.Value FROM ads 
    JOIN category ON ads.fk_idCategory = category.idCat
    GROUP BY ads.id
	ORDER BY ads.id DESC;";
	$res = $conn->query($query);
	$ads = array();
	while($ad = $res->fetch_object()){
		array_push($ads, $ad);
	}
	return $ads;
}

function get_ad(){
    global $conn;
    $query = "SELECT * FROM ads;";
    $res = $conn->query($query);
    $ads1 = array();
    while($ad = $res->fetch_object()){
        array_push($ads1,$ad);
    }
    return $ads1;
}

//Preberi oglase iz baze
$ads = get_ads();
$ads1 = get_ad();

//Izpiši oglase
//Doda link z GET parametrom id na oglasi.php za gumb 'Preberi več'
foreach($ads as $ad){
	?>
	<div class="ad">
		<h4><?php echo $ad->title;?></h4>
		<p><?php echo "Opis: ".$ad->description;?></p>
        <p><?php echo "Kategorija: ".$ad->Value;?></p>
        <a href="ad.php?id=<?php echo $ad->id;?>"><button>Preberi več</button></a>
	</div>
    <hr/>
    <?php

}
include_once('footer.php');
?>