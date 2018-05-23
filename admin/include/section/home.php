<!--@Home@1@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div class="home">

    panel home<br>


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
