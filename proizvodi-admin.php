<?php
    session_start();
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="admin"){
        header("Location: errors/403.php");
    }
    require "functions.php";
    require "fixed/head.php";

    $proizvodi=proizvodiKorpa();
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid" id="proizvodi">
        <table id="tabela">
            <tr>
                <th>Slika</th>
                <th>Naziv</th>
                <th>Cena</th>
                <th>Datum postavljanja</th>
                <th>Podešavanja</th>
            </tr>
            <?php foreach($proizvodi as $p):?>
                <tr>
                    <td><img class="thumbnail" src="images/proizvodi/thumbnail/<?=$p->putanja?>" alt="<?=$p->opis?>"/></td>
                    <td><?=$p->nazivMarka?> <?=$p->naziv?></td>
                    <td><?=preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", preg_replace("/\./",",",$p->cena))?> RSD</td>
                    <td><?=$p->datumPost?></td>
                    <td><a href="admin/proizvod-delete.php?id=<?=$p->idProizvod?>">Obriši </a> <a href="proizvod-update-insert.php?uradi=update&id=<?=$p->idProizvod?>"> Izmeni</a></td>
                </tr>
            <?php endforeach;?>
        </table>
        <div class="col-12 flex2" id="sadrzaj"><a href="proizvod-update-insert.php?uradi=insert">Dodaj novi proizvod </a></div>
    </div>
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
        
</body>
</html>