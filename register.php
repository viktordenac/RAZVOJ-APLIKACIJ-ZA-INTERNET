<?php
include_once('header.php');

// Funkcija preveri, ali v bazi obstaja uporabnik z določenim imenom in vrne true, če obstaja.
function username_exists($username){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$query = "SELECT * FROM users WHERE username='$username'";
	$res = $conn->query($query);
	return mysqli_num_rows($res) > 0;
}

// Funkcija ustvari uporabnika v tabeli users. Poskrbi tudi za ustrezno šifriranje uporabniškega gesla.
function register_user($username, $password, $naslov, $posta, $tel){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$pass = sha1($password);
    $naslov = mysqli_real_escape_string($conn, $naslov);
    $posta = mysqli_real_escape_string($conn, $posta);
    $tel = mysqli_real_escape_string($conn, $tel);
	/* 
		Tukaj za hashiranje gesla uporabljamo sha1 funkcijo. V praksi se priporočajo naprednejše metode, ki k geslu dodajo naključne znake (salt).
		Več informacij: 
		http://php.net/manual/en/faq.passwords.php#faq.passwords 
		https://crackstation.net/hashing-security.htm
	*/
	$query = "INSERT INTO users (username, password, naslov, posta, tel) VALUES ('$username', '$pass', '$naslov', '$posta', '$tel');";
	if($conn->query($query)){
		return true;
	}
	else{
		echo mysqli_error($conn);
		return false;
	}
}

$error = "";
if(isset($_POST["submit"])){
	/*
		VALIDACIJA: preveriti moramo, ali je uporabnik pravilno vnesel podatke (unikatno uporabniško ime, dolžina gesla,...)
		Validacijo vnesenih podatkov VEDNO izvajamo na strežniški strani. Validacija, ki se izvede na strani odjemalca (recimo Javascript), 
		služi za bolj prijazne uporabniške vmesnike, saj uporabnika sproti obvešča o napakah. Validacija na strani odjemalca ne zagotavlja
		nobene varnosti, saj jo lahko uporabnik enostavno zaobide (developer tools,...).
	*/
	//Preveri če se gesli ujemata
	if($_POST["password"] != $_POST["repeat_password"]){
		$error = "Gesli se ne ujemata.";
	}
	//Preveri ali uporabniško ime obstaja
	else if(username_exists($_POST["username"])){
		$error = "Uporabniško ime je že zasedeno.";
	}
	//Podatki so pravilno izpolnjeni, registriraj uporabnika
	else if(register_user($_POST["username"], $_POST["password"], $_POST["naslov"], $_POST["posta"], $_POST["tel"])){
		header("Location: login.php");
		die();
	}
	//Prišlo je do napake pri registraciji
	else{
		$error = "Prišlo je do napake med registracijo uporabnika.";
	}
}

?>
	<h2>Registracija</h2>
	<form action="register.php" method="POST">
        <table>
            <tr>
                <td><label>Uporabniško ime:</label></td>
                <td><input type="text" name="username" /> <br/></td>
            </tr>
            <tr>
                <td><label>Geslo:</label></td>
                <td><input type="password" name="password" /> <br/></td>
            </tr>
            <tr>
                <td><label>Ponovi geslo:</label></td>
                <td><input type="password" name="repeat_password" /> <br/></td>
            </tr>
            <tr>
                <td><label>Naslov:</label></td>
                <td><input type="naslov" name="naslov" /> <br/></td>
            </tr>
            <tr>
                <td><label>Pošta:</label></td>
                <td><input type="posta" name="posta" /> <br/></td>
            </tr>
            <tr>
                <td><label>Telefon:</label></td>
                <td><input type="tel" name="tel" /> <br/></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Pošlji" /> <br/></td>
            </tr>
        </table>
		<label><?php echo $error; ?></label>
	</form>
<?php
include_once('footer.php');
?>