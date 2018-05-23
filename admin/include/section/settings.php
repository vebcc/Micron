<!--@Ustawienia@8@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div id="settings">
    <h1>Ustawienia</h1>
    <div class="centercv">
        <form class="form-horizontal" action="index.php?goto=settings" method="post">
            <?php
        if(checkpermission("section", "settings")){
            if(checkpermission("settings")){
                if(!count($permlist)){
                    $fullperm=1;
                }else{
                    $fullperm=0;
                }

                foreach($_POST as $key => $value){
                    if(isset($permlist[$key]) OR $fullperm){
                        if($value){
                            $key = htmlspecialchars(stripslashes(strip_tags(trim($key))));
                            $value = htmlspecialchars(stripslashes(strip_tags(trim($value))));
                            $db_query = mysqli_query($con, "UPDATE `micron`.`settings` SET `pl_value` = '$value' WHERE `settings`.`name` = '$key';");
                        }
                    }
                }

                $db_query = mysqli_query($con, "SELECT name, pl_value, description FROM settings;");
                while($db_row = mysqli_fetch_assoc($db_query)){
                    if(isset($permlist[$db_row["name"]]) OR $fullperm){
                        //echo "to: " . $db_row["name"] . " opis: " . $db_row["description"] . " value: " . $db_row["pl_value"]. "<br>";
                        echo "<div class='form-group'>
                                        <label class='control-label col-sm-3' for='".$db_row['name']."'>".$db_row['description'].":</label>
                                        <div class='col-sm-9'>
                                            <input type='text' class='form-control' name='".$db_row['name']."' placeholder='".$db_row['pl_value']."'>
                                        </div>
                                  </div>";
                    }
                }
            }
        }
            ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 sumb">
                    <button type="submit" class="btn btn-default">Prześlij</button>
                </div>
            </div>
        </form>
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

