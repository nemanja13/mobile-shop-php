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
        if(isset($_POST["id"])){
            $id=$_POST["id"];
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
            if(isset($_FILES["slika"])){
                $slika=$_FILES["slika"]["name"];
                $greske=array_merge($greske, proveraSlika($_FILES["slika"]));
            }
            if(isset($_FILES["thumbnail"])){
                $thumbnail=$_FILES["thumbnail"]["name"];
                $greske=array_merge($greske, proveraThumbnail($_FILES["thumbnail"]));
            }

            $greske=array_merge($greske, updateInsertRegExp($naziv, $cena, $spec));

            if(count($greske)){
                $data=$greske;
            }
            else{
                $upit="UPDATE proizvodi SET naziv=:naziv, cena=:cena, datumPost=:datumPost, idMarka=:idMarka WHERE idProizvod=:id";
                $upit2="UPDATE slike s INNER JOIN slika_proizvod sp ON sp.idSlika=s.idSlika SET s.putanja=:putanja WHERE s.idTipSlike=1 AND sp.idProizvod=:id";
                $upit3="UPDATE slike s INNER JOIN slika_proizvod sp ON sp.idSlika=s.idSlika SET s.putanja=:putanja WHERE s.idTipSlike=2 AND sp.idProizvod=:id";
                $upit4="UPDATE specifikacije SET vrednost=:vr WHERE idProizvod=:idP AND idKarakteristika=:idK";

                $priprema=$konekcija->prepare($upit);
                $priprema->bindParam(":id", $id);
                $priprema->bindParam(":naziv", $naziv);
                $priprema->bindParam(":cena", $cena);
                $priprema->bindParam(":datumPost", $datumPost);
                $priprema->bindParam(":idMarka", $markaId);

                $priprema2=$konekcija->prepare($upit2);
                $priprema2->bindParam(":id", $id);
                $priprema2->bindParam(":putanja", $slika);

                $priprema3=$konekcija->prepare($upit3);
                $priprema3->bindParam(":id", $id);
                $priprema3->bindParam(":putanja", $thumbnail);

                $priprema4=$konekcija->prepare($upit4);
                try{
                    $priprema->execute();
                    if($slika){
                        $priprema2->execute();
                        if(!move_uploaded_file($_FILES["slika"]["tmp_name"], "../images/proizvodi/$slika")){
                            $code=500;
                            $greske=["Došlo je do greške pri upload-ovanju fajla, molimo Vas pokušajte kasnije"];
                        }
                    }
                    if($thumbnail){
                        $priprema3->execute();
                        if(!move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "../images/proizvodi/thumbnail/$thumbnail")){
                            $code=500;
                            $greske=["Došlo je do greške pri upload-ovanju fajla, molimo Vas pokušajte kasnije"];
                        }
                    }
                    foreach($spec as $i=>$s){
                        $priprema4->bindParam(":vr", $s);
                        $priprema4->bindParam(":idP", $id);
                        $priprema4->bindParam(":idK", $i);
                        $priprema4->execute();
                    }
                    $code=204;
                    
                    
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