<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){ // sprawdza czy zmienne sesji sa ustawione
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){ // sprawdza czy zmienne sesji sa zgodne z danymi klienta
        $login = $_SESSION['login'];
        echo "Siemka zalogowany $login!";

        require("include/functions.php"); // include functions.php

        $directory = 'include/section'; // folder sekcji
        $includelist = scandir($directory); // wyszukuje wszystkie sekcje

        for($i=2;$i<count($includelist);$i++){ // petla konwertujaca wyniki skanu
            $afile = fopen("include/section/".$includelist[$i],"r"); // otwiera plik
            $atitle = explode("@", fgets($afile)); // pobiera 1 linijke, dzieli ja @ i zapisuje do tablicy
            fclose($afile); // wyjscie z pliku
            $asection = explode(".", $includelist[$i]); // oddziela .php od nazwy pliku zapisuajc tylko nazwe
            $alink[$i-2] = array($atitle[2], $asection[0], $atitle[1]); // zapisuje przekonwertowane wyniki do tablicy
        }
        sort($alink); // sortowanie tablicy zgodnie z ich numeracja w plikach
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
        </div>
    </nav>
    <div class="container-fluid mycontainer">
        <div class="row">
            <div class="col-md-2 menu">
                <ul class="nav nav-pills nav-stacked ulmenu">
                   <?php
                        for($i=0;$i<count($alink);$i++){ // petla wyswietlajaca wszystkie linki z plikow
                            echo "<li><a href='index.php?goto=".$alink[$i][1]."'>".$alink[$i][2]."</a></li>"; // wyswietlenie linku w menu
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
                           require("include/home.php"); // jesli nie to przekieruj do home
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

    }else{
        $error = "Zaloguj się ponownie!"; // przeslanie bledu logowania
        require("login.php"); // wyswietlenie formularza logowania
    }
}else{
    session_start(); // start sesji
    $error = "Próba ingerencji!"; // wyswielenie erroru proby ingerencji
    $_SESSION['error'] = $error; // wstawienie $errora do sesji error
    header('Location: ./'); // przekierowanie do index.php
}
?>
