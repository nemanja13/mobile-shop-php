<?php
    session_start();
    header("Content-type: application/json");
    require "../config/konekcija.php";
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: ../errors/403.php");
    }else{
        $data=null;
        $code=404;

        if(isset($_POST["btn"])){

            $id=$_POST["btn"];
            $ime=$_POST["ime"];
            $prezime=$_POST["prezime"];
            $lozinka=$_POST["lozinka"];
            $telefon=$_POST["telefon"];
            $adresa=$_POST["adresa"];
            $grad=$_POST["grad"];
            $email=$_POST["email"];
            $postanskiBroj=$_POST["postanskiBroj"];
            $idUloga=$_POST["uloga"];
            
            $greske=[];

            if(!preg_match("/^[A-Z][a-z]{2,19}$/", $ime)){
                $greske[]="Ime nije u dobrom formatu";
            }
            if(!preg_match("/^[A-Z][a-z]{2,19}(\s[A-Z][a-z]{2,19})*$/", $prezime)){
                $greske[]="Prezime nije u dobrom formatu";
            }
            if(strlen($lozinka)<6){
                $greske[]="Lozinka nije u dobrom formatu";
            }
            if(!preg_match("/^\w+(\s\w+)*$/", $adresa)){
                $greske[]="Adresa nije u dobrom formatu";
            }
            if(!preg_match("/^[A-Z][a-z]{1,15}(\s[A-Z][a-z]{1,15})*$/", $grad)){
                $greske[]="Grad nije u dobrom formatu";
            }
            if(!preg_match("/^\d{4,10}$/", $postanskiBroj)){
                $greske[]="Poštanski broj nije u dobrom formatu";
            }
            if(!preg_match("/^06\d{7,8}$/", $telefon)){
                $greske[]="Telefon nije u dobrom formatu";
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $greske[]="Email nije u dobrom formatu";
            }

            if(count($greske)){
                $data=$greske;
                $code=422;
            }
            else{
                $lozinka=md5($lozinka);
                $upit="UPDATE korisnici SET  ime=:ime, prezime=:prezime, email=:email, lozinka=:lozinka, adresa=:adresa, grad=:grad, postanskiBroj=:postanskiBroj, telefon=:telefon, idUloga=:idUloga WHERE idKorisnik=:id";
                $priprema=$konekcija->prepare($upit);
                $priprema->bindParam(":id", $id);
                $priprema->bindParam(":ime", $ime);
                $priprema->bindParam(":prezime", $prezime);
                $priprema->bindParam(":email", $email);
                $priprema->bindParam(":lozinka", $lozinka);
                $priprema->bindParam(":adresa", $adresa);
                $priprema->bindParam(":grad", $grad);
                $priprema->bindParam(":postanskiBroj", $postanskiBroj);
                $priprema->bindParam(":telefon", $telefon);
                $priprema->bindParam(":idUloga", $idUloga);
                
                try{
                    $priprema->execute();
                    $code=201;
                    $data=["Uspešno ste izmenili podatke"];
                }catch(PDOException $ex){
                    $code=500;
                    $data=["Došlo je do greške, molimo Vas pokušajte kasnije"];
                }
            }
        }else{
            $code=404;
        }
        echo json_encode($data);
        http_response_code($code);
    }
?>