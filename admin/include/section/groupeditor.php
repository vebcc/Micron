<!--@Edytor grup@8@-->
<!--@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div id="groupeditor">
  <?php
        if(isset($_GET["groupeditor"]) && !empty($_GET["groupeditor"])){
            $gred=1;
            echo "<h1>Edytuj grupę</h1>";
            $edgroup = htmlspecialchars(stripslashes(strip_tags(trim($_GET["groupeditor"]))));
        }else{
            $gred=0;
            echo "<h1>Dodaj grupę</h1>";
        }
  ?>

    <div class="centercv">
    <?php
        if(checkpermission("section", "groupeditor")){
            if(checkpermission("groupeditor")){
                $success="";
                $error="";

                //TODO: error logi z wykonywania i success logi

                if(isset($_POST["editgroup"]) && !empty($_POST["editgroup"]) && !empty($_POST["edgr"])){
                    $edgr = htmlspecialchars(stripslashes(strip_tags(trim($_POST["edgr"]))));
                    $edname = htmlspecialchars(stripslashes(strip_tags(trim($_POST["edgroup"]))));
                    $ednameo = htmlspecialchars(stripslashes(strip_tags(trim($_POST["edgroupo"]))));
                    $howold = $_POST["howold"];
                    //TODO: howold niebezpieczny;
                    if(preg_match('/^[a-zA-Z0-9]{5,24}$/', $edname)){
                        if($edname!=$ednameo){
                            $db_query = mysqli_query($con, "UPDATE groups SET nazwa = '$edname' WHERE groups.id_rangi = $edgr;");
                            if($db_query){
                                $success .= "Nazwa grupy została zmieniona <br>";
                            }
                        }
                    }else{
                        $error .= "Błędna nazwa(Nazwa od 5-24 znaków a-Z, 0-9 <br>";
                    }
                    $permcount = (count($_POST)-5-$howold)/6;
                    //FIXME: permocunt zle dziala podaje ulamki?!
                    //echo "permcount: $permcount<br>";
                    //echo "howold: $howold<br>";
                    for($i=0;$i<$permcount-1;$i++){
                        //FIXME: TU SIE COS PIERDOLI Z WSTAWIANIEM DODATKOWYCH daje sie tylko 1
                        $grsection = htmlspecialchars(stripslashes(strip_tags(trim($_POST["grsection$i"]))));
                        $grname = htmlspecialchars(stripslashes(strip_tags(trim($_POST["grname$i"]))));
                        $grvalue = htmlspecialchars(stripslashes(strip_tags(trim($_POST["grvalue$i"]))));
                        if(isset($_POST["grid$i"])){
                            $grid = htmlspecialchars(stripslashes(strip_tags(trim($_POST["grid$i"]))));
                        }
                        //echo "dd:$i ";
                        if(!empty($grsection) && !empty($grname) && !empty($grvalue)){

                            $grsectiono = htmlspecialchars(stripslashes(strip_tags(trim($_POST["grsectiono$i"]))));
                            $grnameo = htmlspecialchars(stripslashes(strip_tags(trim($_POST["grnameo$i"]))));
                            $grvalueo = htmlspecialchars(stripslashes(strip_tags(trim($_POST["grvalueo$i"]))));
                            //echo "bb:$i ";
                            if($grsection!=$grsectiono || $grname!=$grnameo || $grvalue!=$grvalueo){
                                //echo "ww:$i ";
                                if($i<$howold){
                                    $db_query = mysqli_query($con, "UPDATE permissions SET section ='$grsection', name ='$grname', value ='$grvalue' WHERE permissions.id = $grid;");
                                }else{
                                    $db_query = mysqli_query($con, "INSERT INTO permissions (id, group_id, section, name, value) VALUES (NULL, '$edgr', '$grsection', '$grname', '$grvalue');");
                                    //echo "tt:$i ";
                                }
                            }
                        }
                        if(empty($grsection) && empty($grname) && $i<$howold){
                            $db_query = mysqli_query($con, "DELETE FROM permissions WHERE permissions.id = $grid;");
                        }
                    }
                    $success .= "Permissje zostały edytowane <br>";
                    //TODO: poprawic succes wyswietla sie nawet jak nic sie nie zmieni!
                }

                if(isset($_POST["addgroup"]) && !empty($_POST["addgroup"])&& !empty($_POST["edgroup"])){
                    $edgroup = htmlspecialchars(stripslashes(strip_tags(trim($_POST["edgroup"]))));
                    $edord = htmlspecialchars(stripslashes(strip_tags(trim($_POST["edord"]))));
                    $db_query = mysqli_query($con, "SELECT id_rangi, nazwa, ord FROM groups WHERE ord>$edord ORDER BY ord");
                    while($db_row = mysqli_fetch_assoc($db_query)){
                        $db_query_inc = mysqli_query($con, "UPDATE groups SET ord = ord+1 WHERE groups.id_rangi =".$db_row['id_rangi']);
                    }

                    $edord++;

                    $db_query = mysqli_query($con, "INSERT INTO `groups` (`id_rangi`, `nazwa`, `ord`) VALUES (NULL, '$edgroup', '$edord')");
                    $success = "Grupa została dodana! <br>";
                }

                if(isset($_GET["delgroup"]) && !empty($_GET["delgroup"])){
                    $delgroup = htmlspecialchars(stripslashes(strip_tags(trim($_GET["delgroup"]))));
                    $db_query = mysqli_query($con, "SELECT ord FROM groups WHERE groups.id_rangi=$delgroup");
                    $db_row = mysqli_fetch_assoc($db_query);
                    $edord = $db_row["ord"];
                    $db_query = mysqli_query($con, "DELETE FROM groups WHERE groups.id_rangi=$delgroup");
                    $db_query = mysqli_query($con, "SELECT id_rangi, nazwa, ord FROM groups WHERE ord>$edord ORDER BY ord");
                    while($db_row = mysqli_fetch_assoc($db_query)){
                        $db_query_inc = mysqli_query($con, "UPDATE groups SET ord = ord-1 WHERE groups.id_rangi =".$db_row['id_rangi']);
                    }
                    $success = "Grupa została usunięta! <br>";
                    //TODO: usuwanie uprawnieni po usunieciu danej grupy;
                }

                if(isset($_GET["groupmove"]) && !empty($_GET["groupmove"]) && !empty($_GET["groupwhere"])){
                    $groupmove = htmlspecialchars(stripslashes(strip_tags(trim($_GET["groupmove"]))));
                    $groupwhere = htmlspecialchars(stripslashes(strip_tags(trim($_GET["groupwhere"]))));
                    if($groupwhere=="down"){
                        $db_query = mysqli_query($con, "SELECT groups.id_rangi FROM groups WHERE groups.ord=(SELECT groups.ord FROM groups WHERE groups.id_rangi=$groupmove)+1");
                        $db_row = mysqli_fetch_assoc($db_query);
                        $nextgroup = $db_row["id_rangi"];
                        $db_query2 = mysqli_query($con, "UPDATE groups SET ord = ord+1 WHERE groups.id_rangi =$groupmove AND groups.ord<((SELECT MAX(ord) FROM (select * from groups) AS gr2));");
                        $db_query1 = mysqli_query($con, "UPDATE groups SET ord = ord-1 WHERE groups.id_rangi =$nextgroup");
                        if($db_query1){
                            $success = "Grupa została przeniesiona w dół! <br>";
                        }else{
                            $error = "Nie udało się przenieść grupy w dół! <br>";
                        }
                    }else if($groupwhere=="up"){
                        $db_query = mysqli_query($con, "SELECT groups.id_rangi FROM groups WHERE groups.ord=(SELECT groups.ord FROM groups WHERE groups.id_rangi=$groupmove)-1");
                        $db_row = mysqli_fetch_assoc($db_query);
                        $nextgroup = $db_row["id_rangi"];
                        $db_query1 = mysqli_query($con, "UPDATE groups SET ord = ord-1 WHERE groups.id_rangi =$groupmove AND groups.ord!=1;");
                        $db_query2 = mysqli_query($con, "UPDATE groups SET ord = ord+1 WHERE groups.id_rangi =$nextgroup;");
                        if($db_query2){
                            $success = "Grupa została przeniesiona w górę! <br>";
                        }else{
                            $error = "Nie udało się przenieść grupy w górę! <br>";
                        }
                    }
                }

                if(!empty($success)){
                    echo "<div class='alert alert-success'><p>$success</p></div>";
                }
                if(!empty($error)){
                    echo "<div class='alert alert-danger'><p>$error</p></div>";
                }
                    if(!$gred){
                ?>

        <form class="form-horizontal" action="index.php?goto=groupeditor" method="post">
            <div class='form-group has-feedback'>
                <label class='control-label col-sm-3' for='edgroup'>Nazwa grupy:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' id="inputError" name='edgroup' placeholder='Nazwa grupy' autocomplete="off">
                </div>
            </div>
            <div class='form-group has-feedback'>
                <label class='control-label col-sm-3' for='edord'>Dodaj pod:</label>
                <div class='col-sm-9'>
                    <select id='inputState' class='form-control' name='edord'>
                        <?php
                $db_query = mysqli_query($con, "SELECT id_rangi, nazwa, ord FROM groups ORDER BY ord DESC;");
                while($db_row = mysqli_fetch_assoc($db_query)){
                    echo "<option value='".$db_row['ord']."'>".$db_row['nazwa']."</option>";
                }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 sumb">
                    <input type='hidden' name='addgroup' value="1">
                    <button type="submit" class="btn btn-default">Dodaj</button>
                </div>
            </div>
        </form>


                    <table class='table table-striped grouplist'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Grupa</th>
                                <th class="kolejnosc">Kolejność</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                $db_query = mysqli_query($con, "SELECT id_rangi, nazwa, ord FROM groups ORDER BY ord;");
                while($db_row = mysqli_fetch_assoc($db_query)){
                    echo "<tr><td>".$db_row['id_rangi']."</td><td>".$db_row['nazwa']."</td><td class='kolejnosc'>".$db_row['ord']."</td>
                    <td class='arrowup'><a href='index.php?goto=groupeditor&groupmove=".$db_row['id_rangi']."&groupwhere=up' class='reglink'><span class='glyphicon glyphicon-arrow-up'></span></a></td>
                    <td class='arrowdown'><a href='index.php?goto=groupeditor&groupmove=".$db_row['id_rangi']."&groupwhere=down' class='reglink'><span class='glyphicon glyphicon-arrow-down'></span></a></td>
                    <td class='delex'><a href='index.php?goto=groupeditor&delgroup=".$db_row['id_rangi']."' class='reglink'><span class='glyphicon glyphicon-remove'></span></a></td>
                    <td><a href='index.php?goto=groupeditor&groupeditor=".$db_row['id_rangi']."' class='reglink'><span class='glyphicon glyphicon-edit'></span></a></td>
                    </tr>";
                }
                            ?>
                        </tbody>
                </table>



                <?php
                    }else{
                        $db_query = mysqli_query($con, "SELECT nazwa FROM groups WHERE groups.id_rangi=$edgroup");
                        $db_row = mysqli_fetch_assoc($db_query);
                        $edname = $db_row["nazwa"];
                        ?>
                            <form class="form-horizontal" action="index.php?goto=groupeditor&groupeditor=<?php echo $edgroup; ?>" method="post">
                                <div class='form-group has-feedback'>
                                    <label class='control-label col-sm-3' for='edgroup'>ID grupy:</label>
                                    <div class='col-sm-9'>
                                        <input type='text' class='form-control' name='edgr' placeholder='ID' value="<?php echo $edgroup ?>" autocomplete="off" readonly>
                                    </div>
                                </div>
                                <div class='form-group has-feedback'>
                                    <label class='control-label col-sm-3' for='edgroup'>Nazwa grupy:</label>
                                    <div class='col-sm-9'>
                                        <input type='text' class='form-control' id="inputError" name='edgroup' value="<?php echo $edname ?>" placeholder='Nazwa grupy' autocomplete="off">
                                        <input type='hidden' name='edgroupo' value="<?php echo $edname ?>">
                                    </div>
                                </div>
                                <?php
                                    $db_query = mysqli_query($con, "SELECT id, section, name, value FROM permissions WHERE group_id=$edgroup;");
                                    $grnext=0;
                                    while($db_row = mysqli_fetch_assoc($db_query)){
                                        echo "<div class='form-group has-feedback'>
                                                <label class='control-label col-sm-3' for='edord$grnext'>Uprawnienie:</label>
                                                <div class='col-sm-9 row'>
                                                    <div class='col-sm-5'>
                                                        <select id='grsection' class='form-control selectormanager' name='grsection$grnext'><option value=''></option>";

                                                            loadpermoption('section',$db_row['section']);

                                                        echo "</select>
                                                        <input type='hidden' name='grsectiono$grnext' value='".$db_row['section']."'>
                                                        <input type='hidden' name='grid$grnext' value='".$db_row['id']."'>

                                                    </div>
                                                    <div class='col-sm-5'>
                                                        <select id='grname' class='form-control' name='grname$grnext'><option value=''></option>";

                                                            loadpermoption('name',$db_row['name'], $db_row['section']);

                                                        echo "</select>
                                                        <input type='hidden' name='grnameo$grnext' value='".$db_row['name']."'>
                                                    </div>
                                                    <div class='col-sm-1'>
                                                        <select id='grvalue' class='form-control inpcenter' name='grvalue$grnext'><option value=''></option>";

                                                            loadpermoption('value',$db_row['value']);

                                                        echo "</select>
                                                        <input type='hidden' name='grvalueo$grnext' value='".$db_row['value']."'>
                                                    </div>
                                                </div>
                                            </div>";
                                        $grnext++;
                                }
                                    echo "<div id='zeroinput'>
                                            <div class='form-group has-feedback'>
                                            <label class='control-label col-sm-3' for='edord$grnext'>Uprawnienie:</label>
                                                <div class='col-sm-9 row'>
                                                    <div class='col-sm-5'>
                                                        <select id='grsection' class='form-control selectormanager' name='grsection$grnext'><option value=''></option>";
                                                            loadpermoption('section',"null");
                                                    echo "</select>
                                                    <input type='hidden' name='grsectiono$grnext' value=' '>
                                                    </div>
                                                    <div class='col-sm-5'>
                                                        <select id='grname' class='form-control' name='grname$grnext'><option value=''></option>";
                                                            loadpermoption('name',"null");
                                                    echo "</select>
                                                    <input type='hidden' name='grnameo$grnext' value=' '>
                                                    </div>
                                                    <div class='col-sm-1'>
                                                        <select id='grvalue' class='form-control inpcenter' name='grvalue$grnext'><option value=''></option>";
                                                            loadpermoption('value',1);
                                                    echo "</select>
                                                    <input type='hidden' name='grvalueo$grnext' value='1'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";


                                    $grnext++;
                                    echo "<span id='grnext'>$grnext</span>";
                                ?>
                                <div id="allinput">

                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10 sumb">
                                        <input type='hidden' name='editgroup' value="1">
                                        <input type='hidden' name='howold' value='<?php echo $grnext-1; ?>'>
                                        <button type="submit" class="btn btn-default">Dodaj</button>
                                    </div>
                                </div>
                            </form>
                            <script>
                                var permnamelist = new Array;
                                <?php
                                    $lastkey = "";
                                    foreach($permnamelist as $key => $value){
                                        foreach($permnamelist[$key] as $key2 => $value2){
                                            if($key!=$lastkey){
                                                echo "permnamelist['$key']= new Array; ";
                                                $lastkey = $key;
                                            }
                                            echo "permnamelist['$key'][$key2]='$value2'; ";
                                        }
                                    }
                                ?>
                            </script>
                            <script src="js/groupeditor.js"></script>


                        <?php
                    }
            }
        }

    ?>
    </div>
</div>

<?php

    }else{
        $error = "Zaloguj się ponownie!";
        require("login.php");
    }
}else{
    session_start();
    $error = "Próba ingerencji!";
    $_SESSION['error'] = $error;
    header('Location: ./');
}
?>
