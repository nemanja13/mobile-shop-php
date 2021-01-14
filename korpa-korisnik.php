<?php
    session_start();
    if(!isset($_SESSION["korisnik"]) || $_SESSION["korisnik"]->ulogaNaziv!="korisnik"){
        header("Location: errors/403.php");
    }
    require "fixed/head.php";
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div id="proizvodi" class="p-2">
    
    
    </div>
    <div class="red2">
        <a href="#" data-id="<?=$_SESSION['korisnik']->idKorisnik?>" id="nastaviKupovinu">Nastavi kupovinu</a>
    </div>
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
</body>
</html>
