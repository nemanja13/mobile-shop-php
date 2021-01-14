<?php
    require "config/konekcija.php";
    error_reporting(E_ERROR | E_PARSE);
    function prikaziMeni(){
        global $konekcija;
        $upit="SELECT * FROM navigacija ORDER BY pozicija";
        $rez=$konekcija->query($upit)->fetchAll();
        $upit2="SELECT * FROM navigacija ORDER BY pozicija DESC LIMIT 3";
        $rez2=$konekcija->query($upit2)->fetchAll();
        $ispis="<ul>";
        foreach($rez as $r){
            if(isset($_SESSION["korisnik"])){
                if($_SESSION["korisnik"]->ulogaNaziv=="korisnik" && (strpos($r->link, "korisnik") || strpos($r->link, "index.php")!==false)){
                    if($r->tekst=="Korpa")
                        $ispis.="<li><a href='$r->link' class='korpa'><i class='fas fa-shopping-cart'></i><span class='broj'></span></a></li>";
                    else
                        $ispis.="<li><a href='$r->link'>$r->tekst</a></li>";
                }
                elseif($_SESSION["korisnik"]->ulogaNaziv=="admin" && (strpos($r->link, "admin") || $r->link=="index.php")){
                    $ispis.="<li><a href='$r->link'>$r->tekst</a></li>";
                }
            }elseif( strpos($r->link, "korisnik") || strpos($r->link, "index.php")!==false){
                if($r->tekst=="Korpa"){
                    continue;    
                }
                $ispis.="<li><a href='$r->link'>$r->tekst</a></li>";
            }
           
        }
        $ispis.="</ul>";
        $ispis.="<ul>";
        foreach($rez2 as $r){
            if($r->tekst=="Login" && isset($_SESSION["korisnik"])){
                continue;
            }
            if($r->tekst=="Registracija" && isset($_SESSION["korisnik"])){
                continue;
            }
            if($r->tekst=="Logout" && !isset($_SESSION["korisnik"])){
                continue;
            }
            $ispis.="<li><a href='$r->link'>$r->tekst</a></li>";
        }
        $ispis.="</ul>";
        return $ispis;
    }
    function prikaziPodMeni(){
        global $konekcija;
        $upit="SELECT * FROM navigacija ORDER BY pozicija LIMIT 5";
        $rez=$konekcija->query($upit)->fetchAll();
        $ispis="<ul>";
        foreach($rez as $r){
            if($r->tekst=== "Korpa"){
                $ispis.="<li><a href='$r->link' class='korpa'><i class='fas fa-shopping-cart'></i><span class='broj'></span></a></li>";
            }else{
                $ispis.="<li><a href='$r->link'>$r->tekst</a></li>";
            }
        }
        $ispis.="</ul>";
        return $ispis;
    }
    function prikaziNajnovije(){
        global $konekcija;
        $upit="SELECT p.idProizvod, p.naziv, p.cena, s.putanja, s.opis, m.nazivMarka FROM proizvodi p INNER JOIN slika_proizvod sp ON p.idProizvod=sp.idProizvod INNER JOIN slike s ON sp.idSlika=s.idSlika INNER JOIN marke m  ON p.idMarka=m.idMarka WHERE idTipSlike=1  ORDER BY p.datumPost DESC LIMIT 4";
        $rez=$konekcija->query($upit)->fetchAll();
        $ispis="";
        foreach($rez as $r){
            $r->specifikacije=prikazKarakteristika($r->idProizvod);
        }
        return $rez;
    }
    function prikazKarakteristika($id){
        global $konekcija;
        $upit2="SELECT s.vrednost, k.nazivKarakteristika FROM specifikacije s INNER JOIN karakteristike k ON s.idKarakteristika=k.idKarakteristika WHERE idProizvod=$id LIMIT 4";
        $rezultat=$konekcija->query($upit2)->fetchAll();
        return $rezultat;
    }
    function prikaziSveProizvode($offset=0, $niz=[], $sort=0, $search=""){
        global $konekcija;
        $limit=6;
        $upit="SELECT p.idProizvod, p.naziv, p.cena, s.putanja, s.opis, m.nazivMarka FROM proizvodi p INNER JOIN slika_proizvod sp ON p.idProizvod=sp.idProizvod INNER JOIN slike s ON sp.idSlika=s.idSlika INNER JOIN marke m  ON p.idMarka=m.idMarka WHERE idTipSlike=1";
        if(count($niz)!=0)
        {
            $marke=implode($niz, ", ");
            $upit.=" AND p.idMarka IN ($marke)";
        }
        if($search!=""){
            $upit.=" AND (UPPER(p.naziv) LIKE UPPER('%$search%') OR UPPER(m.nazivMarka) LIKE UPPER('%$search%'))";
        }
        if($sort!=0){
            if($sort==1){
                $upit.=" ORDER BY m.nazivMarka, p.naziv";
            }else if($sort==2){
                $upit.=" ORDER BY m.nazivMarka DESC, p.naziv DESC";
            }else if($sort==3){
                $upit.=" ORDER BY p.cena";
            }else{
                $upit.=" ORDER BY p.cena DESC";
            }
        }
        $upit.=" LIMIT $limit OFFSET $offset";
        $rez=$konekcija->query($upit)->fetchAll();
        $ispis="";
        foreach($rez as $r){
            $r->specifikacije=prikazKarakteristika($r->idProizvod);
        }
        return $rez;
    }
    function proizvodiKorpa(){
        global $konekcija;
        $upit="SELECT p.idProizvod, p.naziv, p.cena, s.putanja, p.datumPost, s.opis, m.nazivMarka FROM proizvodi p INNER JOIN slika_proizvod sp ON p.idProizvod=sp.idProizvod INNER JOIN slike s ON sp.idSlika=s.idSlika INNER JOIN marke m  ON p.idMarka=m.idMarka WHERE idTipSlike=2";
        $rez=$konekcija->query($upit)->fetchAll();
        return $rez;
    }
    function korisnici(){
        global $konekcija;
        $upit="SELECT k.idKorisnik, k.ime, k.prezime, k.email, k.adresa, k.grad, k.postanskiBroj, k.telefon, u.ulogaNaziv FROM korisnici k INNER JOIN uloge u ON k.idUloga=u.idUloga";
        $rez=$konekcija->query($upit)->fetchAll();
        return $rez;
    }
    function porudzbineKorisnik(){
        global $konekcija;
        $upit="SELECT DISTINCT k.idKorisnik, k.ime, k.prezime, k.email, k.adresa, k.grad, k.postanskiBroj, k.telefon FROM porudzbine p INNER JOIN korisnici k ON p.idKorisnik=k.idKorisnik";
        $rez=$konekcija->query($upit)->fetchAll();
        return $rez;
    }
    function mejlovi(){
        global $konekcija;
        $upit="SELECT * FROM mejl";
        $rez=$konekcija->query($upit)->fetchAll();
        return $rez;
    }
?>