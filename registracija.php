<?php
    session_start();
    require "fixed/head.php";
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="naslov2">Formular za registraciju</h2>
            </div>
            <div class="col-12">
                <form id="registracija" action="" method="POST" class="col-lg-10 col-11 flex3">

                    <div class="col-10 col-sm-5">
                    <label for="ime">Ime</label>
                    <input type="text" id="ime" name="ime"/>
                    <span class='greska'>Pogrešan format <br/> Format (prvo veliko slovo, ime ne sme biti duže od 20 karaktera)</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="prezime">Prezime</label>
                    <input type="text" id="prezime" name="prezime"/>
                    <span class='greska'>Pogrešan format <br/> Format (prvo veliko slovo, prezime ne sme biti duže od 20 karaktera, osoba može imati više prezimena)</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"/>
                    <span class='greska'>Pogrešan format</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="emailPotvrda">Potvrda email-a</label>
                    <input type="text" id="emailPotvrda" name="emailPotvrda"/>
                    <span class='greska'>Email adrese nisu iste</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="lozinka">Lozinka</label>
                    <input type="password" id="lozinka" name="lozinka"/>
                    <span class='greska'>Pogrešan format <br/> Format (lozinka mora sadržati 6 karaktera)</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="lozinkaPotvrda">Potvrda lozinke</label>
                    <input type="password" id="lozinkaPotvrda" name="lozinkaPotvrda"/>
                    <span class='greska'>Lozinke nisu iste</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="telefon">Broj telefona</label>
                    <input type="text" id="telefon" name="telefon"/>
                    <span class='greska'>Pogrešan format <br/> Format (06********, ukupno cifara 9/10)</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="adresa">Adresa</label>
                    <input type="text" id="adresa" name="adresa"/>
                    <span class='greska'>Pogrešan format</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="grad">Grad/Mesto</label>
                    <input type="text" id="grad" name="grad"/>
                    <span class='greska'>Pogrešan format <br/> Format (prvo veliko slovo, naziv ne sme biti duži od 16 karaktera)</span>
                    </div>

                    <div class="col-10 col-sm-5">
                    <label for="postanskiBroj">Poštanski broj</label>
                    <input type="text" id="postanskiBroj" name="postanskiBroj"/>
                    <span class='greska'>Pogrešan format <br/> Format (poštanski broj sme sadržati samo brojeve)</span>
                    </div>

                    <div class="col-11">
                        <button id="posalji" name="reg" type="button" value="Registracija">Registracija</button>
                    </div>
                </form>
                <div id="poruka" class="col-10 mx-auto p-4"></div>
            </div>
        </div>
    </div>
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
</body>
</html>