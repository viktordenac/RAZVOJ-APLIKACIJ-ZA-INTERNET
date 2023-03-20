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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <h2 class="text-center font-weight-light mb-0">Registracija</h2>
                    </div>
                    <div class="card-body">
                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Uporabniško ime:</label>
                                <input class="form-control" type="text" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Geslo:</label>
                                <input class="form-control" type="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ponovi geslo:</label>
                                <input class="form-control" type="password" name="repeat_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Naslov:</label>
                                <input class="form-control" type="text" name="naslov" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pošta:</label>
                                <input class="form-control" type="text" name="posta" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefon:</label>
                                <input class="form-control" type="tel" name="tel" required>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-block mt-4" type="submit" name="submit">Pošlji</button>
                            </div>
                            <div class="text-center mt-3">
                                <label><?php echo $error; ?></label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include_once('footer.php');
?>