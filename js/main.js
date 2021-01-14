var url=location.href;
window.onload=function(){
    podMeni();
    brojProizvodaUKorpi();
}
if(url.indexOf("index.php")!=-1){
    slajder();
    prikazNajnovijih(false);
    $("#posalji").click(provera);
    prikaziAnketu();
    $("#rezultati").click(prikaziRezAnkete);
    $("#kontakt .disable").click(function(){alert("Morate se registrovati")});
    $("#kontakt .disableGlasao").click(function(){alert("Već ste odgovorili na ovu anketu")});
    $("#glasaj").click(upisOdgovora);
}
if(url.indexOf("registracija.php")!=-1){
    document.getElementById("posalji").addEventListener("click", registracija);
}
if(url.indexOf("login.php")!=-1){
    document.getElementById("posalji").addEventListener("click", logovanje);
}
if(url.indexOf("proizvodi-korisnik.php")!=-1){
    sviProizvodi(
        function(proizvodi){
            prikaziProizvode(proizvodi, false);
            paginacija(proizvodi.brojStranica);
               
        }
    );
    prikazCheckbox();
    $("#sortiraj").change(sortiranje);
    $("#pretraga").click(search);
    
}
if(url.indexOf("korpa-korisnik.php")!=-1){
    korpa();
}
if(url.indexOf("proizvod.php")!=-1){
    $("#proizvod .disable").on("click", function(e){e.preventDefault()
        alert("Morate se registrovati")});
        $(".dodajUKorpu").click(dodajUKorpu);
}
if(url.indexOf("proizvod-update-insert.php?uradi=update")!=-1){
   $("#posalji").click(function(){insertupdateProizvod("update", "izmenili")});
}
if(url.indexOf("proizvod-update-insert.php?uradi=insert")!=-1){
    $("#posalji").click(function(){insertupdateProizvod("insert", "dodali")});
}
if(url.indexOf("korisnici-admin.php")!=-1){
    $("#posalji").click(updateKorisnik);
}
if(url.indexOf("anketa-admin.php")!=-1){
    $("#unesi").click(anketaInsert);
    $("#aktiviraj").click(aktivirajAnketu);
    $("#prikazi").click(prikaziRezAnkete);
}
function slideShow(){
    var aktivan=$("#slajder .vidljiv");
    var sledeci=aktivan.next().length?$(".vidljiv").next():aktivan.parent().children(":first");
    aktivan.removeClass("vidljiv").addClass("nevidljiv");
    sledeci.addClass("vidljiv").removeClass("nevidljiv");
}
function slajder(){
    setInterval(function(){
        slideShow();
}, 10000);
var strelicaDesno=$(".strelicaDesno");
var strelicaLevo=$(".strelicaLevo");
strelicaDesno.click(function(){
    slideShow();
});
strelicaLevo.click(function(){
    var aktivan=$("#slajder .vidljiv");
    var sledeci=aktivan.prev().length?$(".vidljiv").prev():aktivan.parent().children(":last");
    aktivan.removeClass("vidljiv").addClass("nevidljiv");
    sledeci.addClass("vidljiv").removeClass("nevidljiv");
})
}
function podMeni(){
    $("#bars").click(function(){
        $("#podMeni").slideToggle();
        $("#bars").toggleClass("MeniBar")
    });
}
function prikazNajnovijih(korisnik){
    $.ajax({
        url : "proizvodi/najnoviji.php",
        method : "GET",
        dataType : "json",
        success : function(data){
            let ispis="";
            let klasa;
            if(!korisnik) klasa="disable";
            else klasa="dodajUKorpu"
                data.forEach(el => {
                    ispis+=`
                    <div class='col-lg-2 col-sm-5 col-10  flex2 proizvod'>
                        <img src='images/proizvodi/${el.putanja}' alt='${el.opis}' class='slikaProizvod'>
                        <h3>${el.nazivMarka} ${el.naziv}</h3>
                        ${specifikacije(el.specifikacije)}
                        <p class='naStanju'> Na stanju</p>
                        <h4 class='cena'>${cena(el.cena)} RSD</h4>
                        <div class='flex4'>
                            <a href='proizvod.php?id=${el.idProizvod}' class='detaljnije'>Detaljnije</a>
                            <a href='#' class='${klasa}' data-id='${el.idProizvod}'><i class='fas fa-shopping-cart'></i> Kupi</a>
                        </div>
                    </div>`;
                });
                
                document.getElementById("sadrzaj").innerHTML=ispis;
                if(!korisnik)$("#sadrzaj .disable").on("click", function(e){e.preventDefault()
                alert("Morate se registrovati")});
                $(".dodajUKorpu").click(dodajUKorpu);

        },
        error: function(xhr, status, data){
            alert(xhr.status +" "+ status)
        }
        
    })
}
function specifikacije(spec){
    let ispis="<ul>";
    spec.forEach(sp=>{
        ispis+=`<li>${sp.nazivKarakteristika}: ${sp.vrednost}</li>`;
    })
    ispis+="</ul>";
    return ispis;
}
function cena(cena){
    cena=cena.substr(0, cena.length-3).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return cena;
}
function registracija(){
    let dobro=true;
    let btn=$("#posalji").val();
    let ime=$("#ime");
    let prezime=$("#prezime");
    let email=$("#email");
    let emailPotvrda=$("#emailPotvrda");
    let lozinka=$("#lozinka");
    let lozinkaPotvrda=$("#lozinkaPotvrda");
    let adresa=$("#adresa");
    let grad=$("#grad");
    let telefon=$("#telefon");
    let postanskiBroj=$("#postanskiBroj");

    let regExpIme=/^[A-Z][a-z]{2,19}$/;
    let regExpPrezime=/^[A-Z][a-z]{2,19}(\s[A-Z][a-z]{2,19})*$/;
    let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
    let regExpTelefon=/^06\d{7,8}$/;
    let regExpAdresa=/^\w+(\s\w+)*$/;
    let regExpGrad=/^[A-Z][a-z]{1,15}(\s[A-Z][a-z]{1,15})*$/;
    let regPostanskiBroj=/^\d{4,10}$/;

    if(!regExpIme.test(ime.val())){
        ime.next().show();
        dobro=false;
    }
    else{
        ime.next().hide();
    }
    if(!regExpPrezime.test(prezime.val())){
        prezime.next().show();
        dobro=false;
    }
    else{
        prezime.next().hide();
    }
    if(!regExpEmail.test(email.val())){
        email.next().show();
        dobro=false;
    }
    else{
        email.next().hide();
    }
    if(email.val()!=emailPotvrda.val()){
        emailPotvrda.next().show();
        dobro=false;
    }
    else{
        emailPotvrda.next().hide();
    }
    if(!regExpTelefon.test(telefon.val())){
        telefon.next().show();
        dobro=false;
    }
    else{
        telefon.next().hide();
    }
    if(!regExpAdresa.test(adresa.val()) || adresa.val().length>100){
        adresa.next().show();
        dobro=false;
    }
    else{
        adresa.next().hide();
    }
    if(!regExpGrad.test(grad.val())){
        grad.next().show();
        dobro=false;
    }
    else{
        grad.next().hide();
    }
    if(!regPostanskiBroj.test(postanskiBroj.val())){
        postanskiBroj.next().show();
        dobro=false;
    }
    else{
        postanskiBroj.next().hide();
    }
    if(lozinka.val().length<6){
        lozinka.next().show();
        dobro=false;
    }
    else{
        lozinka.next().hide();
    }
    if(lozinka.val() != lozinkaPotvrda.val()){
        lozinkaPotvrda.next().show();
        dobro=false;
    }
    else{
        lozinkaPotvrda.next().hide();
    }

    if(dobro){
        ime=ime.val();
        prezime=prezime.val();
        email=email.val();
        lozinka=lozinka.val();
        adresa=adresa.val();
        grad=grad.val();
        telefon=telefon.val();
        postanskiBroj=postanskiBroj.val();
        $.ajax({
            url : "korisnik/registracija.php",
            method : "post",
            dataType : "json",
            data :{
                btn : btn,
                ime : ime,
                prezime : prezime,
                email : email,
                lozinka : lozinka,
                adresa : adresa,
                grad : grad,
                telefon : telefon,
                postanskiBroj : postanskiBroj
            },
            success : function(data){
                let ispis="";
                data.forEach(e=>{
                    ispis+=`<span>${e}</span><br/>`;
                })
                document.getElementById("poruka").innerHTML=ispis;
            },
            error : function(xhr, status, data){
                let ispis="";
                xhr.responseJSON.forEach(e=>{
                    ispis+=`<span>${e}</span><br/>`;
                })
                document.getElementById("poruka").innerHTML=ispis;
            }
        });
    }
}

