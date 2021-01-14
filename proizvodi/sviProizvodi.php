<?php
    session_start();
    header("Content-type: application/json");
    require_once "../functions.php";
    $data=null;
    $code=404;
    $offset=0;
    $limit=6;
    $upit="SELECT COUNT(*) as ukupno FROM proizvodi p INNER JOIN marke m ON p.idMarka=m.idMarka";
    if(isset($_POST["page"])){
        $offset=($_POST["page"]-1)*$limit;
    }
    if(isset($_POST["katNiz"])){
        $niz=$_POST["katNiz"];
        $marke=implode($niz, ", ");
        $upit.=" WHERE p.idMarka IN ($marke)";
    }
    if(isset($_POST["sort"])){
        $sort=$_POST["sort"];
    }
    if(isset($_POST["search"])){
        $search=$_POST["search"];
        $upit.=" AND (UPPER(p.naziv) LIKE UPPER('%$search%') OR UPPER(m.nazivMarka) LIKE UPPER('%$search%'))";
    }
    try{
        $ukupno=$konekcija->query($upit)->fetch()->ukupno;
        $brojStranica = ceil($ukupno / $limit);
        $data=["brojStranica"=>$brojStranica];
        $data["proizvodi"]=prikaziSveProizvode($offset, $niz, $sort, $search);
        $code=200;
    }
    catch(PDOException $e){
        $code=500;
    }
    
   
    echo json_encode($data);
    http_response_code($code);
?>