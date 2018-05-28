<?php

    //pobiera dane z sekcji (nazwy, order, permissje)
    function getsections(){
        $directory = 'include/section'; // folder sekcji
        $includelist = scandir($directory); // wyszukuje wszystkie sekcje
        global $alink;
        global $permnamelist;
        global $con;

        for($i=2;$i<count($includelist);$i++){ // petla konwertujaca wyniki skanu
            $afile = fopen("include/section/".$includelist[$i],"r"); // otwiera plik
            $atitle = explode("@", fgets($afile)); // pobiera 1 linijke, dzieli ja @ i zapisuje do tablicy

            $apermission = explode("@", fgets($afile)); // pobiera 2 linijke, dzieli ja @ i zapisuje do tablicy
            $asection = explode(".", $includelist[$i]); // oddziela .php od nazwy pliku zapisuajc tylko nazwe

            $acount = count($apermission)-1;
            $z=0;
            $permnamelist[$asection[0]][$z] = "*";
            $z++;
            if($acount==2 && $apermission[1] == "getdb"){
                echo "no i fajnie: ".$asection[0];
                $db_query = mysqli_query($con, "SELECT name FROM $asection[0];");
                $n=1;
                while($db_row = mysqli_fetch_assoc($db_query)){
                    $permnamelist[$asection[0]][$n] = $db_row['name'];
                    $n++;
                }
            }else{
                for($v=1;$v<$acount;$v++){
                    $permnamelist[$asection[0]][$z] = $apermission[$v];
                    $z++;
                }
            }
            $permnamelist["section"][$i-1]=$asection[0];

            fclose($afile); // wyjscie z pliku
            $alink[$i-2] = array($atitle[2], $asection[0], $atitle[1]); // zapisuje przekonwertowane wyniki do tablicy
        }
        sort($alink); // sortowanie tablicy zgodnie z ich numeracja w plikach
    }

    // funkcja sprawdzajaca pozwolenia usera
    $permlist = array();
    function checkpermission($section, $name=0){
        // ex1: settings / author
        // ex2: section  / 0
        global $login;
        global $con;
        global $permlist;

        $type1 = "SELECT permissions.name, permissions.value FROM permissions, groups, users WHERE permissions.group_id=groups.id_rangi AND groups.id_rangi=users.ranga_id AND users.login='$login' AND (permissions.section='page' OR permissions.section='$section') AND permissions.name='*' AND permissions.value=1;";

        $type2 = "SELECT permissions.name, permissions.value FROM permissions, groups, users WHERE permissions.group_id=groups.id_rangi AND groups.id_rangi=users.ranga_id AND users.login='$login' AND permissions.section='$section' AND permissions.value=1;";

        $type3 = "SELECT permissions.name, permissions.value FROM permissions, groups, users WHERE permissions.group_id=groups.id_rangi AND groups.id_rangi=users.ranga_id AND users.login='$login' AND permissions.section='$section' AND permissions.name='$name' AND permissions.value=1;";

        $db_query = mysqli_query($con, $type1);
        $db_row = mysqli_fetch_assoc($db_query);
        $db_query->free();

        if(isset($db_row["name"]) && isset($db_row["value"])){
            return 1; // user ma uprawnienia page * lub section *
        }else{
            if(!$name){
                //$echo = "blad";
                //"zgoda" . count($permlist) . "section: " . $section . " name: " . $name . " blad: " . $echo;
                $db_query = mysqli_query($con, $type2);
                $permlist = array();
                //$i=0;
                //while($db_row = mysqli_fetch_assoc($db_query)){
                //    $permlist[$i] = array($db_row["name"], $db_row["value"]);
                //    $i++;
                //}
                while($db_row = mysqli_fetch_assoc($db_query)){
                    $permlist[$db_row["name"]] = $db_row["value"];
                }
                if(count($permlist)>0){
                    return 1; // jest conajmniej jedno uprawnienie zgodne
                }else{
                    return 0;
                }
            }else{
                $db_query = mysqli_query($con, $type3);
                $db_row = mysqli_fetch_assoc($db_query);
                if(isset($db_row["name"]) && isset($db_row["value"])){
                    return 1; // user ma 1 konkretne uprawnienie
                }else{
                    return 0;
                }
            }
        }
        $db_query->free();
    }

    // TESTER PERMISSJI
    function permissiontester(){
        echo "page: " . checkpermission("page") . "<br>";
        echo "section: " . checkpermission("section") . "<br>";
        echo "array section: " . count($permlist) . "<br>" ;
        echo "nic: " . checkpermission("nic") . "<br>";
        echo "settings: " . checkpermission("settings") . "<br>";
        echo "section adduser: " . checkpermission("section", "adduser") . "<br>";
        echo "section settings: " . checkpermission("section", "settings") . "<br>";
        echo "settings banner: " . checkpermission("settings", "banner") . "<br>";
        echo "settings ifcon: " . checkpermission("settings", "ifon") . "<br>";

        //FIXME: jezeli ktos poda np section a user ma * to do tablicy wejda wszystkie
        //TODO: test uprawnien czy wszystkie sa sprawne
    }

    //wylogowywanie
    function logout(){
        session_unset();
        session_destroy();
        $_SESSION = array();
        header('Location: ./');
    }

    function executepermoptlist(){
        global $alink;
        global $permsectionlist;
        global $permnamelist;

        $permsectionlist = array();
        $nperm=0;
        $permsectionlist[$nperm] = "page";
        $nperm++;
        $permsectionlist[$nperm] = "section";
        $nperm++;
        foreach($alink as $value){
            $permsectionlist[$nperm] = $value[1];
            $nperm++;
        }

        $permnamelist["section"][0]= "*";
        $permnamelist["page"][0]= "*";


    }
    //przygotowywuje i wyswietla option do formularza
    function loadpermoption($type, $default=0, $section=0){
        global $permsectionlist;
        global $permnamelist;
        if($type=="section"){
            foreach($permsectionlist as $value){
                if($default!=$value){
                    echo "<option value='$value'>$value</option>";
                }else{
                    echo "<option value='$value' selected>$value</option>";
                }
            }
        }else if($type=="name"){
            if($section){
                foreach($permnamelist[$section] as $value){
                    if($default!=$value){
                        echo "<option value='$value'>$value</option>";
                    }else{
                        echo "<option value='$value' selected>$value</option>";
                    }
                }
            }

        }else if($type=="value"){
            if($default==1){
                echo "<option value='1' selected>1</option><option value='0'>0</option>";
            }else{
                echo "<option value='1'>1</option><option value='0' selected>0</option>";
            }
        }else{
            echo " nie dziala";
        }
    }

?>
