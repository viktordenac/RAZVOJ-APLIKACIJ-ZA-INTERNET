<?php
?>
<h2>Uredi Oglas</h2>
<form action="publish.php" method="POST" enctype="multipart/form-data">
    <label>Naslov</label><input type="text" name="title" /> <br/>
    <label>Vsebina</label><textarea name="description" rows="10" cols="50"></textarea> <br/>
    <label>Slika</label><input type="file" name="image" /> <br/>
    <input type="submit" name="submit" value="Objavi" /> <br/>
</form>
<?php
include_once('footer.php');
?>
