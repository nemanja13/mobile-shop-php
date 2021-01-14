<?php
    session_start();
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: errors/403.php");
    }
    require "functions.php";
    require "fixed/head.php";
    require "config/konekcija.php";

    $mejlovi=mejlovi();
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid" id="proizvodi">
        <table id="tabela">
            <tr>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Email</th>
                <th>Podešavanja</th>
            </tr>
            <?php foreach($mejlovi as $m):?>
                <tr>
                    <td><?=$m->ime?></td>
                    <td><?=$m->prezime?></td>
                    <td><?=$m->email?></td>
                    <td><a href="<?=$_SERVER["PHP_SELF"]."?id=".$m->idMejl?>"> Detaljnije</a> <a href="admin/mejl-delete.php?id=<?=$m->idMejl?>"> Obriši</a></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    <?php
        if(isset($_GET["id"])):
            $id=$_GET["id"];
            $upit="SELECT * FROM mejl WHERE idMejl=:id";
            $priprema=$konekcija->prepare($upit);
            $priprema->bindParam(":id", $id);
            $priprema->execute();

            $posiljalac=$priprema->fetch();
        ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12" id="sadrzaj">
                        <form id="registracija" class="col-lg-10 col-11 flex3">
                            <div class="col-12 m-3">
                                <h4>Pošiljalac</h4>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Ime:</label>
                            <input type="text" readonly="readonly" value="<?=$posiljalac->ime?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Prezime:</label>
                            <input type="text" readonly="readonly" value="<?=$posiljalac->prezime?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Email:</label>
                            <input type="text" readonly="readonly" value="<?=$posiljalac->email?>"/>
                            </div>
                            <div class="col-10 col-sm-5">
                            <label>Telefon:</label>
                            <input type="text" readonly="readonly" value="<?=$posiljalac->telefon?>"/>
                            </div>
                        </form>
                        <table id="tabela" class="col-10 tabelaMejl">
                            <tr>
                                <th><?=$posiljalac->razlogKontakta?></th>
                            </tr>
                            <tr>
                                <td><?=$posiljalac->poruka?></td>
                            </tr>
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