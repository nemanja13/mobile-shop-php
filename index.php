<?php
    session_start();
    require_once "fixed/head.php";
?>
<body>
   <?php 
        require_once "fixed/nav.php"; 
        require_once "fixed/slajder.php"; 
    ?>
    <div class="container-fluid" id="containerSadrzaj">
        <div class="row">
            <div class="col-12" id="najnoviji">
                <h2>Najnoviji proizvodi</h2>
            </div>
            <div class="col-12 flex" id="sadrzaj">
                
            </div>
        </div>
    </div>
    <div class="container-fluid" id="kompanija">
    </div>
    <div class="container-fluid" id="oNama">
        <h2 class="podnaslov">O nama</h2>
        <div class="row flex3">
            <div class="col-10 col-md-3 blokONama">
                <img src="images/vizijaMala.jpg"/>
                <div>
                    <h3>Vizija</h3>
                    <p>Vizija kompanije Mobile shop je da pruži najviši nivo usluge na domaćem tržištu uz stalne inovacije i obogaćivanje proizvodnog asortimana.</p>
                </div>
            </div>
            <div class="col-10 col-md-3 blokONama">
                <img src="images/kompanijaMala.jpg"/>
                <div>
                    <h3>Kompanija</h3>
                    <p>Od svog osnivanja 2005. godine, kompanija Mobile shop je jedan od lidera na tržištu tehnike u Srbiji.</p>
                </div> 
            </div>
            <div class="col-10 col-md-3 blokONama">
                <img src="images/callCentarMala.jpg"/>
                <div>
                    <h3>Maloprodajna mreža</h3>
                    <p>Mobile shop maloprodajna mreža pokriva preko 25 gradova širom Srbije. Mobile shop ima i Call Centar koji je na raspolaganju kupcima.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="kontakt">
        <h2 class="podnaslov">Kontaktirajte Nas</h2>
        <div class="row flex">       
            <div class="col-12 col-md-5 blokKontakt">
                <h3><i class="fas fa-desktop"></i> Kontaktirajte nas putem e-maila</h3>
                <form id="formaKontakt" action="#" method="POST" onSubmit="return provera();">
                    <label for="ime">Ime</label>
                    <input type="text" id="ime" name="ime"/>
                    <span id="greskaIme" class="greska">Pogrešno uneto ime <br/> <i>Format (prvo veliko slovo, reč ne sme biti duza od 20 karaktera)</i></span>
                    <label for="prezime">Prezime</label>
                    <input type="text" id="prezime" name="prezime"/>
                    <span id="greskaPrezime" class="greska">Pogrešno uneto prezime <br/> <i>Format (prvo veliko slovo, reč ne sme biti duza od 20 karaktera, osoba može imati više prezimena)</i></span>
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"/>
                    <span id="greskaEmail" class="greska">Pogrešno unet email <br/> <i>Format (standardan format)</i></span>
                    <label for="telefon">Telefon</label>
                    <input type="text" id="telefon" name="telefon"/>
                    <span id="greskaTelefon" class="greska">Pogrešan format <br/> <i>Format (06********, ukupno cifara 9/10)</i></span>
                    <label for="razlogKontakta">Razlog kontakta</label>
                    <select id="razlogKontakta" name="razlog">
                        <option value="0">Izaberite</option>
                        <option value="1">Prodaja</option>
                        <option value="2">Isporuka robe</option>
                        <option value="3">Reklamacije</option>
                        <option value="4">Vaše primedbe i sugestije</option>
                        <option value="5">Ostala pitanja</option>
                    </select>
                    <span id="greskaRazlogKontakta" class="greska">Morate izabrati razlog kontakta</span>
                    <label for="porukaForma">Poruka</label>
                    <textarea id="porukaForma"></textarea>
                    <span id="greskaPoruka" class="greska">Morate uneti poruku</span>
                    <div class="cistac"></div>
                    <button type="button" value="posalji" id="posalji">Pošalji</button>
                </form>
                <div id="poruka" class="col-10 mx-auto p-4"></div>
            </div>
            <div class="col-12 col-md-6 blokKontakt">
                <div class="flex2">
                    <div class="col-12 mb-4">
                        <div id="rezultatiAnkete"></div>
                        <?php if(isset($_SESSION["korisnik"])){
                            $upit="SELECT * FROM glasanje g INNER JOIN odgovor o ON g.idOdgovor=o.idOdgovor INNER JOIN anketa a ON a.idAnketa=o.idAnketa WHERE idKorisnik={$_SESSION['korisnik']->idKorisnik} AND aktivna=1";
                            $rezultat=$konekcija->query($upit)->fetchAll();
                            if($rezultat==false){
                                echo "<button id='glasaj' type='button' value='{$_SESSION['korisnik']->idKorisnik}'>Glasaj</button>";
                            }else{
                                echo '<button type="button" class="disableGlasao">Glasaj</button>';
                            }
                        }else{
                            echo '<button type="button" class="disable">Glasaj</button>';
                        }?>
                        <button id="rezultati" class="ml-2" type="button" value="prikazi">Rezultati</button>
                    </div>
                    <div class="col-12 flex3">
                        <div class="col-6">
                            <h3><i class="fas fa-phone"></i> Pozovite</h3>
                            <p>+38111 753 22 55</p>
                            <p>+38111 753 22 66</p>
                            <h4>Opšta podrška</h4>
                            <p>Ponedeljak - Petak : 09h do 18h</p>
                        </div>
                        <div class="col-6">
                            <h3><i class="fas fa-map-marker-alt"></i>  Lokacija</h3>
                            <h4>Adresa:</h4>
                            <p>Nemanjina 10</p>
                            <h4>Servis Lokacije</h4>
                            <p>Kneza Milana Obrenovića 14</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="mapa">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2830.7511505001744!2d20.457362115498128!3d44.806259879098754!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x475a7aa848ddc625%3A0x36e7d5f2cd3e83eb!2z0J3QtdC80LDRmtC40L3QsCAxMCwg0JHQtdC-0LPRgNCw0LQ!5e0!3m2!1ssr!2srs!4v1581967024124!5m2!1ssr!2srs" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
    </div>
    <?php require_once "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
    <?php
        if(isset($_SESSION["korisnik"])):
    ?>
    <script>
        prikazNajnovijih(true);
    </script>
        <?php endif;?>
</body>
</html>