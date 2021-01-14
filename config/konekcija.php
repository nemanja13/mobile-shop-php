<?php
require_once("config.php");
try{
    $konekcija= new PDO("mysql:host=".SERVER.";dbname=".DATABASE , USER, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'"));
    $konekcija->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $konekcija->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}catch(PDOException $ex){
    echo "Greska pri konekciji: ". $ex->getMessage();
}
?>