<?php
    session_start();
    require_once "fixed/head.php";
?>
<body>
    <?php require_once "fixed/nav.php"; 
    require "config/konekcija.php";
    if(isset($_SESSION["korisnik"])){
        $klasa="dodajUKorpu";
    }
    else{
        $klasa="disable";
    }
    if(isset($_GET["id"])){
        $id=$_GET["id"];
        $upit="SELECT p.idProizvod, p.naziv, p.cena, s.putanja, s.opis, m.nazivMarka FROM proizvodi p INNER JOIN slika_proizvod sp ON p.idProizvod=sp.idProizvod INNER JOIN slike s ON sp.idSlika=s.idSlika INNER JOIN marke m  ON p.idMarka=m.idMarka WHERE idTipSlike=1 AND p.idProizvod=:id";
        $upit2="SELECT k.nazivKarakteristika, spec.vrednost FROM specifikacije spec INNER JOIN karakteristike k ON k.idKarakteristika=spec.idKarakteristika WHERE spec.idProizvod=:id";
        $priprema=$konekcija->prepare($upit);
        $priprema->bindParam(":id", $id);
        $priprema2=$konekcija->prepare($upit2);
        $priprema2->bindParam(":id", $id);
        try{
            $priprema->execute();
            $priprema2->execute();
            if($priprema->rowCount()==1){
                $proizvod=$priprema->fetch();
                $specifikacije=$priprema2->fetchAll();
            }else{
                header("Location: 404.php");
            }
        }catch(PDOException $ex){
           header("Location: 404.php");
        }
    }else{
        header("Location: 404.php");
    }
    ?>
    <div class="container-fluid">
        <div class="row flex p-4" id="proizvod">
            <div class="col-lg-4 slikaProizvod flex2">
                <img src="images/proizvodi/<?=$proizvod->putanja?>" alt="<?=$proizvod->opis?>"/>
            </div>
            <div class="col-lg-7 col-10 mx-auto">
                <div class="col-lg-12">
                    <h1><?=$proizvod->nazivMarka?> <?=$proizvod->naziv?></h1>
                    <ul class="my-4">
                        <?php for($i=0; $i<4; $i++):?>
                            <li><?=$specifikacije[$i]->nazivKarakteristika?> : <?=$specifikacije[$i]->vrednost?></li>
                        <?php endfor;?>
                    </ul>
                    <p class="naStanju">&bull; Na stanju</p>
                </div>
                <div class="cenaProizvod">
                    <p class="cenaProizvod"><?=preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", substr($proizvod->cena, 0, -3))?> RSD</p>
                    <h3 class="besplatnaDostava">+ Besplatna dostava</h3>
                    <a href="#" data-id="<?=$proizvod->idProizvod?>" class="<?=$klasa?>"><i class="fas fa-shopping-cart"></i> Dodaj u korpu</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 pl-3 m-3"><h2 id="proizvodiTelefoni">Specifikacije</h2></div>
            <div class="col-12">
                <table class="col-11 mx-auto" id="specifikacije">
                    <?php for($i=0; $i<count($specifikacije); $i++):?>
                        <tr><td class="tdNaslov"><?=$specifikacije[$i]->nazivKarakteristika?></td> <td><?=$specifikacije[$i]->vrednost?></td></tr>
                    <?php endfor;?>
                </table>
            </div>
        </div>
    </div>

    <?php require_once "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
</body>
</html>