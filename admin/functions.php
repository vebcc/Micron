<?php
if(isset($_GET['akcja'])){
    $akcja = $_GET['akcja'];
}else{
    $akcja= '';
}
if ($akcja == 'login') {

    $ollogin = $_POST['ollogin'];
    $olhaslo = $_POST['olhaslo'];
    $olhaslo = addslashes($olhaslo);
    $ollogin = addslashes($ollogin);
    $ollogin = htmlspecialchars($ollogin);

    if(isset($_GET['ollogin'])){
        if ($_GET['ollogin'] != '') { //jezeli ktos przez adres probuje kombinowac
            exit;
        }
        if ($_GET['olhaslo'] != '') { //jezeli ktos przez adres probuje kombinowac
            exit;
        }
    }

    $olhaslo = md5($olhaslo); //szyfrowanie hasla
    if (!$ollogin OR empty($ollogin)) {
        //include("head2.php");
        echo '<p class="alert">Wypelnij pole z loginem!</p>';
        //include("foot.php");
        exit;
    }
    if (!$olhaslo OR empty($olhaslo)) {
        //include("head2.php");
        echo '<p class="alert">Wypelnij pole z haslem!</p>';
        //include("foot.php");
        exit;
    }
    $istnick = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `users` WHERE `login` = '$ollogin' AND `password` = '$olhaslo'")); // sprawdzenie czy istnieje uzytkownik o takim nicku i hasle
    if ($istnick[0] == 0) {
        echo 'Logowanie nieudane. Sprawdz pisownie nicku oraz hasla.';
    } else {

        $_SESSION["login"] = $ollogin;
        //$_SESSION['olhaslo'] = $olhaslo;

        echo 'Zostałeś zalogowany!' . $_SESSION['login'];
        header("Location: ./");
    }
}
?>

