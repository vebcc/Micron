<!--@Wstaw post@2@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div id="addpost">

    <?php


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
Wstaw post
