<!--@Generator wkładek@5@-->
<!--@create@delete@edit@-->
<?php

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $actual_ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $actual_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $actual_ip = $_SERVER['REMOTE_ADDR'];
}

if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($actual_ip)){
        $login = $_SESSION['login'];
?>

<div id="generator">

    <h1>Generator wkładek</h1>
    <?php
        if(checkpermission("section", "generator")){
            if(checkpermission("generator")){
    ?>
                <table class='table table-striped'>
                    <tr><th>ID</th><th>Nazwa</th><th>Wartość</th><th>Usuń</th></tr>
    <?php
                    $db_query = mysqli_query($con, "SELECT id, name, value FROM generator ORDER BY id DESC");
                while($db_row = mysqli_fetch_assoc($db_query)){

                    echo "<tr class=''><td>".$db_row['id']."</td><td>".$db_row['name']."</td><td>".$db_row['value']."</td><td class='delex'><a href='index.php?goto=groupeditor&delgroup=".$db_row['id']."' class='reglink'><span class='glyphicon glyphicon-remove'></span></a></td></tr>";

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
