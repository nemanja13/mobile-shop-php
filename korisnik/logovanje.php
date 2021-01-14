<?php
    session_start();
    header("Content-type: application/json");
    require "../config/konekcija.php";
    $data=null;
    $code=404;

    if(isset($_POST["btn"])){

        $lozinka=$_POST["lozinka"];
        $email=$_POST["email"];
        
        $greske=[];

        if(strlen($lozinka)<6){
            $greske[]="Lozinka nije u dobrom formatu";
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
            $upit="SELECT k.idKorisnik, k.ime, k.prezime, u.ulogaNaziv FROM korisnici k INNER JOIN uloge u ON k.idUloga=u.idUloga WHERE k.email=:email AND k.lozinka=:lozinka";
            $priprema=$konekcija->prepare($upit);
            $priprema->bindParam(":email", $email);
            $priprema->bindParam(":lozinka", $lozinka);
            $priprema->execute();

            $upitEmail="SELECT k.idKorisnik, k.ime, k.prezime, u.ulogaNaziv FROM korisnici k INNER JOIN uloge u ON k.idUloga=u.idUloga WHERE k.email=:email";
            $pripremaEmail=$konekcija->prepare($upitEmail);
            $pripremaEmail->bindParam(":email", $email);
            $pripremaEmail->execute();

            $upitLozinka="SELECT k.idKorisnik, k.ime, k.prezime, u.ulogaNaziv FROM korisnici k INNER JOIN uloge u ON k.idUloga=u.idUloga WHERE k.lozinka=:lozinka";
            $pripremaLozinka=$konekcija->prepare($upitLozinka);
            $pripremaLozinka->bindParam(":lozinka", $lozinka);
            $pripremaLozinka->execute();
            if($pripremaEmail->rowCount()!=1){
                $code=401;
                $data=["Pogrešan e-mail"];
            }
            else if($pripremaLozinka->rowCount()==0){
                $code=401;
                $data=["Pogrešna lozinka"];
            }else{
                if($priprema->rowCount()==1){
                    $rezultat=$priprema->fetch();
                    $code=200;
                    $_SESSION['korisnik']=$rezultat;
                }else{
                    $code=401;
                    $data=["Korisnik sa tim podacima ne postoji u bazi"];
                }
            }
           
        }
    }else{
        $code=404;
    }
    echo json_encode($data);
    http_response_code($code);
?>