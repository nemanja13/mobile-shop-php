<?php
    session_start();
    header("Content-type: application/json");
    require "../config/konekcija.php";
    $data=null;
    $code=404;

    if(isset($_POST["btn"])){

        $ime=$_POST["ime"];
        $prezime=$_POST["prezime"];
        $telefon=$_POST["telefon"];
        $email=$_POST["email"];
        $razlogKontakta=$_POST["razlogKontakta"];
        $poruka=$_POST["poruka"];
        
        $greske=[];

        if(!preg_match("/^[A-Z][a-z]{2,19}$/", $ime)){
            $greske[]="Ime nije u dobrom formatu";
        }
        if(!preg_match("/^[A-Z][a-z]{2,19}(\s[A-Z][a-z]{2,19})*$/", $prezime)){
            $greske[]="Prezime nije u dobrom formatu";
        }
        if($razlogKontakta==""){
            $greske[]="Morate izabrati razlog kontakta";
        }
        if(!preg_match("/^[^@]+(\s[^@]+)*$/", $poruka)){
            $greske[]="Morate uneti poruku";
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
            $upit="INSERT INTO mejl VALUES (null, :ime, :prezime, :email, :telefon, :razlogKontakta, :poruka, DEFAULT)";
            $priprema=$konekcija->prepare($upit);
            $priprema->bindParam(":ime", $ime);
            $priprema->bindParam(":prezime", $prezime);
            $priprema->bindParam(":email", $email);
            $priprema->bindParam(":telefon", $telefon);
            $priprema->bindParam(":razlogKontakta", $razlogKontakta);
            $priprema->bindParam(":poruka", $poruka);
            
            try{
                $priprema->execute();
                $code=200;
                $data=["Uspešno ste poslali e-mail"];
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
?>