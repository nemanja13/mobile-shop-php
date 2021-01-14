<div id="header" class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-10">
                <a href="index.php" id="logo"><img src="images/logo.png"><h1>Mobile Shop</h1></a>
            </div>
            <div id="meni" class="col-lg-9 my-auto d-none d-lg-flex flex">
                <?php
                    require_once "functions.php";
                    echo prikaziMeni();
                ?>
            </div>
            <div class="col-2 d-lg-none">
                <div id="bars">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="podMeni" class="col-12 d-lg-none nevidljiv">
                <?=prikaziMeni();?>
            </div>
        </div>
</div>