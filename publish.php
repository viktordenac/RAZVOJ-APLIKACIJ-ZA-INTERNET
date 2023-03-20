<?php
include_once('header.php');

// Funkcija vstavi nov oglas v bazo. Preveri tudi, ali so podatki pravilno izpolnjeni.
// Vrne false, če je prišlo do napake oz. true, če je oglas bil uspešno vstavljen.
function publish($title, $desc, $img, $categories){
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $desc = mysqli_real_escape_string($conn, $desc);
    # $category = mysqli_real_escape_string($conn, $categories);
    $user_id = $_SESSION["USER_ID"];



    $categoryIDs = array();
    foreach ($categories as $category) {
        $categoryQuery = "SELECT idCat FROM category WHERE value = '$category'";
        $categoryResult = mysqli_query($conn, $categoryQuery);
        $categoryRow = mysqli_fetch_assoc($categoryResult);
        $categoryIDs[] = $categoryRow['idCat'];
    }

    $image_path = 'images/' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

    $query = "INSERT INTO ads (title, description, lastUpdate, user_id, image, views)
				VALUES('$title', '$desc',CURRENT_TIMESTAMP , '$user_id', '$image_path', 0);";

    if($conn->query($query)){
        $ad_id = mysqli_insert_id($conn);
        foreach ($categoryIDs as $categoryID) {
            $query = "INSERT INTO ads_categories (fk_idAds, fk_idCategory) VALUES ('$ad_id', '$categoryID');";
            $conn->query($query);
        }
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

function getCategory() {
    global $conn;
    $query = "SELECT value FROM category";
    $res = $conn->query($query);

    $myArr = array();
    while($row = mysqli_fetch_assoc($res)) {
        $myArr[] = $row["value"];
    }
    # print_r($myArr);
    return $myArr;
}

$error = "";
if(isset($_POST["submit"])){
    if(publish($_POST["title"], $_POST["description"], $_FILES["image"], $_POST["categories"])){
        header("Location: index.php");
        die();
    }
    else{
        $error = "Prišlo je do našpake pri objavi oglasa.";
    }
}
?>
    <div class="container-fluid">
        <h3 class="text-center mb-4">Objavi oglas</h3>
        <form action="publish.php" method="POST" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label class="form-label"><b>Naslov</b></label>
                <input class="form-control form-control-sm" type="text" name="title" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label"><b>Vsebina</b></label>
                <textarea class="form-control form-control-sm" name="description" rows="10" cols="50" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label class="form-label"><b>Slika</b></label>
                <input class="form-control form-control-sm" type="file" name="image" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label"><b>Kategorije</b></label>
                <select class="form-select form-select-sm" name="categories[]" multiple required>
                    <?php foreach (getCategory() as $option) { ?>
                        <option value="<?php echo $option ?>"><?php echo $option ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="text-center">
                <button class="btn btn-primary btn-lg" type="submit" name="submit">Objavi</button>
                <br>
                <label class="mt-3"><?php echo $error; ?></label>
            </div>
        </form>
    </div>
<?php
include_once('footer.php');
?>