<?php
    session_start();
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: errors/403.php");
    }
    require "functions.php";
    require "fixed/head.php";
    require "config/konekcija.php";

    $kupci=porudzbineKorisnik();
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid" id="proizvodi">
        <table id="tabela">
            <tr>
                <th>Redni broj</th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Email</th>
                <th>Podešavanja</th>
            </tr>
            <?php foreach($kupci as $i=>$k):?>
                <tr>
                    <td><?=++$i?></td>
                    <td><?=$k->ime?></td>
                    <td><?=$k->prezime?></td>
                    <td><?=$k->email?></td>
                    <td><a href="<?=$_SERVER["PHP_SELF"]."?id=".$k->idKorisnik?>"> Detaljnije</a></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    <?php
        if(isset($_GET["id"])):
            $id=$_GET["id"];
            $tip=2;
            $upitKupac="SELECT * FROM korisnici WHERE idKorisnik=:id";
            $upit="SELECT p.idProizvod, p.naziv, p.cena, s.putanja, s.opis, m.nazivMarka, dp.kolicina, dp.datum FROM proizvodi p INNER JOIN slika_proizvod sp ON p.idProizvod=sp.idProizvod INNER JOIN slike s ON sp.idSlika=s.idSlika INNER JOIN marke m ON p.idMarka=m.idMarka INNER JOIN detaljiporudzbine dp ON dp.idProizvod=p.idProizvod INNER JOIN porudzbine po ON po.idPorudzbina=dp.idPorudzbina WHERE idTipSlike=:tip AND po.idKorisnik=:id";
            $priprema=$konekcija->prepare($upit);
            $priprema->bindParam(":id", $id);
            $priprema->bindParam(":tip", $tip);
            $priprema->execute();

            $priprema2=$konekcija->prepare($upitKupac);
            $priprema2->bindParam(":id", $id);
            $priprema2->execute();

            $kupac=$priprema2->fetch();
            $proizvodi=$priprema->fetchAll();
        ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12" id="sadrzaj">
                        <form id="registracija" class="col-lg-10 col-11 flex3">
                            <div class="col-12 m-3">
                                <h4>Kupac</h4>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Ime:</label>
                            <input type="text" readonly="readonly" value="<?=$kupac->ime?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Prezime:</label>
                            <input type="text" readonly="readonly" value="<?=$kupac->prezime?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Email:</label>
                            <input type="text" readonly="readonly" value="<?=$kupac->email?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Telefon:</label>
                            <input type="text" readonly="readonly" value="<?=$kupac->telefon?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Grad:</label>
                            <input type="text" readonly="readonly" value="<?=$kupac->grad?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Adresa:</label>
                            <input type="text" readonly="readonly" value="<?=$kupac->adresa?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Poštanski broj:</label>
                            <input type="text" readonly="readonly" value="<?=$kupac->postanskiBroj?>"/>
                            </div>
                        </form>
                        <table id="tabela">
                            <tr>
                                <th>Slika</th>
                                <th>Naziv</th>
                                <th>Cena</th>
                                <th>Datum poručivanja</th>
                                <th>Količina</th>
                            </tr>
                            <?php foreach($proizvodi as $p):?>
                                <tr>
                                    <td><img class="thumbnail" src="images/proizvodi/thumbnail/<?=$p->putanja?>" alt="<?=$p->opis?>"/></td>
                                    <td><?=$p->nazivMarka?> <?=$p->naziv?></td>
                                    <td><?=preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", preg_replace("/\./",",",$p->cena))?> RSD</td>
                                    <td><?=$p->datum?></td>
                                    <td><?=$p->kolicina?></td>
                                </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif;?>
    
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
        
</body>
</html>