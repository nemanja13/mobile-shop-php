<?php
    session_start();
    require "fixed/head.php";
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="naslov2">Logovanje</h2>
            </div>
            <div class="col-12">
                <form id="logovanje" action="" method="POST" class="col-lg-4 col-sm-7 col-11">

                    <label for="email">Email</label>
                    <input type="text" id="email" name="email"/>
                    <span class='greska'>Pogrešan format</span>

                    <label for="lozinka">Lozinka</label>
                    <input type="password" id="lozinka" name="lozinka"/>
                    <span class='greska'>Pogrešan format <br/> Format (lozinka mora sadržati 6 karaktera)</span>

                    <button id="posalji" name="log" type="button" value="login">Login</button>
                    <div class="cistac"></div>
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
