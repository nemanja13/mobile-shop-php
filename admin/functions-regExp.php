<?php

function updateInsertRegExp($naziv, $cena, $spec)
{
    
    $greske=[];
    $reNaziv="/[\w\d\s]+/";
    $reCena="/\d+/";

    if(!preg_match($reNaziv, $naziv)){
        $greske[]="Pogrešno unet naziv proizvoda";
    }
    if(!preg_match($reCena, $cena)){
        $greske[]="Pogrešno uneta cena proizvoda";
    }
    if(!preg_match("/^\d([\.,]\d)?\".*$/",$spec[1])){
        $greske[]="Pogrešan format ( <i>Morate uneti veličinu ekrana, npr. 6,4\"</i> )";
    }
    if(!preg_match("/^\d{1,2}\s?GB$/",$spec[2])){
        $greske[]="Pogrešan format ( <i>Morate uneti količinu RAM memorije, npr. 6 GB</i> )";
    }
    if(!preg_match("/^\d{2,3}\s?GB$/",$spec[3])){
        $greske[]="Pogrešan format ( <i>Morate uneti količinu Interne memorije, npr. 32 GB</i> )";
    }
    if(!preg_match("/^\d{1,3}\s?MP(\s?[\-\+]\s?\d{1,3}\s?MP)*\/\d{1,3}\s?MP(\s?[\-\+]\s?\d{1,3}\s?MP)*$/",$spec[4])){
        $greske[]="Pogrešan format ( <i>Morate uneti kvalitet kamera, npr. 12 MP/8 MP</i> )";
    }
    if(!preg_match("/^.*(Dual|Quad|Octa|Hexa)(\sCore|-core).*$/",$spec[5])){
        $greske[]="Pogrešan format ( <i>Morate uneti procesor telefona, npr. Octa-core</i> )";
    }
    if(!preg_match("/^(Android|iOS|EMUI)(\s?\d{1,2}([\.,]\d{1,2})?(\s?\(\w{3,12}\))?)?$/",$spec[6])){
        $greske[]="Pogrešan format ( <i>Morate uneti operativni sistem telefona, npr. Android</i> )";
    }
    if(!preg_match("/^\d{4}\s?mAh$/",$spec[7])){
        $greske[]="Pogrešan format ( <i>Morate uneti kapacitet baterije telefona, npr. 4000 mAh</i> )";
    }
    if(!preg_match("/^\d{1,3}([\.,]\d{1,2})?\s?mm(\s?[xX]\s?\d{1,3}([\.,]\d{1,2})?\s?mm){2}$/",$spec[8])){
        $greske[]="Pogrešan format ( <i>Morate uneti dimenzije telefona, npr. 158.5 mm x 74.7 mm x 7.7 mm</i> )";
    }
    if(!preg_match("/^\d{1,4}\s?g$/",$spec[9])){
        $greske[]="Pogrešan format ( <i>Morate uneti težinu telefona u gramima, npr. 158 g</i> )";
    }
    if(!preg_match("/^\d{3,4}\s?[xX]\s?\d{3,4}\s?(px|Px|PX).*$/",$spec[10])){
        $greske[]="Pogrešan format ( <i>Morate uneti rezoluciju telefona, npr. 1080 x 2340 px</i> )";
    }
    if(!preg_match("/^\w{3,12}(\s\w{3,12})*$/",$spec[11])){
        $greske[]="Pogrešan format ( <i>Morate uneti boju telefona, npr. Crna )";
    }
    return $greske;
}
function proveraSlika($slikaFile){
    $slika=$slikaFile["name"];
    $slikaVelicina=$slikaFile["size"];
    $ekstenzije=["jpg", "png", "jpeg", "webp"];
    $greske=[];
    $ekstenzija=strtolower(pathinfo($slika, PATHINFO_EXTENSION));
    $velicina=500*1024;
    if($slikaVelicina>$velicina){
        $greske[]="Veličina slike ne sme biti veća od 500 KB";
    }
    if(!in_array($ekstenzija, $ekstenzije)){
        $greske[]="Fajl mora biti jpg, png ili webp formata";
    }
    return $greske;
}
function proveraThumbnail($thumbnailFile){
    $thumbnail=$thumbnailFile["name"];
    $slikaVelicina=$thumbnailFile["size"];
    $ekstenzije=["jpg", "png", "jpeg", "webp"];
    $greske=[];
    $ekstenzija=strtolower(pathinfo($thumbnail, PATHINFO_EXTENSION));
    $velicina=15*1024;
    if($slikaVelicina>$velicina){
        $greske[]="Veličina thubnail slike ne sme biti veća od 15 KB";
    }
    if(!in_array($ekstenzija, $ekstenzije)){
        $greske[]="Fajl mora biti jpg, png ili webp formata";
    }
    return $greske;
}
?>