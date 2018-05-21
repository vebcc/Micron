<html>
    <head>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" crossorigin="anonymous">

        <?php

        session_start();

        if(!isset($_SESSION["login"])){
            require("login.php");

            if (isset($_POST['cookie']) && !empty($_POST['logem'])) { // jeżeli formularz został wysłany, to wykonuje się poniższy skrypt
                require("config.php");
                require("connection.php");

                $logem = htmlspecialchars(stripslashes(strip_tags(trim($_POST["logem"]))));
                $pwd = htmlspecialchars(stripslashes(strip_tags(trim($_POST["pwd"]))));

                $pwd = md5($pwd);

                $db_query = mysqli_query($con,"SELECT users.login, users.email, users.password FROM users WHERE users.login='$logem' OR users.email='$logem';");

                $db_row = mysqli_fetch_assoc($db_query);

                if($pwd == $db_row["password"]){ // czy hasla sie zgadzaja
                    // ZALOGOWANO!
                    $login = $db_row["login"];
                    $_SESSION["login"] = $login;
                    header('Location: index.php');
                }else{
                    echo "Zły login lub hasło!";
                }

                $db_query->free();



            }



        }else{
            //include("register.php");
            //include("login.php");
            echo "Siemka zalogowany!";
        }

        //echo 'sesja: ' . $_SESSION["login"];
        //echo '<Br>sesja empty: ' . empty($slogin);

        ?>

        <!-- Bootstrap core JavaScript -->
        <script src="jquery/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
