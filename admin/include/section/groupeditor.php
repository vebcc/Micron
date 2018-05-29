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

                if(isset($_POST["addgroup"]) && !empty($_POST["addgroup"])&& !empty($_POST["edgroup"])){
                    $edgroup = htmlspecialchars(stripslashes(strip_tags(trim($_POST["edgroup"]))));
                    $edord = htmlspecialchars(stripslashes(strip_tags(trim($_POST["edord"]))));
                    $db_query = mysqli_query($con, "SELECT id_rangi, nazwa, ord FROM groups WHERE ord>$edord ORDER BY ord");
                    while($db_row = mysqli_fetch_assoc($db_query)){
                        $db_query_inc = mysqli_query($con, "UPDATE groups SET ord = ord+1 WHERE groups.id_rangi =".$db_row['id_rangi']);
                    }

                    $edord++;

                    $db_query = mysqli_query($con, "INSERT INTO `groups` (`id_rangi`, `nazwa`, `ord`) VALUES (NULL, '$edgroup', '$edord')");
                    $success = "Grupa została dodana!";
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
                    $success = "Grupa została usunięta!";
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
                            $success = "Grupa została przeniesiona w dół!";
                        }else{
                            $error = "Nie udało się przenieść grupy w dół!";
                        }
                    }else if($groupwhere=="up"){
                        $db_query = mysqli_query($con, "SELECT groups.id_rangi FROM groups WHERE groups.ord=(SELECT groups.ord FROM groups WHERE groups.id_rangi=$groupmove)-1");
                        $db_row = mysqli_fetch_assoc($db_query);
                        $nextgroup = $db_row["id_rangi"];
                        $db_query1 = mysqli_query($con, "UPDATE groups SET ord = ord-1 WHERE groups.id_rangi =$groupmove AND groups.ord!=1;");
                        $db_query2 = mysqli_query($con, "UPDATE groups SET ord = ord+1 WHERE groups.id_rangi =$nextgroup;");
                        if($db_query2){
                            $success = "Grupa została przeniesiona w górę!";
                        }else{
                            $error = "Nie udało się przenieść grupy w górę!";
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
                            <form class="form-horizontal" action="index.php?goto=groupeditor" method="post">
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
                                    </div>
                                </div>
                                <?php
                                    $db_query = mysqli_query($con, "SELECT section, name, value FROM permissions WHERE group_id=$edgroup;");
                                    $grnext=0;
                                    while($db_row = mysqli_fetch_assoc($db_query)){
                                        echo "<div class='form-group has-feedback'>
                                                <label class='control-label col-sm-3' for='edord$grnext'>Uprawnienie:</label>
                                                <div class='col-sm-9 row'>
                                                    <div class='col-sm-5'>
                                                        <select id='grsection' class='form-control' name='grsection$grnext'><option value=''></option>";

                                                            loadpermoption('section',$db_row['section']);

                                                        echo "</select>

                                                    </div>
                                                    <div class='col-sm-5'>
                                                        <select id='grname' class='form-control' name='grname$grnext'><option value=''></option>";

                                                            loadpermoption('name',$db_row['name'], $db_row['section']);

                                                        echo "</select>
                                                    </div>
                                                    <div class='col-sm-1'>
                                                        <select id='grvalue' class='form-control inpcenter' name='grvalue$grnext'><option value=''></option>";

                                                            loadpermoption('value',$db_row['value']);

                                                        echo "</select>
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
                                                        <select id='grsection' class='form-control' name='grsection$grnext'><option value=''></option>";
                                                            loadpermoption('section',0);
                                                    echo "</select>
                                                    </div>
                                                    <div class='col-sm-5'>
                                                        <select id='grname' class='form-control' name='grname$grnext'><option value=''></option>";
                                                            loadpermoption('name',0);
                                                    echo "</select>
                                                    </div>
                                                    <div class='col-sm-1'>
                                                        <select id='grvalue' class='form-control inpcenter' name='grvalue$grnext'><option value=''></option>";
                                                            loadpermoption('value',1);
                                                    echo "</select>
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
                                        <input type='hidden' name='addgroup' value="1">
                                        <button type="submit" class="btn btn-default">Dodaj</button>
                                    </div>
                                </div>
                            </form>
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
