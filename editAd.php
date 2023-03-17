<?php
include_once('header.php');

global $conn;

// Getting the categories
$categories = get_rows("SELECT * FROM category");
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

if(isset($_GET['id'])){
    $id = $_GET['id'];
    // Get the ad information from the database
    $query = "SELECT * FROM ads WHERE id='$id'";
    $res = $conn->query($query);
    $ad = $res->fetch_object();
}

if(isset($_POST['edit'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Check if the user uploaded a new image
    if($_FILES['image']['name'] != '') {
        $image_path = 'images/' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $query = "UPDATE ads SET title='$title', description='$description', image='$image_path' WHERE id='$id', fk_idCategory='$category[0]'";
    } else {
        $query = "UPDATE ads SET title='$title', description='$description', fk_idCategory='$category[0]' WHERE id='$id'";
    }
    $conn->query($query);
    header("Location: myads.php");
}


?>
<html>
<head>
    <title>Edit an ad</title>
</head>
<body>
<div class="container w-50">
    <h2>Edit ad</h2>
    <!-- enctype allows file uploads (image) -->
    <form action="editAd.php?id=<?php echo $ad->id;?>" method="POST" enctype="multipart/form-data">
        <label>Naslov:</label>
        <input class="form-control" type="text" name="title" value="<?php echo $ad->title;?>"/><br><br>

        <label>Vsebina:</label>
        <textarea class="form-control" name="description" rows="10" cols="50"><?php echo $ad->description;?></textarea><br><br>

        <label>Category
            <?php
            foreach ($categories as $category) {
                $selected = "";
                if($category->id == $ad->fk_idCategory){
                    echo "<input list='ads' name='category' value=\"$category->id $category->Value\"/>";
                    break;
                }
            }

            ?>
            <datalist id="ads">
                <?php
                foreach ($categories as $category) {
                    echo "<option value=\"$category->id $category->Value\"/>";
                }
                ?>
            </datalist>
        </label><br/>
        <label>Slika:</label>
        <input class="form-control" type="file" name="image" /><br><br>

        <input type="submit" name="edit" value="Edit" /><br><br>
    </form>
</div>
</body>
</html>