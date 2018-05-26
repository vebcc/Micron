<?php

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

?>
