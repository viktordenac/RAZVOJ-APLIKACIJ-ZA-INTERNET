<?php
include "header.php";


$error = "";
$categories = get_rows("SELECT * FROM category");

// Funkcija vstavi nov oglas v bazo. Preveri tudi, ali so podatki pravilno izpolnjeni.
// Vrne false, če je prišlo do napake oz. true, če je oglas bil uspešno vstavljen.
function update($title, $desc, $img, $category){
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $desc = mysqli_real_escape_string($conn, $desc);
    $user_id = $_SESSION["USER_ID"];
    $category_id = $category[0];
    //Preberemo vsebino (byte array) slike
    $img_file = file_get_contents($img["tmp_name"]);
    //Pripravimo byte array za pisanje v bazo (v polje tipa LONGBLOB)
    $img_file = mysqli_real_escape_string($conn, $img_file);
    //$datum_zapadlosti = time() + (30 * 0 * 0 * 0);
    $created_dt = date("Y-m-d");

    $query = "INSERT INTO ads (title, description, user_id, image, category_id)
				VALUES('$title', '$desc', '$user_id', '$img_file', $category_id);";


    if($conn->query($query)){
        return true;
    }
    else{
        //Izpis MYSQL napake z: echo mysqli_error($conn);
        return false;
    }

    /*
    //Pravilneje bi bilo, da sliko shranimo na disk. Poskrbeti moramo, da so imena slik enolična. V bazo shranimo pot do slike.
    //Paziti moramo tudi na varnost: v mapi slik se ne smejo izvajati nobene scripte (če bi uporabnik naložil PHP kodo). Potrebno je urediti ustrezna dovoljenja (permissions).

        $imeSlike=$photo["name"]; //Pazimo, da je enolično!
        //sliko premaknemo iz začasne poti, v neko našo mapo, zaradi preglednosti
        move_uploaded_file($photo["tmp_name"], "slika/".$imeSlike);
        $pot="slika/".$imeSlike;
        //V bazo shranimo $pot
    */
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
function get_data($select, $col)
{
    global $conn;
    $query = $select;
    $res = $conn->query($query);
    if ($res_obj = $res->fetch_object()) {
        return $res_obj->$col;
    } else
        return false;
}

if(isset($_POST["shrani"])){
    if(publish($_POST["title"], $_POST["description"], $_FILES["image"], $_POST["category"])){
        header("Location: index.php");
        die();
    }
    else{
        $error = "Prišlo je do napake pri urejanju oglasa.";
    }
}
?>
<h2>Uredi oglas</h2>
<form action="uredioglas.php" method="POST" enctype="multipart/form-data">
    <label>Naslov</label><input type="text" name="title" /> <br/>
    <label>Vsebina</label><textarea name="description" rows="10" cols="50"></textarea> <br/>
    <label>Slika</label><input type="file" name="image" /> <br/>
    <label>Category
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
    </label><br/>
    <input type="submit" name="shrani" value="Shrani" /> <br/>
    <label><?php echo $error; ?></label>
</form>

<?php
include_once('footer.php');
?>

