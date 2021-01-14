<?php
    session_start();
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: errors/403.php");
    }
    require "functions.php";
    require "fixed/head.php";

    $korisnici=korisnici();
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid" id="proizvodi">
        <table id="tabela">
            <tr>
                <th>Identifikacija</th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Email</th>
                <th>Uloga</th>
                <th>Podešavanja</th>
            </tr>
            <?php foreach($korisnici as $k):?>
                <tr>
                    <td><?=$k->idKorisnik?></td>
                    <td><?=$k->ime?></td>
                    <td><?=$k->prezime?></td>
                    <td><?=$k->email?></td>
                    <td><?=$k->ulogaNaziv?></td>
                    <td><a href="<?=$_SERVER["PHP_SELF"]."?id=".$k->idKorisnik?>"> Izmeni</a></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    <?php
        if(isset($_GET["id"])):
            require "config/konekcija.php";
            $id=$_GET["id"];
            $upit="SELECT * FROM korisnici k INNER JOIN uloge u ON k.idUloga=u.idUloga WHERE idKorisnik=:id";
            $priprema=$konekcija->prepare($upit);
            $priprema->bindParam(":id", $id);
            $priprema->execute();
            $korisnik=$priprema->fetch();
    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12" id="sadrzaj">
                    <form id="registracija" action="" method="POST" class="col-lg-10 col-11 flex3">

                        <div class="col-10 col-sm-5">
                        <label for="ime">Ime</label>
                        <input type="text" id="ime" name="ime" value="<?=$korisnik->ime?>"/>
                        <span class='greska'>Pogrešan format <br/> Format (prvo veliko slovo, ime ne sme biti duže od 20 karaktera)</span>
                        </div>

                        <div class="col-10 col-sm-5">
                        <label for="prezime">Prezime</label>
                        <input type="text" id="prezime" name="prezime" value="<?=$korisnik->prezime?>"/>
                        <span class='greska'>Pogrešan format <br/> Format (prvo veliko slovo, prezime ne sme biti duže od 20 karaktera, osoba može imati više prezimena)</span>
                        </div>

                        <div class="col-10 col-sm-5">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="<?=$korisnik->email?>"/>
                        <span class='greska'>Pogrešan format</span>
                        </div>

                        <div class="col-10 col-sm-5">
                        <label for="lozinka">Lozinka</label>
                        <input type="password" id="lozinka" name="lozinka"/>
                        <span class='greska'>Pogrešan format <br/> Format (lozinka mora sadržati 6 karaktera)</span>
                        </div>

                        <div class="col-10 col-sm-5">
                        <label for="telefon">Broj telefona</label>
                        <input type="text" id="telefon" name="telefon" value="<?=$korisnik->telefon?>"/>
                        <span class='greska'>Pogrešan format <br/> Format (06********, ukupno cifara 9/10)</span>
                        </div>

                        <div class="col-10 col-sm-5">
                        <label for="adresa">Adresa</label>
                        <input type="text" id="adresa" name="adresa" value="<?=$korisnik->adresa?>"/>
                        <span class='greska'>Pogrešan format</span>
                        </div>

                        <div class="col-10 col-sm-5">
                        <label for="grad">Grad/Mesto</label>
                        <input type="text" id="grad" name="grad" value="<?=$korisnik->grad?>"/>
                        <span class='greska'>Pogrešan format <br/> Format (prvo veliko slovo, naziv ne sme biti duži od 16 karaktera)</span>
                        </div>

                        <div class="col-10 col-sm-5">
                        <label for="postanskiBroj">Poštanski broj</label>
                        <input type="text" id="postanskiBroj" name="postanskiBroj" value="<?=$korisnik->postanskiBroj?>"/>
                        <span class='greska'>Pogrešan format <br/> Format (poštanski broj sme sadržati samo brojeve)</span>
                        </div>

                        <div class="col-11 mt-4">
                        <label for="postanskiBroj">Uloga</label>
                        <select id="uloga">
                            <?php 
                            $uloge=$konekcija->query("SELECT * FROM uloge");
                            foreach($uloge as $u){
                                if(($u->idUloga)==($korisnik->idUloga)){
                                    echo "<option selected='selected' value='$u->idUloga'>$u->ulogaNaziv</option>";
                                }else{
                                echo "<option value='$u->idUloga'>$u->ulogaNaziv</option>";
                                }
                            }
                            ?>
                        </select>
                        <span class='greska'>Pogrešan format <br/> Format (poštanski broj sme sadržati samo brojeve)</span>
                        </div>

                        <div class="col-11">
                            <button id="posalji" name="reg" type="button" value="<?=$korisnik->idKorisnik?>">Izmeni</button>
                        </div>
                    </form>
                    <div id="poruka" class="col-10 mx-auto p-4"></div>
                </div>
            </div>
        </div>              
        <?php endif;?>
 
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
        
</body>
</html>