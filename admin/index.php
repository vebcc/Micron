<html lang="pl">
    <head>
        <title>Panel Admina - Micron</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" crossorigin="anonymous">

        <?php
        set_include_path(get_include_path() . PATH_SEPARATOR . 'D:\Pliki\Git');
        session_start(); // start sesji

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $actual_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $actual_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $actual_ip = $_SERVER['REMOTE_ADDR'];
        }

        //$actual_ip = $_SERVER['REMOTE_ADDR']; // pobranie adresu ip klienta

        //TODO: zabezpieczenie przed get.
        //if(isset($_GET['ollogin'])){
        //    if ($_GET['ollogin'] != '') { //jezeli ktos przez adres probuje kombinowac
        //        exit;
        //    }
        //    if ($_GET['olhaslo'] != '') { //jezeli ktos przez adres probuje kombinowac
        //        exit;
        //    }
        //}

        $db = 1; // zmiena pozwalajaca na rozruch pliku connection.php
        require("include/config.php"); // dane logowania mysql
        require("include/connection.php"); // polaczenie z baza danych

        if(!isset($_SESSION["error"])){ // czy sesja zawiera jakis error
            if(!isset($_SESSION["login"]) && !isset($_SESSION['token']) && !isset($_SESSION['token2'])){ // czy zmienne sesji nie sa ustawione
                if (isset($_POST['cookie']) && isset($_POST['logem'])) { // czy zmienne formularza sa ustawione / przeslane

                    //$db = 1; // zmiena pozwalajaca na rozruch pliku connection.php
                    //require("include/config.php"); // dane logowania mysql
                    //require("include/connection.php"); // polaczenie z baza danych

                    // zapytanie sprawdzajace czy dane ip ma permbana
                    $db_query = mysqli_query($con,"SELECT COUNT(*) AS fullblock FROM fail_login_ban WHERE ip='$actual_ip' AND fail_login_ban.error='interference';");
                    $db_row = mysqli_fetch_assoc($db_query);
                    $ifban = $db_row["fullblock"];
                    $db_query->free();

                    if($ifban==0){ // sprawdzanie permbana

                        // zapytanie sprawdzajace ilosc banow klienta w ciagu miesiaca
                        $db_query = mysqli_query($con,"SELECT COUNT(*) AS countban FROM fail_login_ban WHERE ip='$actual_ip' AND fail_login_ban.error='loginban' AND fail_login_ban.date >= DATE_ADD(now(), INTERVAL -1 MONTH);");
                        $db_row = mysqli_fetch_assoc($db_query);

                        $countban = $db_row["countban"]; // zapisuje ilosc banow klienta
                        $banvalue = array(0, "MINUTE", 10, "MINUTE", 30, "MINUTE", 1, "HOUR", 1, "DAY", 7, "DAY");
                        $db_query->free(); // czyszczenie kolejki
                        $banned = array($banvalue[$countban*2], $banvalue[($countban*2)+1]); // ustawia czas bana na podstawie ilosci poprzednich banow

                        // zapytanie sprawdzajace czy dana osoba ma aktywnego bana tymczasowego
                        $db_query = mysqli_query($con,"SELECT COUNT(*) AS banned FROM fail_login_ban WHERE ip='$actual_ip' AND fail_login_ban.error='loginban' AND DATE_ADD(fail_login_ban.date, INTERVAL ".$banned[0]." ".$banned[1].") >= now() ORDER BY date DESC LIMIT 1;");
                        $db_row = mysqli_fetch_assoc($db_query);
                        $db_query->free();

                        if($db_row["banned"]==0){ // ban tymczasowy

                            $logem = htmlspecialchars(stripslashes(strip_tags(trim($_POST["logem"])))); //pobieranie i sprawdzanie loginu/emailu
                            $pwd = htmlspecialchars(stripslashes(strip_tags(trim($_POST["pwd"])))); // pobieranie i sprawdzanie hasla

                            $pwd = md5($pwd); // hashowanie hasla

                            //zapytanie pobierajace  haslo dla danego emaila lub loginu
                            $db_query = mysqli_query($con,"SELECT users.login, users.email, users.password FROM users WHERE users.login='$logem' OR users.email='$logem';");

                            $db_row = mysqli_fetch_assoc($db_query);

                            if($pwd == $db_row["password"]){ // czy hasla sie zgadzaja
                                // ZALOGOWANO!
                                $login = $db_row["login"]; // pobieranie loginu (po to  gdy np podal email)
                                $_SESSION["login"] = $login; // zapis loginu do sesji
                                $_SESSION['token'] = md5($_SERVER['HTTP_USER_AGENT']); // zapis user agent do sesji w md5
                                $_SESSION['token2'] = md5($actual_ip); // zapis ip klienta do sesji w md5
                                header('Location: ./'); // przeniesienie zalogowanego klienta na strone glowna (refresh)
                            }else{
                                $error = "Zły login lub hasło!"; // wyslanie erroru o blednym hasle

                                //funkcja wprowadzajaca blad o blednym logowaniu (bledny login lub haslo)
                                $db_query = mysqli_query($con,"INSERT INTO `micron`.`fail_login_ban` (`id`, `error`, `ip`, `date`, `browser`) VALUES (NULL, 'faillogin', '$actual_ip', CURRENT_TIMESTAMP, '".$_SERVER['HTTP_USER_AGENT']."');");

                                // zapytanie sprawdzajace ile banow w ciagu ostatnich 10 min dostal klient o danym ip
                                $db_query = mysqli_query($con,"SELECT COUNT(*) AS ilosc FROM fail_login_ban WHERE ip='$actual_ip' AND fail_login_ban.error='faillogin' AND fail_login_ban.date >= DATE_ADD(now(), INTERVAL -10 MINUTE);");
                                $db_row = mysqli_fetch_assoc($db_query);
                                $db_query->free(); // czyszczenie kolejki

                                if($db_row["ilosc"]>=3){ // sprawdzanie czy klient o danym ip dostal juz 3 bany w ciagu 10 minut
                                    // jesli tak
                                    // funkcja dodajaca bana do bazy
                                    $db_query = mysqli_query($con,"INSERT INTO `micron`.`fail_login_ban` (`id`, `error`, `ip`, `date`, `browser`) VALUES (NULL, 'loginban', '$actual_ip', CURRENT_TIMESTAMP, '".$_SERVER['HTTP_USER_AGENT']."');");
                                    header('Location: ./'); // przeniesienie klienta na strone glowna (refresh)
                                }
                            }
                        }else{
                            switch($banned[1]){ //Konwertowanie tekstu do wyswietlenia
                                case "MINUTE":
                                    $bantext = "Minut";
                                    break;
                                case "HOUR":
                                    $bantext = "Godzin";
                                    break;
                                case "DAY":
                                    $bantext = "Dni";
                                    break;
                            }
                            $error = "Jesteś zbanowany na ". $banned[0] . " " . $bantext . "."; // error mowiacy o banie czasowym
                        }
                    }else{
                        $error = "Zostałeś permamentnie zbanowany. Jeżeli uważasz że to błąd skontaktuj się z Administratorem."; // error o perm banie
                    }
                }
                require("login.php"); // include formularza logowania
            }else{
                if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){ // sprawdzanie czy dane z sesji zgadzaja sie z danymi klienta
                    $login = $_SESSION['login'];
                    require("panel_admin.php"); // include panel admina
                }else{
                    $error = "Zaloguj się ponownie!"; // error przez inne dane sesji i klienta (moze blad moze próba ataku sesji)
                }
            }
        }else{
            $error = $_SESSION['error']; // przeslanie error sesji z panelu admina
            unset($_SESSION['error']); // usuniecie zmiennej error z sesji

            // dodanie do bazy informacji o probie ingerencji przez wejscie w inne pliki niz index.php (rownoznaczne z perm banem)
            $db_query = mysqli_query($con,"INSERT INTO `micron`.`fail_login_ban` (`id`, `error`, `ip`, `date`, `browser`) VALUES (NULL, 'interference', '$actual_ip', CURRENT_TIMESTAMP, '".$_SERVER['HTTP_USER_AGENT']."');");

            require("login.php"); // include formularza logowania
        }
        $con->close(); // konczenie polaczenia z baza danych
        ?>

        <!-- Bootstrap core JavaScript -->
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
