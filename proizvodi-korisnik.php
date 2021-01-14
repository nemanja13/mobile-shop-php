<?php
    session_start();
    require "fixed/head.php";
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid" id="proizvodi">
        <div class="row naslov">
            <div class="col-lg-3 col-12">
                <h1 id="proizvodiTelefoni">Smart telefoni</h1>
            </div>
            <div id="searchSortFilter" class="col-lg-9 col-12 flex mt-4">
                <form id="pretragaProizvoda" action="#" method="POST" class="col-md-4">
                    <input type="text" name="search" placeholder="Unesite pojam za pretragu" id="search">
                    <p id="pretraga"><i id="faSearch" class="fa fa-search"></i></p>
                </form>
                <select id="sortiraj" class="col-md-3 col-9 mx-auto">
                    <option value="0">Sortiraj po</option>
                    <option value="1">Nazivu od A do Z</option>
                    <option value="2">Nazivu od Z do A</option>
                    <option value="3">Ceni rastuće</option>
                    <option value="4">Ceni opadajuće</option>
                </select>
                <div id="filterDd" class="col-md-4 col-9 d-lg-none mx-auto">
                    <h2 id="filteri" class="prikazi flex">Filteri <i class="fas fa-angle-down"></i></h2>
                    <div id="filteriMd" class="nevidljiv col-12">
                         <div class="col-11 blokFilter">
                             <h4 class="flex prikazi">Robna marka <i class="fas fa-angle-down"></i></h4>
                             <div class="divRobneMarke nevidljiv"></div>
                         </div>
                    </div>
                 </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3 d-none d-lg-block filteri">
                <div class="col-11 blokFilter">
                    <h2 class="flex prikazi">Robna marka <i class="fas fa-angle-up"></i></h2>
                    <div class="divRobneMarke"></div>
                </div>
            </div>
            <div class="col-lg-9 col-12 flex" id="sadrzajProizvodi">
                
            </div>
            <div class="col-lg-9 col-12 flex mx-auto" id="paginacija">
                
            </div>
        </div>
    </div>
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script>
        <?php if(isset($_SESSION["korisnik"])):  ?>
            sviProizvodi(
                function(proizvodi){
                    prikaziProizvode(proizvodi, true)
                }
            );
        <?php endif;?>
    </script>
        
</body>
</html>