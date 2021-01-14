<?php 
    session_start();
    require "../config/konekcija.php";
    if(!isset($_GET["id"]) || !isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: ../errors/403.php");
    }else{
        $id=$_GET["id"];
        $upit="SELECT * FROM mejl WHERE idMejl=:id";
        $priprema=$konekcija->prepare($upit);
        $priprema->bindParam(":id", $id);
        $priprema->execute();
        if($priprema->rowCount()!=1){
            header("Location: ../errors/404.php");
        }else{
            try{
                $upitDelete="DELETE FROM mejl WHERE idMejl=:id";
                $priprema2=$konekcija->prepare($upitDelete);
                $priprema2->bindParam(":id", $id);
                $priprema2->execute();
                header("Location: ../mejlovi-admin.php");
            }catch(PDOException $ex){
                var_dump( $ex->getMessage());
            }
        }
    }
    
?>