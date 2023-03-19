<?php
session_start(); //Naloži sejo
setcookie('userAdVisit', '', time() - (86400 * 30), "/","");  // 86400 = 1 day
session_unset(); //Odstrani sejne spremenljivke
session_destroy(); //Uniči sejo
header("Location: index.php"); //Preusmeri na index.php
?>