function logovanje(){
    let dobro=true;
    let btn=$("#posalji").val();
    let email=$("#email");
    let lozinka=$("#lozinka");

    let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;

    if(!regExpEmail.test(email.val())){
        email.next().show();
        dobro=false;
    }
    else{
        email.next().hide();
    }
    if(lozinka.val().length<6){
        lozinka.next().show();
        dobro=false;
    }
    else{
        lozinka.next().hide();
    }

    if(dobro){
        email=email.val();
        lozinka=lozinka.val();
        $.ajax({
            url : "korisnik/logovanje.php",
            method : "post",
            dataType : "json",
            data :{
                btn : btn,
                email : email,
                lozinka : lozinka
            },
            success : function(data){
                window.location.href="index.php";
            },
            error : function(xhr, status, data){
                let ispis="";
                xhr.responseJSON.forEach(e=>{
                    ispis+=`<span>${e}</span><br/>`;
                })
                document.getElementById("poruka").innerHTML=ispis;
            }
        });
    }
}
function sviProizvodi(callback){
    $.ajax({
        url: "proizvodi/sviProizvodi.php",
        method: "GET",
        success: callback
    });
}
function prikaziProizvode(data, korisnik){
    let ispis="";
    let klasa;
    if(!korisnik) klasa="disable";
    else klasa="dodajUKorpu"
        data.proizvodi.forEach(el => {
            ispis+=`
            <div class='col-lg-3 col-md-5 col-8  flex2 proizvod'>
                <img src='images/proizvodi/${el.putanja}' alt='${el.opis}' class='slikaProizvod'>
                <h3>${el.nazivMarka} ${el.naziv}</h3>
                ${specifikacije(el.specifikacije)}
                <p class='naStanju'> Na stanju</p>
                <h4 class='cena'>${cena(el.cena)} RSD</h4>
                <div class='flex4'>
                    <a href='proizvod.php?id=${el.idProizvod}' class='detaljnije'>Detaljnije</a>
                    <a href='#' class='${klasa}' data-id='${el.idProizvod}'><i class='fas fa-shopping-cart'></i> Kupi</a>
                </div>
            </div>`;
        });
        
        document.getElementById("sadrzajProizvodi").innerHTML=ispis;
        if(!korisnik)$("#sadrzajProizvodi .disable").on("click", function(e){e.preventDefault()
        alert("Morate se registrovati")})
        $(".dodajUKorpu").click(dodajUKorpu);

}
function dodajUKorpu(e){
    e.preventDefault();
    let val=$(this).data("id");
    let proizvodi=[];
    if(localStorage.getItem("kupljenProizvod")){
       proizvodi=JSON.parse(localStorage.getItem("kupljenProizvod"));
       if(proizvodi.filter(p=>p.id==val).length){
            proizvodi.forEach(proizvod=>{
                if(proizvod.id==val){
                    proizvod.kolicina++;
                }
            });
            upisiULocalStorage("kupljenProizvod", proizvodi);
       }else{
            proizvodi.push({
                id:val,
                kolicina:1
            });
            upisiULocalStorage("kupljenProizvod", proizvodi);
       }
    }else{
        proizvodi[0]={
            id:val,
            kolicina:1
        }
        upisiULocalStorage("kupljenProizvod", proizvodi);
    }
    brojProizvodaUKorpi();
}
function upisiULocalStorage(string, vrednost){
    if(localStorage){
        localStorage.setItem(string, JSON.stringify(vrednost));
    }
}
function brojProizvodaUKorpi(){
    if(localStorage.getItem("kupljenProizvod")){
        let proizvodi=JSON.parse(localStorage.getItem("kupljenProizvod"));
        if(proizvodi.length){
            let broj=0;
        proizvodi.forEach(p=>{
            broj+=p.kolicina;
        });
        $("#header .broj").text(broj).css("display", "flex");
        }else{
            $("#header .broj").hide();
        }
    }else{
        $("#header .broj").hide();
    }
}
function korpa(){
    let prazno="<div class='prazno flex'><h1>Vaša korpa je prazna <i class='fas fa-shopping-cart'></i></h1></div>";
    let suma=0;
    if(localStorage.getItem("kupljenProizvod")){
        $.ajax({
            url: "proizvodi/proizvodiKorpa.php",
            method:"get",
            dataType: "json",
            success: function(data){
                let proizvodi= JSON.parse(localStorage.getItem("kupljenProizvod"));
                console.log(data);
                let kupljeniProizvodi=data.filter(p=>{
                    for(let i=0; i<proizvodi.length; i++){
                        if(proizvodi[i].id==p.idProizvod){
                            p.kolicina=proizvodi[i].kolicina;
                            return true;
                        }
                    }
                });
                console.log(kupljeniProizvodi)
                if(kupljeniProizvodi.length){
                let ispis=`
                <table id="tabela">
                    <tr>
                        <th>Slika</th>
                        <th>Naziv</th>
                        <th>Cena</th>
                        <th>Količina</th>
                        <th>Ukupna cena</th>
                        <th>Ukloni</th>
                    </tr>`;
                kupljeniProizvodi.forEach(p=>{
                    ispis+=`<tr>
                        <td><img class="thumbnail" src="images/proizvodi/thumbnail/${p.putanja}" alt="${p.opis}"/></td>
                        <td>${p.nazivMarka} ${p.naziv}</td>
                        <td>${cena(p.cena)} RSD</td>
                        <td>${p.kolicina}</td>
                        <td>${iznos(cena(p.cena),p.kolicina)} RSD</td>
                        <td><a href="#" data-id="${p.idProizvod}">Ukloni</a></td>
                    </tr>`;
                    suma+=parseInt(zbir(iznos(cena(p.cena),p.kolicina)));
                });
                ispis+=`</table>
                <div class="red2"><h4>Ukupna cena: </h4> <p id="ukupnaCena"> ${suma.toString().replace(/\./g, ",").replace(/\B(?=(\d{3})+(?!\d))/g, ".")} RSD</p></div>
                `;
                document.getElementById("proizvodi").innerHTML=ispis;
                $("#tabela a").click(function(e){
                    let val=$(this).data("id");
                    e.preventDefault();
                    let proizvodi=JSON.parse(localStorage.getItem("kupljenProizvod"));
                    let filtriraniProizvodi=proizvodi.filter(p=>p.id!=val);
                    upisiULocalStorage("kupljenProizvod", filtriraniProizvodi);
                    korpa();
                    brojProizvodaUKorpi();
                })
               
            }else{
                document.getElementById("proizvodi").innerHTML=prazno;
                $("#nastaviKupovinu").hide();
            }
            },
            error: function(err){
                console.error(err);
            }
        })
        
    }
    else{
        document.getElementById("proizvodi").innerHTML=prazno;
    }
}
$("#nastaviKupovinu").show().click(function(e){
    e.preventDefault();
    let id=$(this).data("id");
    porudzbina(id);
});
function porudzbina(idKorisnik){
    let kp=JSON.parse(localStorage.getItem("kupljenProizvod"));
    $.ajax({
        url : "korisnik/porudzbina.php",
        method : "post",
        dataType : "json",
        data : {
            proizvodi : kp,
            id : idKorisnik
        },
        success : function(data){
            console.log(data)
            document.getElementById("proizvodi").innerHTML=`<div class='prazno flex'><h1>${data}<i class='fas fa-truck'></i></h1></div>`;
            $("#nastaviKupovinu").hide();
            localStorage.removeItem("kupljenProizvod");
            brojProizvodaUKorpi();
        },
        error : function(xhr, status, data){
            console.log(xhr)
            document.getElementById("proizvodi").innerHTML=`<div class='prazno flex'><h1>${xhr.responseJSON}<i class='fas fa-truck'></i></h1></div>`;
            $("#nastaviKupovinu").hide();
            localStorage.removeItem("kupljenProizvod");
            brojProizvodaUKorpi();
        }
    });
}
function iznos(c, k){
    let cena=c.replace(/(\.)/g, "");
    let kolicina=k;
    let iznos=cena*kolicina;
    iznos=iznos.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return iznos;
}
function zbir(c){
    let cena=c.replace(/(\.)/g, "");
    return cena;
}
function prikaziFilter(){
    $(".prikazi").click(function(){
        $(this).find("i").toggleClass("fas fa-angle-down");
        $(this).find("i").toggleClass("fas fa-angle-up");
        $(this).next().stop(true, true).slideToggle("slow");
    })
}
function prikazCheckbox(){
    $.ajax({
        url : "proizvodi/marke.php",
        method : "post",
        dataType : "json",
        success : function(data){
            let ispis="";
            data.forEach(e=>{
                ispis+=`<span><input type="checkbox" name="filtriranjePoMarci" value="${e.idMarka}">
                <label>${e.nazivMarka}</label></span>`
            });
            $(".divRobneMarke").html(ispis);
            prikaziFilter();
            $("input[name=filtriranjePoMarci]").change(filtriranjePoMarci);
        },
        error : function(xhr, status, data){
            console.log(xhr)
        }
    });
    
}
var nizMarki=[];
function filtriranjePoMarci(){
    document.getElementById("search").value="";
    let val=this.value;
    if(!nizMarki.includes(val)){
        nizMarki.push(val);
    }else{
        nizMarki=nizMarki.filter(p=>p!=val);
    }
    $.ajax({
        url : "proizvodi/sviProizvodi.php",
        method : "post",
        dataType : "json",
        data : {
            page : 1,
            katNiz : nizMarki,
            sort : $("#sortiraj").val(),
            search : $("#search").val()
        },
        success : function(data){
            let korisnik;
            if($("#sadrzajProizvodi .dodajUKorpu").length!=0)
                korisnik=true;
                else korisnik=false
            prikaziProizvode(data, korisnik);
            paginacija(data.brojStranica);
        }
    });
}
function sortiranje(){
    document.getElementById("search").value="";
    let val=this.value;
    console.log(val);
    $.ajax({
        url : "proizvodi/sviProizvodi.php",
        method : "post",
        dataType : "json",
        data : {
            page : 1,
            katNiz : nizMarki,
            sort : val,
            search : $("#search").val()
        },
        success : function(data){
            let korisnik;
            if($("#sadrzajProizvodi .dodajUKorpu").length!=0)
                korisnik=true;
                else korisnik=false
            prikaziProizvode(data, korisnik);
            paginacija(data.brojStranica);
        }
    });
}
function search(){
    let val=$("#search").val();
    console.log(val);
    $.ajax({
        url : "proizvodi/sviProizvodi.php",
        method : "post",
        dataType : "json",
        data : {
            page : 1,
            katNiz : nizMarki,
            sort : $("#sortiraj").val(),
            search : val
        },
        success : function(data){
            let korisnik;
            if($("#sadrzajProizvodi .dodajUKorpu").length!=0)
                korisnik=true;
                else korisnik=false
            prikaziProizvode(data, korisnik);
            paginacija(data.brojStranica);
            if(data.proizvodi.length==0){
                ispis="<div class='prazno flex'> <h1>Vaša pretraga nije dala rezultate. <i class='fas fa-search'></i></h1> </div>";
                document.getElementById("sadrzajProizvodi").innerHTML=ispis;
            }
        },
        error : function(xhr, status, data){
            ispis="<div class='prazno flex'> <h1>Vaša pretraga nije dala rezultate. <i class='fas fa-search'></i></h1> </div>";
            document.getElementById("sadrzajProizvodi").innerHTML=ispis;
        }
    });
}
function paginacija(str){
    let ispis="<ul>";
        for(let i=1; i<=str; i++){
            if(i==1) ispis+=`<li><a href="#" class="flex3 page aktivan" data-page="${i}">${i}</a></li>`;
            else ispis+=`<li><a href="#" class="flex3 page" data-page="${i}">${i}</a></li>`;
        }
        ispis+="</ul>"
        document.getElementById("paginacija").innerHTML=ispis;
        $("#paginacija .page").click(function(){
            let page=$(this).data("page");
            $("#paginacija .aktivan").removeClass("aktivan");
            $(this).addClass("aktivan");
            $.ajax({
                url : "proizvodi/sviProizvodi.php",
                method : "post",
                dataType : "json",
                data : {
                    page : page,
                    katNiz : nizMarki,
                    sort : $("#sortiraj").val(),
                    search : $("#search").val()
                },
                success : function(data){
                    let korisnik;
                    if($("#sadrzajProizvodi .dodajUKorpu").length!=0)
                        korisnik=true;
                        else korisnik=false
                    prikaziProizvode(data, korisnik);
                }
            });
        })
}
function insertupdateProizvod(putanja, poruka){
    let btn=$("#posalji").val();
    let naziv=$("#naziv").val();
    let datumPost=$("#datumPost").val();
    let cena=$("#cena").val();
    let marka=$("#marka").val();
    let spec0=$("#spec0").val();
    let spec1=$("#spec1").val();
    let spec2=$("#spec2").val();
    let spec3=$("#spec3").val();
    let spec4=$("#spec4").val();
    let spec5=$("#spec5").val();
    let spec6=$("#spec6").val();
    let spec7=$("#spec7").val();
    let spec8=$("#spec8").val();
    let spec9=$("#spec9").val();
    let spec10=$("#spec10").val();

    let dobro=true;
    let regExpNaziv=/[\w\d\s]+/;
    let regExpSpec0=/^\d([\.,]\d)?\".*$/;
    let regExpSpec1=/^\d{1,2}\s?GB$/;
    let regExpSpec2=/^\d{2,3}\s?GB$/;
    let regExpSpec3=/^\d{1,3}\s?MP(\s?[\-\+]\s?\d{1,3}\s?MP)*\/\d{1,3}\s?MP(\s?[\-\+]\s?\d{1,3}\s?MP)*$/;
    let regExpSpec4=/^.*(Dual|Quad|Octa|Hexa)(\sCore|-core).*$/;
    let regExpSpec5=/^(Android|iOS|EMUI)(\s?\d{1,2}([\.,]\d{1,2})?(\s?\(\w{3,12}\))?)?$/;
    let regExpSpec6=/^\d{4}\s?mAh$/;
    let regExpSpec7=/^\d{1,3}([\.,]\d{1,2})?\s?mm(\s?[xX]\s?\d{1,3}([\.,]\d{1,2})?\s?mm){2}$/;
    let regExpSpec8=/^\d{1,4}\s?g$/;
    let regExpSpec9=/^\d{3,4}\s?[xX]\s?\d{3,4}\s?(px|Px|PX).*$/;
    let regExpSpec10=/^\w{2,14}(\s\w{2,14})*$/;

    if(!regExpNaziv.test(naziv)){
        $("#naziv").next().show();
        dobro=false;
    }
    else{
        $("#naziv").next().hide();
    }
    if(cena<=0){
        $("#cena").next().show();
        dobro=false;
    }
    else{
        $("#cena").next().hide();
    }
    if(!regExpSpec0.test(spec0)){
        $("#spec0").next().html("Pogrešan format ( <i>Morate uneti veličinu ekrana, npr. 6,4\"</i> )").show();
        dobro=false;
    }else{
        $("#spec0").next().hide();
    }
    if(!regExpSpec1.test(spec1)){
        $("#spec1").next().html("Pogrešan format ( <i>Morate uneti količinu RAM memorije, npr. 6 GB</i> )").show();
        dobro=false;
    }else{
        $("#spec1").next().hide();
    }
    if(!regExpSpec2.test(spec2)){
        $("#spec2").next().html("Pogrešan format ( <i>Morate uneti količinu Interne memorije, npr. 32 GB</i> )").show();
        dobro=false;
    }else{
        $("#spec2").next().hide();
    }
    if(!regExpSpec3.test(spec3)){
        $("#spec3").next().html("Pogrešan format ( <i>Morate uneti kvalitet kamera, npr. 12 MP/8 MP</i> )").show();
        dobro=false;
    }else{
        $("#spec3").next().hide();
    }
    if(!regExpSpec4.test(spec4)){
        $("#spec4").next().html("Pogrešan format ( <i>Morate uneti procesor telefona, npr. Octa-core</i> )").show();
        dobro=false;
    }else{
        $("#spec4").next().hide();
    }
    if(!regExpSpec5.test(spec5)){
        $("#spec5").next().html("Pogrešan format ( <i>Morate uneti operativni sistem telefona, npr. Android</i> )").show();
        dobro=false;
    }else{
        $("#spec5").next().hide();
    }
    if(!regExpSpec6.test(spec6)){
        $("#spec6").next().html("Pogrešan format ( <i>Morate uneti kapacitet baterije telefona, npr. 4000 mAh</i> )").show();
        dobro=false;
    }else{
        $("#spec6").next().hide();
    }
    if(!regExpSpec7.test(spec7)){
        $("#spec7").next().html("Pogrešan format ( <i>Morate uneti dimenzije telefona, npr. 158.5 mm x 74.7 mm x 7.7 mm</i> )").show();
        dobro=false;
    }else{
        $("#spec7").next().hide();
    }
    if(!regExpSpec8.test(spec8)){
        $("#spec8").next().html("Pogrešan format ( <i>Morate uneti težinu telefona u gramima, npr. 158 g</i> )").show();
        dobro=false;
    }else{
        $("#spec8").next().hide();
    }
    if(!regExpSpec9.test(spec9)){
        $("#spec9").next().html("Pogrešan format ( <i>Morate uneti rezoluciju telefona, npr. 1080 x 2340 px</i> )").show();
        dobro=false;
    }else{
        $("#spec9").next().hide();
    }
    if(!regExpSpec10.test(spec10)){
        $("#spec10").next().html("Pogrešan format ( <i>Morate uneti boju telefona, npr. Crna )").show();
        dobro=false;
    }else{
        $("#spec10").next().hide();
    }

    if(dobro){
        var slika = $("#slika").prop("files")[0];
        var thumbnail = $("#thumbnail").prop("files")[0];
        var spec=JSON.stringify({ 1 : spec0, 2 : spec1, 3 : spec2, 4 : spec3, 5 : spec4, 6 : spec5, 7 : spec6, 8 : spec7, 9 : spec8, 10 : spec9, 11 : spec10})
        var form_data = new FormData();                  
        form_data.append("slika", slika) 
        if(putanja=="update"){
            form_data.append("id", btn) 
        }
        if(putanja=="insert"){
            form_data.append("insert", true) 
        }
        form_data.append("naziv", naziv) 

        form_data.append("datumPost", datumPost)
        form_data.append("cena", cena) 
        form_data.append("marka", marka) 
        form_data.append("spec", spec) 
        form_data.append("thumbnail", thumbnail)
        $.ajax({
            url: "admin/proizvod-"+putanja+".php",
            dataType: 'script',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success : function(data){
                alert("Uspešno ste "+poruka+" proizvod");
                $("#poruka").html("").show();
            },
            error : function(xhr,status,data){
                ispis="<ol>";
                JSON.parse(xhr.responseText).forEach(x=>{
                    ispis+=`<li>${x}</li>`;
                });
                ispis+="</ol>";
                $("#poruka").html(ispis).show();

            }
        })
    }
}
function updateKorisnik(){
    let dobro=true;
    let btn=$("#posalji").val();
    let ime=$("#ime");
    let prezime=$("#prezime");
    let email=$("#email");
    let lozinka=$("#lozinka");
    let adresa=$("#adresa");
    let uloga=$("#uloga").val();
    let grad=$("#grad");
    let telefon=$("#telefon");
    let postanskiBroj=$("#postanskiBroj");

    let regExpIme=/^[A-Z][a-z]{2,19}$/;
    let regExpPrezime=/^[A-Z][a-z]{2,19}(\s[A-Z][a-z]{2,19})*$/;
    let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
    let regExpTelefon=/^06\d{7,8}$/;
    let regExpAdresa=/^\w+(\s\w+)*$/;
    let regExpGrad=/^[A-Z][a-z]{1,15}(\s[A-Z][a-z]{1,15})*$/;
    let regPostanskiBroj=/^\d{4,10}$/;

    if(!regExpIme.test(ime.val())){
        ime.next().show();
        dobro=false;
    }
    else{
        ime.next().hide();
    }
    if(!regExpPrezime.test(prezime.val())){
        prezime.next().show();
        dobro=false;
    }
    else{
        prezime.next().hide();
    }
    if(!regExpEmail.test(email.val())){
        email.next().show();
        dobro=false;
    }
    else{
        email.next().hide();
    }
    if(!regExpTelefon.test(telefon.val())){
        telefon.next().show();
        dobro=false;
    }
    else{
        telefon.next().hide();
    }
    if(!regExpAdresa.test(adresa.val()) || adresa.val().length>100){
        adresa.next().show();
        dobro=false;
    }
    else{
        adresa.next().hide();
    }
    if(!regExpGrad.test(grad.val())){
        grad.next().show();
        dobro=false;
    }
    else{
        grad.next().hide();
    }
    if(!regPostanskiBroj.test(postanskiBroj.val())){
        postanskiBroj.next().show();
        dobro=false;
    }
    else{
        postanskiBroj.next().hide();
    }
    if(lozinka.val().length<6){
        lozinka.next().show();
        dobro=false;
    }
    else{
        lozinka.next().hide();
    }

    if(dobro){
        ime=ime.val();
        prezime=prezime.val();
        email=email.val();
        lozinka=lozinka.val();
        adresa=adresa.val();
        grad=grad.val();
        telefon=telefon.val();
        postanskiBroj=postanskiBroj.val();
        $.ajax({
            url : "admin/korisnik-update.php",
            method : "post",
            dataType : "json",
            data :{
                btn : btn,
                ime : ime,
                prezime : prezime,
                email : email,
                lozinka : lozinka,
                uloga : uloga,
                adresa : adresa,
                grad : grad,
                telefon : telefon,
                postanskiBroj : postanskiBroj
            },
            success : function(data){
                let ispis="";
                data.forEach(e=>{
                    ispis+=`<span>${e}</span><br/>`;
                })
                document.getElementById("poruka").innerHTML=ispis;
            },
            error : function(xhr, status, data){
                let ispis="";
                xhr.responseJSON.forEach(e=>{
                    ispis+=`<span>${e}</span><br/>`;
                })
                document.getElementById("poruka").innerHTML=ispis;
            }
        });
    }
}
function provera(){
    let btn=document.getElementById("posalji").value;
    let ime= document.getElementById("ime").value;
    let prezime= document.getElementById("prezime").value;
    let email= document.getElementById("email").value;
    let telefon= document.getElementById("telefon").value;
    let poruka= document.getElementById("porukaForma").value;
    let razlogKontakta= document.getElementById("razlogKontakta").value;

    let regExpPrezime=/^[A-Z][a-z]{2,19}(\s[A-Z][a-z]{2,19})*$/;
    let regExpIme=/^[A-Z][a-z]{2,19}$/;
    let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
    let regExpTelefon=/^06\d{7,8}$/;
    let regExpPoruka=/^[^@]+(\s[^@]+)*$/;
    let dobro=true;
    if(!regExpIme.test(ime)){
        $("#greskaIme").show();
        dobro=false;
    }else{
        $("#greskaIme").hide();
    }
    if(!regExpPrezime.test(prezime)){
        $("#greskaPrezime").show();
        dobro=false;
    }else{
        $("#greskaPrezime").hide();
    }
    if(!regExpEmail.test(email)){
        $("#greskaEmail").show();
        dobro=false;
    }else{
        $("#greskaEmail").hide();
    }
    if(!regExpTelefon.test(telefon)){
        $("#greskaTelefon").show();
        dobro=false;
    }else{
        $("#greskaTelefon").hide();
    }
    if(!regExpPoruka.test(poruka)){
        $("#greskaPoruka").show();
        dobro=false;
    }
    else{
        $("#greskaPoruka").hide();
    }
    if(razlogKontakta==0){
        $("#greskaRazlogKontakta").show();
        dobro=false;
    }
    else{
        $("#greskaRazlogKontakta").hide();
    }
    if(dobro){
        let nizRazlogKontakta=["Prodaja", "Isporuka robe", "Reklamacije", "Vaše primedbe i sugestije", "Ostala pitanja"];
        razlogKontakta=nizRazlogKontakta[razlogKontakta-1];

        $.ajax({
            url : "admin/mejl-insert.php",
            method : "post",
            dataType : "json",
            data :{
                btn : btn,
                ime : ime,
                prezime : prezime,
                email : email,
                telefon : telefon,
                razlogKontakta : razlogKontakta,
                poruka : poruka
            },
            success : function(data){
                let ispis="";
                data.forEach(e=>{
                    ispis+=`<span>${e}</span><br/>`;
                })
                document.getElementById("poruka").innerHTML=ispis;
            },
            error : function(xhr, status, data){
                let ispis="<ol>";
                xhr.responseJSON.forEach(e=>{
                    ispis+=`<li class='crveno'>${e}</li>`;
                })
                ispis+="</ol>"
                document.getElementById("poruka").innerHTML=ispis;
            }
        });
    }
}
function prikaziAnketu(){
    $.ajax({
        url : "admin/anketa-select.php",
        method : "post",
        dataType : "json",
        success : function(data){
            ispis="<form>";
            ispis+=`<h3>${data.anketa.pitanje}</h3> <input type="hidden" value="${data.anketa.idAnketa}" id="anketeRez"/>`;
            data.odgovori.forEach(e=>{
                ispis+=`<span><input type="radio" name="odgovori" value="${e.idOdgovor}">
                <label>${e.odgovor}</label></span>`;
            });
            ispis+="<span id='greskaAnketa' class='greska'>Morate čekirati odgovor</span></form>";
            document.getElementById("rezultatiAnkete").innerHTML=ispis;
        },
        error : function(xhr, status, data){
            console.log(xhr)
            alert(xhr.responseJSON);
        }
    })
}
function anketaInsert(){
    let btn=$(this).val();
    let pitanje=$("#pitanje");
    let odgovori=$("#odgovori");
    let dobro=true;

    let regExp=/^[^@]+$/;

    if(!regExp.test(pitanje.val())){
        pitanje.next().show();
        dobro=false;
    }
    else{
        pitanje.next().hide();
    }
    if(!regExp.test(odgovori.val())){
        odgovori.next().show();
        dobro=false;
    }
    else{
        odgovori.next().hide();
    }

    if(dobro){
        $.ajax({
            url : "admin/anketa.php",
            method : "post",
            dataType : "json",
            data : {
                btn:btn,
                pitanje:pitanje.val(),
                odgovori:odgovori.val()
            },
            success : function(data){
                alert(data.poruka);
                location.reload();
            },
            error : function(xhr, status, data){
                let ispis="<ol>";
                xhr.responseJSON.forEach(e=>{
                    ispis+=`<li class='crveno'>${e}</li>`;
                })
                ispis+="</ol>"
                document.getElementById("poruka").innerHTML=ispis;
            }
        });
    }
}
function aktivirajAnketu(){
    let btn=$(this).val();
    let anketa=$("#anketeAktivacija")
    let dobro=true;

    if(anketa.val()==0){
        anketa.next().show();
        dobro=false;
    }
    else{
        anketa.next().hide();
    }

    if(dobro){
        $.ajax({
            url : "admin/anketa.php",
            method : "post",
            dataType : "json",
            data : {
                btn:btn,
                idAnketa:anketa.val()
            },
            success : function(data){
                alert(data.poruka);
                location.reload();
            },
            error : function(xhr, status, data){ 
                alert(xhr.responseJSON);
            }
        })
    }
}
function prikaziRezAnkete(){
    let btn=$(this).val();
    let anketa=$("#anketeRez")
    let dobro=true;

    if(anketa.val()==0){
        anketa.next().show();
        dobro=false;
    }
    else{
        anketa.next().hide();
    }

    if(dobro){
        $.ajax({
            url : "admin/anketa.php",
            method : "post",
            dataType : "json",
            data : {
                btn:btn,
                idAnketa:anketa.val()
            },
            success : function(data){
                let ispis="";
                console.log(data)
                data.rezultati.forEach(e=>{
                    if(Number(e.broj)){ 
                        ispis+=`<div><div class="col-8 mx-auto"><span>${e.odgovor}</span></div><div class="col-8 mx-auto progresDiv"><div class="progres flex" style="width:${(parseInt(e.broj)/data.ukupno[0].ukupno)*100}%;">${Math.round((parseInt(e.broj)/data.ukupno[0].ukupno)*100,0)}%</div></div></div>`;
                    }else{
                        ispis+=`<div><div class="col-8 mx-auto"><span>${e.odgovor}</span></div><div class="col-8 mx-auto progresDiv"><div class="progres flex" style="width:0%;">0%</div></div></div>`;
                    }
                });
                document.getElementById("rezultatiAnkete").innerHTML=ispis;
            },
            error : function(xhr, status, data){ 
                alert(xhr.responseJSON);
            }
        })
    }
}
function upisOdgovora(){
    let idKorisnik=$(this).val();
    let odgovori=$("input[type=radio][name=odgovori]:checked");
    let dobro=true;

    if(odgovori.length==0){
        $("#greskaAnketa").show();
        dobro=false;
    }else{
        $("#greskaAnketa").hide();
        idOdgovor=odgovori.val();
    }

    if(dobro){
        $.ajax({
            url : "admin/odgovor-insert.php",
            method : "post",
            dataType : "json",
            data :{
                idKorisnik:idKorisnik,
                idOdgovor:idOdgovor
            },
            success : function(data){
                alert(data);
                location.reload();
            },
            error : function(xhr, status, data){
                console.log(xhr.responseJSON)
                alert(xhr.responseJSON);
            }
        });
    }
}

