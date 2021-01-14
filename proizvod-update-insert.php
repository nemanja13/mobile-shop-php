<?php 
    session_start();
    ob_start();
    require "config/konekcija.php";
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: errors/403.php");
    }
    if(isset($_GET["id"])){
        $id=$_GET["id"];
        $upit="SELECT idProizvod FROM proizvodi WHERE idProizvod=:id";
        $priprema=$konekcija->prepare($upit);
        $priprema->bindParam(":id", $id);
        $priprema->execute();
        if($priprema->rowCount()!=1){
            header("Location: errors/404.php");
        }

        $upitSelect="SELECT * FROM proizvodi WHERE idProizvod=:id";
        $upitMarke="SELECT * FROM marke m";
        $upitSpecifikacije="SELECT k.nazivKarakteristika, spec.vrednost FROM specifikacije spec INNER JOIN karakteristike k ON k.idKarakteristika=spec.idKarakteristika WHERE spec.idProizvod=:id";
        $upitSlike="SELECT s.putanja, s.opis FROM slike s INNER JOIN slika_proizvod sp ON s.idSlika=sp.idSlika WHERE sp.idProizvod=:id";

        $marke=$konekcija->query($upitMarke)->fetchAll();
        $priprema2=$konekcija->prepare($upitSelect);
        $priprema2->bindParam(":id", $id);
        $priprema2->execute();
        $proizvod=$priprema2->fetch();
        
        $priprema3=$konekcija->prepare($upitSpecifikacije);
        $priprema3->bindParam(":id", $id);
        $priprema3->execute();
        $specifikacije=$priprema3->fetchAll();
        
        $priprema4=$konekcija->prepare($upitSlike);
        $priprema4->bindParam(":id", $id);
        $priprema4->execute();
        $slike=$priprema4->fetchAll();

    }else{
        $specifikacije=$konekcija->query("SELECT nazivKarakteristika FROM  karakteristike k")->fetchAll();
        $marke=$konekcija->query("SELECT * FROM marke m")->fetchAll();
    }
    require "fixed/head.php";
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid" id="proizvodi">
        <form id="registracija" action="" method="POST" class="col-lg-10 col-11 flex3" enctype="multipart/form-data">
            <div class="col-12 m-3">
                <h4>Proizvod</h4>
            </div>
            <div class="col-10 col-sm-5">
            <label for="naziv">Naziv</label>
            <input type="text" id="naziv" name="naziv" value="<?=$proizvod->naziv?>"/>
            <span class='greska'>Pogrešan format</span>
            </div>

            <div class="col-10 col-sm-5">
            <label for="datumPost">Datum postavljanja: </label>
            <input type="date" id="datumPost" name="datumPost" value="<?=substr($proizvod->datumPost,0,10)?>"/>
            <span class='greska'>Pogrešan format (Morate izabrati datum)</span>
            </div>

            <div class="col-10 col-sm-5">
            <label for="cena">Cena: </label>
            <input type="number" id="cena" name="cena" value="<?=$proizvod->cena?>"/>
            <span class='greska'>Pogrešan format (Morate uneti cenu)</span>
            </div>

            <div class="col-10 col-sm-5">
            <label for="marka">Marka</label>
            <select id="marka">
                <?php foreach($marke as $marka){
                    if(($marka->idMarka)==($proizvod->idMarka)){
                        echo "<option selected='selected' value='$marka->idMarka'>$marka->nazivMarka</option>";
                    }else{
                    echo "<option value='$marka->idMarka'>$marka->nazivMarka</option>";
                    }
                }?>
            </select>
            </div>
            <div class="col-12 m-3">
                <h4>Specifikacije</h4>
            </div>
            <?php foreach($specifikacije as $i => $s):?>
                <div class="col-10 col-sm-5">
                <label for="spec<?=$i?>"><?=$s->nazivKarakteristika?></label>
                <input type="text" id="spec<?=$i?>" name="<?=$s->nazivKarakteristika?>" value='<?=$s->vrednost?>'/>
                <span class="greska"></span>
                </div>
            <?php endforeach;?>
            <div class="col-12 m-3">
                <h4>Slike</h4>
            </div>
            <div class="col-10 col-sm-5">
            <label for="slika">Slika : </label>
            <input type="file" id="slika" name="slika"/>
            </div>

            <div class="col-10 col-sm-5">
            <label for="thumbnail">Slika thumbnail : </label>
            <input type="file" id="thumbnail" name="thumbnail"/>
            </div>

            <div class="col-11">
                <button id="posalji" name="izmeni" type="button" value="<?=$proizvod->idProizvod?>">Potvrdi</button>
            </div>
        </form>
    </div>
    <div class="container-fluid"><div class="row"><div id="poruka" class="greska col-10 mx-auto p-4"></div></div></div>
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
        
</body>
</html>