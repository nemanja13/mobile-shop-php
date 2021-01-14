<?php require_once "functions.php"; ?>
<div class="container-fluid" id="footer">
    <div class="row flex3">
        <div class="col-sm-4 col-md-3 mt-4">
            <h4>Meni</h4>
            <?= prikaziPodMeni();?>
        </div>
        <div class="col-sm-4 col-md-3 mt-4">
            <h4>Informacije</h4>
            <ul>
                <li>Radno vreme:</li>
                <li>Ponedeljak-Petak: 9h-20h</li>
                <li>Subota: 9h-15h</li>
                <li>Nedelja: zatvoreno</li>
            </ul>
        </div>
        <div class="col-sm-4 col-md-3 mt-4">
            <h4>Kontaktirajte nas</h4>
            <ul>
                <li>Telefon: +38111 753 22 55</li>
                <li>Mobilni: +38164 753 22 66</li>
                <li>Pratite nas:</li>
            </ul>
            <div id="socMreze">
                <a href="https://www.facebook.com/"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="https://www.instagram.com" class="ml-4"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="https://twitter.com/" class="ml-4"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="https://youtube.com/" class="ml-4"><i class="fab fa-youtube fa-lg"></i></a>
                <a href="sitemap.xml" class="ml-3"><i class="fas fa-sitemap fa-lg"></i></a>
                <a href="dokumentacija.pdf" class="ml-3"><i class="fas fa-file-pdf fa-lg"></i></a>
            </div>
        </div>
    </div>
</div>