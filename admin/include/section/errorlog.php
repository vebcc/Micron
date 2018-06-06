<!--@Error log@4@-->
<!--@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div id="errorlog">
    <h1>Logi</h1>
    <?php
        //TODO: usuwanie logow
        //TODO: uprawnienia do all lub np tylko do przegladania logow
        if(checkpermission("section", "errorlog")){
            if(checkpermission("errorlog")){
                ?>
                <table class='table table-striped'>
                    <tr><th>ID</th><th>Error</th><th>IP</th><th>Data</th><th>UserAgent</th></tr>
                <?php
                $db_query = mysqli_query($con, "SELECT id, error, ip, date, browser FROM fail_login_ban ORDER BY date DESC");
                while($db_row = mysqli_fetch_assoc($db_query)){
                    $alert = "";
                    switch($db_row['error']){
                        case "faillogin":
                            $alert="info";
                            break;
                        case "loginban":
                            $alert="warning";
                            break;
                        case "interference":
                            $alert="danger";
                            break;
                    }
                    echo "<tr class='$alert'><td>".$db_row['id']."</td><td>".$db_row['error']."</td><td>".$db_row['ip']."</td><td>".$db_row['date']."</td><td>".$db_row['browser']."</td></tr>";

                }
                echo "</table>";
            }
        }
    ?>

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
