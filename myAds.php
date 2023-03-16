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
        /*$query = "SELECT users.name as uname, ads.type as atype, categories.type as ctype, done, ads.user_id as ide FROM user
        JOIN users ON
            ads.user_id=users.id
        WHERE users.id = \"$user_id\";";*/
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
/*
// Funkcija prebere oglase iz baze in vrne polje objektov
function get_oglasi(){
    global $conn;
    $query = "SELECT * FROM ads ;";
    $res = $conn->query($query);
    $oglasi = array();
    while($oglas = $res->fetch_object()){
        array_push($oglasi, $oglas);
    }
    return $oglasi;
}*/

$ads = get_ads();
?> <label>Category
        <input list="cats" name="category">
        <datalist id="cats">
            <?php
            $counter = 1;
            foreach ($categories as $category){
                echo "<option value=\"$counter $category->type\"/>";
                $counter++;
            }
            ?>
        </datalist>
 <button class="btn btn-primary" name="filter">Filter</button>
    <form action="index.php" method="POST">
    <input name="valueToSearch" type="text"  placeholder="Search..."/>
    <input type="submit" name="search" value="Search"/>
<?php
/*
?>    <li><a href="category.php?>id=1">Živali</a></li>
    <li><a href="category.php?>id=2">Hrana</a></li>
    <li><a href="category.php?>id=3">Glasba</a></li>
<?php*/
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
        <a href="oglas.php?id=<?php echo $oglas->id;?>"><button>Preberi več</button></a>
    </div>
    <hr/>
    <?php
}


/*
$categories = get_rows("SELECT * FROM categories");


// Funkcija prebere oglase iz baze in vrne polje objektov
function get_oglasi(){
    global $conn;
    $query = "SELECT * FROM ads;";
    $res = $conn->query($query);
    $oglasi = array();
    while($oglas = $res->fetch_object()){
        array_push($oglasi, $oglas);
    }
    return $oglasi;
}

//Da preberemo vnaprej določene kategorije
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

global $conn;
if(isset($_POST['filter'])) {
    $category =$_POST["category"];
    $query = "SELECT * FROM ads INNER JOIN (categories) ON (ads.category_id = cetagories.id) WHERE categories.id = 1;";
    //$query=mysqli_query($conn, "SELECT * FROM `ads` WHERE `category_id`='$category'") or die(mysqli_error());
    /* while($fetch=mysqli_fetch_array($query)){
         echo"<tr><td>".$fetch['title']."</td><td>".$fetch['description']."</td></tr>";
     }
    $res = $conn->query($query); //povežemo z query in shranimo v res
    $number_of_results = mysqli_query($conn,$query);

    if(mysqli_num_rows($number_of_results)>0){
        while($row = mysqli_fetch_assoc($number_of_results)){
        }
        echo $row['title'];
        echo $row['description'];
    }
    else{
        echo "No results";
    }
}

//Preberi oglase iz baze
$oglasi = get_oglasi();
//is_user_logged_in() && get_current_user_id()
foreach($oglasi as $oglas){
    if (is_user_logged_in() && get_current_user_id() == $oglas->user_id) {
        ?>
        <h4><?php echo $oglas->title;?></h4>
        <p><?php echo $oglas->description;?></p>
        <a href="oglas.php?id=<?php echo $oglas->id;?>"><button>Preberi več</button></a>
        <?php
    }
}
/*
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
        <a href="oglas.php?id=<?php echo $oglas->id;?>"><button>Preberi več</button></a>
    </div>
    <hr/>
    <?php
}
*/

include_once('footer.php');
?>