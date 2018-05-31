let next = document.getElementById('grnext').innerHTML; //pobiera wartosc pola od ktorego ma zaczac numeracje
let zero = next;
let nextinput = 0;
let first=true;
function getnext(kol){
    let x = true;
    document.getElementsByName('grsection'+(kol-1))[0].onfocus = function f(){ // nasluchuje nad ostatnia sekcja

        if(x){
            //console.log('grsection'+(kol-1));
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
            document.getElementsByName('grsectiono'+(zero-1))[1].name = "grsectiono"+kol;
            createfocus(kol);
            kol++; // dodaje jeden by zaczac nasluchiwac drugi
            x = false;
            getnext(kol); // odpala funkcje

        }
    };
}

function createfocus(who){
    document.getElementsByName('grsection'+who)[0].onchange = function cf(){
        let avalue = document.getElementsByName('grsection'+who)[0].value
        //console.log(avalue);
        var printer ="";
        for(var i = 0;i<permnamelist[avalue].length;i++){
            printer+="<option value='"+permnamelist[avalue][i]+"'>"+permnamelist[avalue][i]+"</option>";
            //console.log(printer);
        }
        document.getElementsByName('grname'+who)[0].innerHTML = printer;
        //<option value='$value'>$value</option>
    }

}

function starterpack(){
    let ilosc = document.getElementsByClassName('selectormanager').length
    for(var z=0;z<ilosc;z++){
        createfocus(z);
    }

}

getnext(next);

starterpack();
//createfocus(0);
