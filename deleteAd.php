<?php
include_once('header.php');

global $conn;

if(isset($_GET['id'])){
    $id = $_GET['id'];
    // Get the ad information from the database
    $query = "SELECT * FROM ads WHERE id='$id'";
    $res = $conn->query($query);
    $oglas = $res->fetch_object();
}

if(isset($_POST['delete'])){
    $query = "DELETE FROM ads WHERE id='$id'";
    $conn->query($query);
    header("Location: myAds.php");
    die();
}

if(isset($_POST['deleteNo'])){
    header("Location: myAds.php");
    die();
}

?>
<html>
<head>
    <title>Deleting Ad</title>
</head>
<body>
<div class="container w-50">
    <h2>Are you sure you want to delete this ad?</h2>
    <!-- enctype allows file uploads (image) -->
    <form action="deleteAd.php?id=<?php echo $oglas->id;?>" method="POST" enctype="multipart/form-data">
        <input type="submit" name="delete" value="Yes"/>
        <input type="submit" name="deleteNo" value="No" /><br><br>
    </form>
</div>
</body>
</html>