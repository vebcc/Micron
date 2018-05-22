<!--@Home@1@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div class="home">

    panel home<br>

    <?php
        // TESTER PERMISSJI
        echo "page: " . checkpermission("page") . "<br>";
        echo "section: " . checkpermission("section") . "<br>";
        echo "nic: " . checkpermission("nic") . "<br>";
        echo "settings: " . checkpermission("settings") . "<br>";
        echo "section adduser: " . checkpermission("section", "adduser") . "<br>";
        echo "section settings: " . checkpermission("section", "settings") . "<br>";
        echo "settings banner: " . checkpermission("settings", "banner") . "<br>";
        echo "settings ifcon: " . checkpermission("settings", "ifon") . "<br>";

    //FIXME: jezeli ktos poda np section a user ma * to do tablicy wejda wszystkie
    //TODO: test uprawnien czy wszystkie sa sprawne

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
