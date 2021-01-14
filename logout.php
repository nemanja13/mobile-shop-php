<?php
 session_start();
 ob_start();
 require_once "config/konekcija.php";
 unset($_SESSION['korisnik']);
 header("Location: index.php");
?>
