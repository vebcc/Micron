<!--@Edytor grup@8@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div id="groupeditor">
   <h1>Dodaj grupę</h1>
    <div class="centercv">
    <?php
        if(checkpermission("section", "groupeditor")){
            if(checkpermission("groupeditor")){
                $success="";

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
                    $db_query = mysqli_query($con, "DELETE FROM groups WHERE groups.id_rangi=$delgroup");
                    $success = "Grupa została usunięta!";
                }

                if(isset($_GET["groupmove"]) && !empty($_GET["groupmove"]) && !empty($_GET["groupwhere"])){
                    $groupmove = htmlspecialchars(stripslashes(strip_tags(trim($_GET["groupmove"]))));
                    $groupwhere = htmlspecialchars(stripslashes(strip_tags(trim($_GET["groupwhere"]))));
                    if($groupwhere=="down"){
                        $db_query = mysqli_query($con, "SELECT groups.id_rangi FROM groups WHERE groups.ord=(SELECT groups.ord FROM groups WHERE groups.id_rangi=$groupmove)+1");
                        $db_row = mysqli_fetch_assoc($db_query);
                        $nextgroup = $db_row["id_rangi"];
                        $db_query = mysqli_query($con, "UPDATE groups SET ord = ord-1 WHERE groups.id_rangi =".$nextgroup);
                        $db_query = mysqli_query($con, "UPDATE groups SET ord = ord+1 WHERE groups.id_rangi =".$groupmove);
                        $success = "Grupa została przeniesiona w dół!";
                    }else if($groupwhere=="up"){
                        $db_query = mysqli_query($con, "SELECT groups.id_rangi FROM groups WHERE groups.ord=(SELECT groups.ord FROM groups WHERE groups.id_rangi=$groupmove)-1");
                        $db_row = mysqli_fetch_assoc($db_query);
                        $nextgroup = $db_row["id_rangi"];
                        $db_query = mysqli_query($con, "UPDATE groups SET ord = ord-1 WHERE groups.id_rangi =".$groupmove);
                        $db_query = mysqli_query($con, "UPDATE groups SET ord = ord+1 WHERE groups.id_rangi =".$nextgroup);
                        $success = "Grupa została przeniesiona w górę!";
                    }
                }

                if(!empty($success)){
                    echo "<div class='alert alert-success'><p>$success</p></div>";
                }

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
                    <td><a href='index.php?goto=gropeditor&groupeditor=".$db_row['id_rangi']."' class='reglink'><span class='glyphicon glyphicon-edit'></span></a></td>
                    </tr>";
                }
                            ?>
                        </tbody>
                </table>



                <?php
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
