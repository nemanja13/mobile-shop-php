<?php
    session_start();
    header("Content-type: application/json");
    require "../config/konekcija.php";
    $data=null;
    $code=404;
    
    if(isset($_POST["proizvodi"]) && isset($_POST["id"])){
        $idKorisnik=$_POST["id"];
        
        $upitSelect="SELECT * FROM korisnici WHERE idKorisnik=:id";
        $priprema=$konekcija->prepare($upitSelect);
        $priprema->bindParam(":id", $idKorisnik);
        $priprema->execute();

        if($priprema->rowCount()==1){
            $upit="INSERT INTO porudzbine VALUES(NULL, :idKorisnik)";
            $priprema=$konekcija->prepare($upit);
            $priprema->bindParam(":idKorisnik", $idKorisnik);
            
            

            try {
                $konekcija->beginTransaction();
                $priprema->execute();
                $idPorudzbina=$konekcija->lastInsertId();
                $konekcija->commit();
               
                foreach($_POST["proizvodi"] as $p){
                    $kolicina=$p["kolicina"];
                    $idProizvod=$p["id"];
                    $upit2="INSERT INTO detaljiporudzbine VALUES(NULL, :idPorudzbina, :idProizvod, :kolicina, DEFAULT)";
                    $priprema2=$konekcija->prepare($upit2);
                    $priprema2->bindParam(":idProizvod", $idProizvod);
                    $priprema2->bindParam(":idPorudzbina", $idPorudzbina);
                    $priprema2->bindParam(":kolicina", $kolicina);
                    $priprema2->execute();
                }
                $code=201;
                $data=["Vaša narudžbina je prihvaćena."];
            } catch(PDOExecption $e) {
                $code=500;
                $data=["Došlo je do greške. Pokušajte kasnije."];
            }
        }
        else{
            $code=404;
        }

        
    }
    echo json_encode($data);
    http_response_code($code);
?>