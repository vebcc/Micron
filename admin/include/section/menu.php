<!--@Menu@5@-->
<!--@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div id="menu">
    <h1> Menu </h1>
    <div class="centercv">

        <?php
        //TODO: error logi i success logi
        //TODO: sprawdzanie czy menu juz istnieje
        //TODO: edycja menu dodawanie usuwanie linkow
        // uprawnienia do zarzadzania menu
        if(isset($_POST["admenu"]) && !empty($_POST["admenu"])&& !empty($_POST["addmenu"])){
            $admenu = htmlspecialchars(stripslashes(strip_tags(trim($_POST["admenu"]))));

            $db_query = mysqli_query($con, "INSERT INTO menu (id, name, main_menu) VALUES (NULL, '$admenu', '0');");
            $success="Dodano nowe menu!";
        }

        ?>

        <form class="form-horizontal" action="index.php?goto=menu" method="post">
            <div class='form-group has-feedback'>
                <label class='control-label col-sm-3' for='admenu'>Nazwa menu:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' id="inputError" name='admenu' placeholder='Nazwa menu' autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 sumb">
                    <input type='hidden' name='addmenu' value="1">
                    <button type="submit" class="btn btn-default">Dodaj</button>
                </div>
            </div>
        </form>

        <div class="row">

           <?php
                $db_query = mysqli_query($con, "SELECT id, name, main_menu FROM menu;");
                while($db_row = mysqli_fetch_assoc($db_query)){
                    $rowid = $db_row["id"];
                    $rowname = $db_row["name"];
                    $rowmain = $db_row["main_menu"];
                    echo "<div class='col-md-6 col-sm-12 col-xs-12'>
                            <form action='index.php?goto=menu&editmenu=$rowid' method='post'>
                            <table class='table table-striped'>
                                <tr>
                                    <th>$rowid</th>
                                    <th><input type='text' class='menuinput' name='edgr' placeholder='ID' value='$rowname'></th>
                                    <th>$rowmain</th>
                                    <th><a href='index.php?goto=menu&delmenu=$rowid' class='reglink'><span class='glyphicon glyphicon-remove'></span></a></th>
                                </tr>";

                    $db_query2 = mysqli_query($con, "SELECT id, tag, name, value, ord FROM menu_list WHERE id_menu='$rowid' ORDER BY ord;");
                    while($db_row2 = mysqli_fetch_assoc($db_query2)){
                        $row2id = $db_row2["id"];
                        $row2name = $db_row2["name"];
                        $row2ord = $db_row2["ord"];

                        echo "<tr>
                                <td>$row2ord</td>
                                <td colspan='2'><input type='text' class='menuinput' name='edgr' placeholder='ID' value='$row2name'></td>
                                <td><a href='index.php?goto=menu&delmenuopt=$row2id' class='reglink'><span class='glyphicon glyphicon-remove'></span></a></td>
                              </tr>";

                    }
                    echo "<tr><td></td><td><button type='submit' class='btn btn-default'>Edytuj</button></td><td></td><td></td></tr>

                    </table></form></div>";
                }
            ?>
        </div>

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
