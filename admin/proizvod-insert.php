<?php
    header("Content-type: application/json");
    session_start();
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: ../errors/403.php");
    }else{
        require "../config/konekcija.php";
        require "functions-regExp.php";
        $data=null;
        $code=404;
        if(isset($_POST["insert"])){
            $naziv=$_POST["naziv"];
            $cena=$_POST["cena"];
            $datumPost=$_POST["datumPost"];
            if($datumPost==""){
                $datumPost=date("Y/m/d H:i:s");
            }
            $markaId=$_POST["marka"];
            $spec=json_decode($_POST["spec"], true);
            $greske=[];

            $slike="/php1sajt/images/proizvodi";
            $thumbnails="/php1sajt/images/proizvodi/thumbnail";
            if(!isset($_FILES["slika"])){
                $code=400;
                $greske[]="Morade priložiti sliku proizvoda";
            }else{
                $slika=$_FILES["slika"]["name"];
                $greske=array_merge($greske, proveraSlika($_FILES["slika"]));
            }
            if(!isset($_FILES["thumbnail"])){
                $code=400;
                $greske[]="Morade priložiti thumbnail sliku proizvoda";
            }else{
                $thumbnail=$_FILES["thumbnail"]["name"];
                $greske=array_merge($greske, proveraThumbnail($_FILES["thumbnail"]));
            }

            $greske=array_merge($greske, updateInsertRegExp($naziv, $cena, $spec));

            if(count($greske)){
                $data=$greske;
            }else{
                $upit="INSERT INTO proizvodi VALUES(NULL, :naziv, :cena, :datumPost, :idMarka)";
                $upit2="INSERT INTO slike VALUES(NULL, :putanja, :opis, 1)";
                $upit3="INSERT INTO slike VALUES(NULL, :putanja, :opis, 2)";
                $upit4="INSERT INTO specifikacije VALUES(NULL, :idP, :idK, :vr)";
                
                $priprema=$konekcija->prepare($upit);
                $priprema->bindParam(":naziv", $naziv);
                $priprema->bindParam(":cena", $cena);
                $priprema->bindParam(":datumPost", $datumPost);
                $priprema->bindParam(":idMarka", $markaId);

                $priprema2=$konekcija->prepare($upit2);
                $priprema2->bindParam(":putanja", $slika);
                $priprema2->bindParam(":opis", $slika);

                $priprema3=$konekcija->prepare($upit3);
                $priprema3->bindParam(":putanja", $thumbnail);
                $priprema3->bindParam(":opis", $thumbnail);
                
                $priprema4=$konekcija->prepare($upit4);
                try{
                    $konekcija->beginTransaction();
                    $priprema->execute();
                    $idProizvod=$konekcija->lastInsertId();
                    $priprema2->execute();
                    if(!move_uploaded_file($_FILES["slika"]["tmp_name"], "../images/proizvodi/$slika")){
                        $code=500;
                        $greske=["Došlo je do greške pri upload-ovanju fajla, molimo Vas pokušajte kasnije"];
                    }
                    $idSlika=$konekcija->lastInsertId();
                    $konekcija->query("INSERT INTO slika_proizvod VALUES(NULL, '$idSlika', '$idProizvod')");
                    $priprema3->execute();
                    if(!move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "../images/proizvodi/thumbnail/$thumbnail")){
                        $code=500;
                        $greske=["Došlo je do greške pri upload-ovanju fajla, molimo Vas pokušajte kasnije"];
                    }
                    $idThumbnail=$konekcija->lastInsertId();
                    $konekcija->query("INSERT INTO slika_proizvod VALUES(NULL, '$idThumbnail', '$idProizvod')");
                    foreach($spec as $i=>$s){
                        $priprema4->bindParam(":vr", $s);
                        $priprema4->bindParam(":idP", $idProizvod);
                        $priprema4->bindParam(":idK", $i);
                        $priprema4->execute();
                    }
                    $konekcija->commit();
                    $code=201;
                    
                    
                }catch(PDOException $ex){
                    $code=500;
                    $data=["Došlo je do greške, molimo Vas pokušajte kasnije".$ex->getMessage()];
                }

            }
        }
        echo json_encode($data);
        http_response_code($code);
    }
?>