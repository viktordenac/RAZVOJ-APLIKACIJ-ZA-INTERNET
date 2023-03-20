<?php
include_once('header.php');

function edit($title, $desc, $categories, $ad_id)
{
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $desc = mysqli_real_escape_string($conn, $desc);
    # $category = mysqli_real_escape_string($conn, $categories);
    $ad_id = mysqli_real_escape_string($conn, $ad_id);

    $my_categories = array();
    foreach ($categories as $category) {
        $catQuery = "SELECT idCat FROM category WHERE value = '$category'";
        $catResult = mysqli_query($conn, $catQuery);
        $catRow = mysqli_fetch_assoc($catResult);
        $my_categories[] = $catRow['idCat'];
    }

    #if podana slika -> posodobimo sliko v bazi
    if ($_FILES['image']['name'] != '') {
        $image_path = 'images/' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $query = "UPDATE ads SET title='$title', description='$desc', image='$image_path' WHERE id='$ad_id'";
    } else {
        $query = "UPDATE ads SET title='$title', description='$desc' WHERE id='$ad_id'";
    }

    // brisanje in dodajanje novih kategorij --> preizkušen UPDATE X
    if ($conn->query($query)) {
        mysqli_query($conn, "DELETE FROM ads_categories WHERE fk_idAds = '$ad_id'");
        foreach ($my_categories as $categoryID) {
            mysqli_query($conn, "INSERT INTO ads_categories (fk_idAds, fk_idCategory) VALUES ('$ad_id', '$categoryID')");
        }
        return true;
    } else {
        return false;
    }
}

function getCategory($selected_id)
{
    global $conn;
    $query = "SELECT value FROM category ORDER BY idCat = $selected_id DESC";
    $res = mysqli_query($conn, $query);

    $catArray = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $catArray[] = $row["value"];
    }
    return $catArray;
}

$error = "";
if (isset($_GET['id'])) {
    $ad_id = $_GET['id'];
    $user_id = $_SESSION['USER_ID'];

    // Pogledamo ce ima user pravice za urejanje objave
    global $conn;
    $query = "SELECT * FROM ads WHERE id='$ad_id' AND user_id='$user_id'";
    $res = mysqli_query($conn, $query);
    $getAd = mysqli_fetch_assoc($res);

    if (!$getAd) {
        $error = "Nimate pravic za urejanje objave!";
    } else {
        $title = $getAd['title'];
        $description = $getAd['description'];
        # $category = $getAd['category_id'];

        $category_query = "SELECT ads_categories.fk_idCategory AS id FROM ads_categories JOIN ads ON ads_categories.fk_idAds=ads.id WHERE ads_categories.fk_idAds = '$ad_id'";
        $category_result = mysqli_query($conn, $category_query);
        $category_id = array();
        while ($row = mysqli_fetch_assoc($category_result)) {
            $category_id[] = $row['id'];
        }

        if (isset($_POST["submit"])) {
            if (edit($_POST["title"], $_POST["description"], $_POST["categories"], $ad_id)) {
                header("Location: index.php");
                die();
            } else {
                $error = "Napaka pri urejanju objave.";
            }
        }
    }
} else {
    $error = "Ni dolocenega idja.";
}

?>
    <div class="container-fluid">
        <h3>Uredi objavo</h3>
        <form action="editAd.php?id=<?php echo $ad_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom: -25px;">
                <label>Naslov</label><input class="form-control form-control-sm" value="<?php echo $title ?>"
                                            type="text" name="title"/> <br/>
            </div>
            <div class="form-group" style="margin-bottom: -20px;">
                <label>Vsebina</label><textarea class="form-control form-control-sm" name="description" rows="10"
                                                cols="50"><?php echo $description ?></textarea> <br/>
            </div>
            <div class="form-group" style="margin-bottom: -20px;">
                <label>Slika</label><input class="form-control form-control-sm" type="file" name="image"/> <br/>
            </div>
            <div class="form-group" style="margin-bottom: -10px;">
                <label>Kategorija:</label>
                <select class="form-select form-select-sm" name="categories[]" multiple>
                    <?php
                    $query = "SELECT * FROM category";
                    $res = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($res)) {
                        $selected = (in_array($row['idCat'], $category_id)) ? 'selected' : '';
                        echo '<option value="' . $row['value'] . '" ' . $selected . '>' . $row['value'] . '</option>';
                    }
                    ?>
                </select>
                <br>
            </div>
            <input class="btn btn-outline-primary" type="submit" name="submit" value="Spremeni"/> <br/>
            <label><?php echo $error; ?></label>
        </form>
    </div>
<?php
include_once('footer.php');
?>