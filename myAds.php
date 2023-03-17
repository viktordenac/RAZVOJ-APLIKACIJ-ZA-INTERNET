<?php
include_once('header.php');

$categories = get_rows("SELECT * FROM category");
function get_ads()
{
    //    $query = "SELECT * FROM ads;";

    global $conn;
    $ads = array();
    if (isset($_SESSION["USER_ID"])) {
        $user_id = $_SESSION["USER_ID"];
        $query = "select * from  ads where ads.user_id =\"$user_id\";";
        $res = $conn->query($query);
        while ($ad = $res->fetch_object()) {
            array_push($ads, $ad);
        }
    }
    return $ads;
}
//preberemo vnaprej določene kategorije
function get_rows($select)
{
    global $conn;
    $query = $select;
    $res = $conn->query($query);
    $rows = array();
    while ($row = $res->fetch_object()) {
        array_push($rows, $row);
    }
    return $rows;
}

$ads = get_ads();
?> <label>Category
        <input list="cat" name="category">
        <datalist id="cat">
            <?php
            $counter = 1;
            foreach ($categories as $category){
                echo "<option value=\"$counter $category->type\"/>";
                $counter++;
            }
            ?>
        </datalist>
<?php

$oglasi = get_ads();
foreach($oglasi as $oglas){
    //vodenj ogledov
    if (isset($_SESSION['views']))
        $_SESSION['views'] = $_SESSION['views'] + 1;
    else
        $_SESSION['views'] = 1;
    echo "views = " . $_SESSION['views'];
    ?>
    <div class="oglas">
        <h4><?php echo $oglas->title;?></h4>
        <p><?php echo $oglas->description;?></p>
		<!-- Redirects to edit.php with the id of the ad -->
		<a href="editAd.php?id=<?php echo $oglas->id;?>">
			<button class="btn btn-primary">Edit</button>
		</a>
		<a href="deleteAd.php?id=<?php echo $oglas->id;?>">
			<button class="btn btn-primary">Delete</button>
		</a>
    </div>
    <hr/>
    <?php
}
include_once('footer.php');
?>