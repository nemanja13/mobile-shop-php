<?php
    session_start();
    require "fixed/head.php";
?>
<body>
    <?php   require "fixed/nav.php"; ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mt-5">
                <img src="images/profile.png" alt="Nemanja Maksimović" id="autor"/>
            </div>
            <div class="col-lg-6 col-md-6  my-auto mt-4 mr-auto p-5 align-text-middle">
                <p>Zovem se Nemanja Maksimović, potičem iz jednog malog sela nadomak Grocke, student sam Visoke ICT škole, završio sam srednju ekonomsku školu u Grockoj, oduvek sam se interesovao za računare i modernu tehnologiju. Na taj način sam zavoleo programiranje i sebe vidim kao budućeg web programera.</p>                   
            </div>
        </div>
    </div>  
    <?php   require "fixed/footer.php"; ?>
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/main.min.js"></script>
</body>
</html>
