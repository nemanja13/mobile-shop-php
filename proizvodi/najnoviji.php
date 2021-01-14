<?php
    session_start();
    ob_start();
    header("Content-type: application/json");
    require_once "../functions.php";
    $data=null;
    $code=404;
    try{
        $data=prikaziNajnovije();
        $code=200;
    }
    catch(PDOException $e){
        $code=500;
    }
    echo json_encode($data);
    http_response_code($code);
?>