<?php 
    session_start();
    require "../config/konekcija.php";
    header("Content-type: application/json");
    $data=null;
    $code=404;
    $upitAnketa="SELECT idAnketa, pitanje FROM anketa WHERE aktivna=1";
    $upitOdgovor="SELECT idOdgovor, odgovor FROM anketa a INNER JOIN odgovor o ON a.idAnketa=o.idAnketa WHERE aktivna=1";
    $anketa=$konekcija->query($upitAnketa)->fetch();
    $odgovori=$konekcija->query($upitOdgovor)->fetchAll();
    if($anketa){
        $code=200;
        $data=["anketa"=>$anketa, "odgovori"=>$odgovori];
    }else{
        $code=500;
        $data=["Došlo je do greške, molimo Vas pokušajte kasnije"];
    }
    echo json_encode($data);
    http_response_code($code);
?>