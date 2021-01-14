<?php
    session_start();
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: errors/403.php");
    }
    require "fixed/head.php";
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid" id="ankete">
        <div class="row">
            <div class="col-12">
                <h2 class="naslov2">Unos ankete</h2>
            </div>
            <div class="col-12">
                <form class="col-lg-10 col-11 flex2 mx-auto p-4">

                    <div class="col-10">
                    <label for="pitanje">Pitanje</label>
                    <input type="text" id="pitanje" name="pitanje"/>
                    <span class='greska'>Pogrešan format</span>
                    </div>

                    <div class="col-10">
                    <label for="odgovori">Odgovori</label>
                    <textarea id="odgovori" placeholder="Odgovore razdvojiti ;"></textarea>
                    <span class='greska'>Pogrešan format</span>
                    </div>

                    <div class="col-10">
                        <button id="unesi" name="reg" type="button" value="unosAnketa">Unesi</button>
                    </div>
                </form>
                <div id="poruka" class="col-10 mx-auto p-4"></div>
            </div>
        </div>
        <div class="row">
                <div class="col-12">
                    <h2 class="naslov2">Aktivacija ankete</h2>
                </div>
                <form class="col-lg-10 col-11 flex2 p-4 mx-auto">

                    <div class="col-10">
                    <label for="anketeAktivacija">Ankete</label>
                    <select id="anketeAktivacija" class="col-12 col-lg-7">
                        <option value="0">Izaberite</option>
                        <?php
                            $upit="SELECT * FROM anketa";
                            $rezultat = $konekcija->query($upit);
                            foreach($rezultat as $r):
                            echo "<option value='$r->idAnketa'>$r->pitanje</option>";
                            endforeach;
                        ?>
                    </select>
                    <span class='greska'>Morate izabarti anketu</span>
                    </div>

                    <div class="col-10">
                        <button id="aktiviraj" name="reg" type="button" value="aktiviraj">Aktiviraj</button>
                    </div>
                </form>
                <div class="col-12">
                    <h2 class="naslov2">Rezultati ankete</h2>
                </div>
                <form class="col-lg-10 col-11 flex2 p-4 mx-auto">

                    <div class="col-10">
                    <label for="anketeRez">Ankete</label>
                    <select id="anketeRez" class="col-12 col-lg-7">
                        <option value="0">Izaberite</option>
                        <?php
                        $upit="SELECT * FROM anketa";
                        $rezultat = $konekcija->query($upit);
                        foreach($rezultat as $r):
                        echo "<option value='$r->idAnketa'>$r->pitanje</option>";
                        endforeach;
                        ?>
                    </select>
                    <span class='greska'>Morate izabarti anketu</span>
                    </div>

                    <div class="col-10">
                        <button id="prikazi" name="reg" type="button" value="prikazi">Prikaži</button>
                    </div>
                </form>
                <div id="rezultatiAnkete" class="col-11 mx-auto p-4"></div>
        </div>
    </div>
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
</body>
</html>