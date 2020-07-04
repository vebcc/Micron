<?php
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $actual_ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $actual_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $actual_ip = $_SERVER['REMOTE_ADDR'];
}
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){ // sprawdza czy zmienne sesji sa ustawione
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($actual_ip)){ // sprawdza czy zmienne sesji sa zgodne z danymi klienta
        $login = $_SESSION['login'];
        echo "Siemka zalogowany $login!";

        require("include/functions.php"); // include functions.php

        getsections();
        executepermoptlist();
?>

<link rel="stylesheet" href="css/panel_admin.css" type="text/css">
</head>
<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Micron - Panel Admina</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Ustawienia</a></li>
                <li><a href="#">Page 2</a></li>
                <li><a href="#">Page 3</a></li>
            </ul>
            <ul class="nav navbar-nav logout">
                <li><a href="index.php?logout=1">Wyloguj się</a></li>
                <li><a href="#">ULOGO</a></li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid mycontainer">
        <div class="row">
            <div class="col-md-2 menu">
                <ul class="nav nav-pills nav-stacked ulmenu">
                   <?php
                        for($i=0;$i<count($alink);$i++){ // petla wyswietlajaca wszystkie linki z plikow
                            if(checkpermission("section", $alink[$i][1])){
                                echo "<li><a href='index.php?goto=".$alink[$i][1]."'>".$alink[$i][2]."</a></li>"; // wyswietlenie linku w menu
                            }
                        }
                    ?>
                    <!--<li class="active"><a href="index.php?goto=home">Home</a></li> -->
                </ul>
            </div>
            <div class="col-md-10 mycontent">
                <div class="content">
                    <?php
                       if(isset($_GET['goto'])){ // czy zmienna goto w get jest ustawiona
                           require("include/section/". $_GET['goto'] .".php"); // jesli tak to przekieruj do pliku z przeslana nazwa
                       }else{
                           require("include/section/home.php"); // jesli nie to przekieruj do home
                       }
                        //for($i=2;$i<count($includelist);$i++){
                        //    $asection = explode(".", $includelist[$i]);
                        //    echo "a: " . $asection[0] ."<br>";
                        //}
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php

        if(isset($_GET['logout']) && $_GET['logout']==1){ // czy zmienna logout w get jest ustawiona
            logout();
        }

    }else{
        $error = "Zaloguj się ponownie! (PA)"; // przeslanie bledu logowania
        require("login.php"); // wyswietlenie formularza logowania
    }
}else{
    session_start(); // start sesji
    $error = "Próba ingerencji!"; // wyswielenie erroru proby ingerencji
    $_SESSION['error'] = $error; // wstawienie $errora do sesji error
    header('Location: ./'); // przekierowanie do index.php
}
?>
