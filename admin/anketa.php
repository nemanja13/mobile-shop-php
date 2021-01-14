<?php
    header("Content-type: application/json");
    session_start();
    require "../config/konekcija.php";
    $data=null;
    $code=404;
    if(isset($_POST["btn"])){
        $btn=$_POST["btn"];
        if($btn=="unosAnketa"){

            $pitanje=$_POST["pitanje"];
            $odgovori=$_POST["odgovori"];
            $greske=[];

            if(!preg_match("/^[^@]+$/", $pitanje)){
                $code=400;
                $greske[]="Morate uneti pitanje";
            }
            if(!preg_match("/^[^@]+$/", $odgovori)){
                $code=400;
                $greske[]="Morate uneti odgovore";
            }
            if(count($greske)){
                $data=$greske;
            }else{

                $odgovoriNiz=explode(';', $odgovori);

                $upitInsertPitanje="INSERT INTO anketa VALUES(NULL, :pitanje, 0)";
                $priprema=$konekcija->prepare($upitInsertPitanje);
                $priprema->bindParam(":pitanje", $pitanje);

                try{
                    $priprema->execute();
                    $idAnkete=$konekcija->lastInsertId();
                    $upitInsertOdgovori = "INSERT INTO odgovor VALUES(null, $idAnkete, :odgovor)";
                    $priprema2=$konekcija->prepare($upitInsertOdgovori);
                    
                    foreach($odgovoriNiz as $n){
                        $priprema2->bindParam(":odgovor", $n);
                        $priprema2->execute();
                    }
                    $code=201;
                    $data=["poruka"=>"Uspešno ste uneli anketu"];
                }
                catch(PDOException $ex){
                        $code=500;
                        $data=["Došlo je do greške, molimo Vas pokušajte kasnije".$ex->getMessage()];
                }
            }
        }
        if($btn=="aktiviraj"){
            $id=$_POST["idAnketa"];
            $greske=[];
            if($id==0){
                $code=400;
                $greske[]="Morate izabrati anketu";
            }
            if(count($greske)){
                $data=$greske;
            }else{
                $aktiviraj="UPDATE anketa SET aktivna=1 WHERE idAnketa=:id";
                $deaktiviraj="UPDATE anketa SET aktivna=0 WHERE idAnketa<>:id";

                $pripremaAktiviraj=$konekcija->prepare($aktiviraj);
                $pripremaDeaktiviraj=$konekcija->prepare($deaktiviraj);

                $pripremaAktiviraj->bindParam(":id", $id);
                $pripremaDeaktiviraj->bindParam(":id", $id);

                try{
                    $pripremaAktiviraj->execute();
                    $pripremaDeaktiviraj->execute();
                    $code=200;
                    $data=["poruka"=>"Uspešno ste aktivirali anketu"];
                }
                catch(PDOException $ex){
                    $code=500;
                    $data=["Došlo je do greške, molimo Vas pokušajte kasnije"];
                }
            }
        }
        if($btn=="prikazi"){
            $id=$_POST["idAnketa"];
            $greske=[];
            if($id==0){
                $code=400;
                $greske[]="Morate izabrati anketu";
            }
            if(count($greske)){
                $data=$greske;
            }else{
                $upitUkupno="SELECT COUNT(g.idOdgovor) as ukupno FROM odgovor o LEFT OUTER JOIN glasanje g on o.idOdgovor=g.idOdgovor WHERE o.idAnketa=:id";
                $upit="SELECT o.odgovor, COUNT(g.idOdgovor) as broj FROM odgovor o LEFT OUTER JOIN glasanje g on o.idOdgovor=g.idOdgovor WHERE o.idAnketa=:id GROUP BY o.odgovor ORDER BY g.idOdgovor";

                $pripremaRez=$konekcija->prepare($upit);
                $pripremaRez->bindParam(":id", $id);
                
                $pripremaUkupno=$konekcija->prepare($upitUkupno);
                $pripremaUkupno->bindParam(":id", $id);

                try{
                    $pripremaUkupno->execute();
                    $ukupno=$pripremaUkupno->fetchAll();
                    $pripremaRez->execute();
                    $rezultati=$pripremaRez->fetchAll();
                    $code=200;
                    $data=["ukupno"=>$ukupno, "rezultati"=>$rezultati];
                }
                catch(PDOException $ex){
                    $code=500;
                    $data=["Došlo je do greške, molimo Vas pokušajte kasnije"];
                }
            }
        }
    }
    echo json_encode($data);
    http_response_code($code);
?>