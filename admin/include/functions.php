<?php
    function checkpermission($section, $name=0){
        // ex1: settings / author
        // ex2: section  / 0
        global($login);

        $type1 = "SELECT permissions.name, permissions.value FROM permissions, groups, users WHERE permissions.group_id=groups.id_rangi AND groups.id_rangi=users.ranga_id AND users.login='$login' AND (permissions.section='page' OR permissions.section='$section') AND permissions.name='*' AND permissions.value=1;";

        $type2 = "SELECT permissions.name, permissions.value FROM permissions, groups, users WHERE permissions.group_id=groups.id_rangi AND groups.id_rangi=users.ranga_id AND users.login='$login' AND permissions.section='$section' AND permissions.value=1;"

            $type3 = "SELECT permissions.name, permissions.value FROM permissions, groups, users WHERE permissions.group_id=groups.id_rangi AND groups.id_rangi=users.ranga_id AND users.login='$login' AND permissions.section='$section' AND permissions.name='$name' AND permissions.value=1;";

        $db_query = mysqli_query($con, $type1);
        $db_row = mysqli_fetch_assoc($db_query);
        $db_query->free();

        if(isset($db_row["name"]) && isset($db_row["value"])){
            return 1; // user ma uprawnienia page * lub section *
        }else{
            if($name==0){
                $db_query = mysqli_query($con, $type2);
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


?>
