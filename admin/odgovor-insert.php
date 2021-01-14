<?php
    header("Content-type: application/json");
    session_start();
    require "../config/konekcija.php";
    $data=null;
    $code=404;
    if(isset($_POST["idKorisnik"])){
        $idKorisnik=$_POST["idKorisnik"];
        $idOdgovor=$_POST["idOdgovor"];
        $upitKorisnik="SELECT * FROM korisnici WHERE idKorisnik=:id";
        $upitOdgovor="SELECT * FROM odgovor WHERE idOdgovor=:id";

        $pripremaKorisnik=$konekcija->prepare($upitKorisnik);
        $pripremaKorisnik->bindParam(":id", $idKorisnik);

        $pripremaOdgovor=$konekcija->prepare($upitOdgovor);
        $pripremaOdgovor->bindParam(":id", $idOdgovor);

        $pripremaKorisnik->execute();
        $pripremaOdgovor->execute();

        if($pripremaOdgovor->rowCount()==1 && $pripremaKorisnik->rowCount()==1){
            $upitInsert="INSERT INTO glasanje VALUES(NULL, :idKorisnik, :idOdgovor)";
            $pripremaInsert=$konekcija->prepare($upitInsert);
            $pripremaInsert->bindParam(":idKorisnik", $idKorisnik);
            $pripremaInsert->bindParam(":idOdgovor", $idOdgovor);
            
            try{
                $pripremaInsert->execute();
                $code=200;
                $data=["Uspešno ste odgovorili na anketu"];
            }catch(PDOException $ex){
                $code=500;
                $data=["Došlo je do greške, molimo Vas pokušajte kasnije"];
            }
        }else{
            $code=500;
            $data=["Došlo je do greške, molimo Vas pokušajte kasnije"];
        }
    }
    echo json_encode($data);
    http_response_code($code);
?>