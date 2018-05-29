let next = document.getElementById('grnext').innerHTML; //pobiera wartosc pola od ktorego ma zaczac numeracje
let zero = next;
let nextinput = 0;
let first=true;
function getnext(kol){
    let x = true;
    document.getElementsByName('grsection'+(kol-1))[0].onfocus = function f(){ // nasluchuje nad ostatnia sekcja

        if(x){
            console.log('grsection'+(kol-1));
            //var moreinput = document.getElementById('moreinput').innerHTML; // div w ktorym beda wszystkie nastepne sekcje
            let zeroinput = document.getElementById('zeroinput').innerHTML; // div z defaultowa sekcja ktora bedzie wklejana
            if(first){
                document.getElementById('allinput').innerHTML = zeroinput+"<div class='allnextinput'></div>"; // wkleja nowego diva
                first=false;
            }else{
                document.getElementsByClassName('allnextinput')[nextinput].innerHTML = zeroinput+"<div class='allnextinput'></div>";
                nextinput++;
            }

            document.getElementsByName('grsection'+(zero-1))[1].name = "grsection"+kol; // pobiera z nowego diva z option parametr name i go podmienia
            document.getElementsByName('grname'+(zero-1))[1].name = "grname"+kol;
            document.getElementsByName('grvalue'+(zero-1))[1].name = "grvalue"+kol;
            kol++; // dodaje jeden by zaczac nasluchiwac drugi
            x = false;
            getnext(kol); // odpala funkcje

        }
    };
}


getnext(next);

