<!--@Dodaj klienta@7@-->
<!--@-->
<?php
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $actual_ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $actual_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $actual_ip = $_SERVER['REMOTE_ADDR'];
}
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    //TODO: remote addr na funkcje sprawdzajaca 3 pozostale mozliwe adresy ip
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($actual_ip)){
        $login = $_SESSION['login'];
?>

<div id="register">
   <?php
        $edituser=0;
        if(isset($_GET["usereditor"]) && !empty($_GET["usereditor"])){
            $edituser=1;
            echo "<h1>Edytuj użytkownika</h1>";
        }else{
            echo "<h1>Dodaj Użytkownika</h1>";
        }

        if(checkpermission("section", "register")){
            if(checkpermission("register")){
                $success="";

                if(isset($_GET["deluser"]) && !empty($_GET["deluser"])){
                    $deluser = htmlspecialchars(stripslashes(strip_tags(trim($_GET["deluser"]))));
                    $db_query = mysqli_query($con, "DELETE FROM users WHERE users.user_id=$deluser");
                    $success = "Użytkownik został usunięty!";
                }

                $regerror=" ";
                $regfail= array("login"=>0,"email"=>0,"pwd"=>0,"pwd2"=>0,"rang"=>0, "pwd2no"=>0, "loginex"=>0, "emailex"=>0, "pwdbad"=>0, "emailbad"=>0, "loginbad"=>0);
                $nofail = 1;
                if(isset($_POST["register"]) || isset($_POST["edituser"])){

                    if(!empty($_POST["reglogin"])){
                        $reglogin = htmlspecialchars(stripslashes(strip_tags(trim($_POST["reglogin"]))));
                    }else{
                        $regfail["login"]=1;
                        $nofail=0;
                    }
                    if(!empty($_POST["regemail"])){
                        $regemail = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regemail"]))));
                    }else{
                        $regfail["email"]=1;
                        $nofail=0;
                    }
                    if(!empty($_POST["regrang"])){
                        $regrang = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regrang"]))));
                    }else{
                        $regfail["rang"]=1;
                        $nofail=0;
                    }

                    if(!$regfail["login"]){
                        if(!preg_match('/^[a-zA-Z0-9]{5,24}$/', $reglogin)){
                            $regfail["loginbad"]=1;
                            $nofail=0;
                        }
                    }

                    if(!$regfail["email"]){
                        if(!preg_match('/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,4}$/', $regemail)){
                            $regfail["emailbad"]=1;
                            $nofail=0;
                        }
                    }

                }
                if(isset($_POST["register"])){
                    if(!empty($_POST["regpwd"])){
                        $regpwd = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regpwd"]))));
                    }else{
                        $regfail["pwd"]=1;
                        $nofail=0;
                    }
                    if(!empty($_POST["regpwd2"])){
                        $regpwd2 = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regpwd2"]))));
                    }else{
                        $regfail["pwd2"]=1;
                        $nofail=0;
                    }
                    if(!$regfail["pwd"] && !$regfail["pwd2"]){
                        if(!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $regpwd)){
                            $regfail["pwdbad"]=1;
                            $nofail=0;
                        }else{
                            if($regpwd!=$regpwd2){
                                $regfail["pwd2no"]=1;
                                $nofail=0;
                            }
                        }
                    }
                    if($nofail){
                        $db_query = mysqli_query($con, "SELECT login, email FROM users WHERE login='$reglogin';");
                        if(mysqli_num_rows($db_query)){
                            $regfail["loginex"]=1;
                            $nofail=0;
                        }

                        $db_query = mysqli_query($con, "SELECT login, email FROM users WHERE email='$regemail';");
                        if(mysqli_num_rows($db_query)){
                            $regfail["emailex"]=1;
                            $nofail=0;
                        }
                    }

                    if($nofail){
                        $regpwd = md5($regpwd);

                        $db_query = mysqli_query($con, "INSERT INTO `users` (`user_id`, `login`, `email`, `password`, `ranga_id`) VALUES (NULL, '$reglogin', '$regemail', '$regpwd', '$regrang');");

                        $success = "Użytkownik został dodany!";
                    }
                }

                if(isset($_POST["edituser"])){
                    $useraid = $_POST["edituser"];
                    if(empty($regpwd) && empty($regpwd2)){
                        $passwd = "";
                    }else{
                        if(!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $regpwd)){
                            $regfail["pwdbad"]=1;
                            $nofail=0;
                        }else{
                            if($regpwd!=$regpwd2){
                                $regfail["pwd2no"]=1;
                                $nofail=0;
                            }
                        }
                        $regpwd = md5($regpwd);
                        $passwd = ", password = '$regpwd'";
                    }
                    if($nofail){
                        $db_query = mysqli_query($con, "SELECT login, email FROM users WHERE login='$reglogin';");
                        if(mysqli_num_rows($db_query)){
                            $db_row = mysqli_fetch_assoc($db_query);
                            if($db_row["login"]!=$reglogin){
                                $regfail["loginex"]=1;
                                $nofail=0;
                            }
                        }

                        $db_query = mysqli_query($con, "SELECT login, email FROM users WHERE email='$regemail';");
                        if(mysqli_num_rows($db_query)){
                            $db_row = mysqli_fetch_assoc($db_query);
                            if($db_row["email"]!=$regemail){
                                $regfail["emailex"]=1;
                                $nofail=0;
                            }
                        }
                    }
                    if($nofail){

                        $db_query = mysqli_query($con, "UPDATE users SET login = '$reglogin', email = '$regemail',$passwd ranga_id = $regrang WHERE users.user_id = $useraid;");
                        $success = "Użytkownik został edytowany!";
                    }
                }
                ?>
    <div class="centercv">

            <?php
                if(!$nofail){
                    echo "<div class='alert alert-danger registerfail'>";
                    if($regfail["login"]){
                        echo "<p>Wprowadź login</p>";
                    }
                    if($regfail["loginbad"]){
                        echo "<p>Niepoprawny login (Login musi zawierać od 5-24 znaków</p>";
                    }
                    if($regfail["loginex"] && !empty($reglogin)){
                        echo "<p>Login jest już zajęty</p>";
                    }
                    if($regfail["email"]){
                        echo "<p>Wprowadź email</p>";
                    }
                    if($regfail["emailbad"]){
                        echo "<p>Wprowadź poprawny adres email</p>";
                    }
                    if($regfail["emailex"] && !empty($regemail)){
                        echo "<p>Email jest już zajęty</p>";
                    }
                    if($regfail["pwd"]){
                        echo "<p>Wprowadź hasło</p>";
                    }
                    if($regfail["pwdbad"]){
                        echo "<p>Niepoprawne hasło (Hasło musi zawierać conajmniej 8 znaków, 1 mała literę, 1 dużą literę oraz 1 znak specjalny lub liczbę)</p>";
                    }
                    if($regfail["pwd2"]){
                        echo "<p>Powtórz hasło</p>";
                    }
                    if($regfail["pwd2no"]){
                        echo "<p>Hasła się nie zgadzają</p>";
                    }
                    if($regfail["rang"]){
                        echo "<p>Wprowadź rangę</p>";
                    }
                    echo "</div>";
                }

                if(!empty($success)){
                    echo "<div class='alert alert-success'><p>$success</p></div>";
                }

                if($edituser){
                    //TODO: Panel edycji usera;
                    $editthisuser= htmlspecialchars(stripslashes(strip_tags(trim($_GET["usereditor"]))));
                    $db_query = mysqli_query($con, "SELECT login, email, ranga_id FROM users WHERE user_id=$editthisuser;");
                    $db_row = mysqli_fetch_assoc($db_query);
                    ;
                    $reglogin = $db_row["login"];
                    $regemail = $db_row["email"];
                    $regpwd = "";
                    $regpwd2 = "";
                    $regrang = $db_row["ranga_id"];;
                }

            ?>

        <form class="form-horizontal" action="index.php?goto=register<?php if($edituser){echo "&usereditor=".$_GET["usereditor"];} ?>" method="post">
            <div class='form-group <?php if($regfail["login"] || $regfail["loginbad"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='reglogin'>Login:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' id="inputError" name='reglogin' placeholder='Login' value ="<?php if(!$regfail["login"] && isset($reglogin) && !$regfail["loginbad"]){echo $reglogin;} ?>" autocomplete="off">
                    <?php if($regfail["login"]|| $regfail["loginbad"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["email"] || $regfail["emailbad"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regemail'>Email:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' name='regemail' placeholder='Email' value ="<?php if(!$regfail["email"] && isset($regemail) && !$regfail["emailbad"]){echo $regemail;} ?>"  autocomplete="off">
                    <?php if($regfail["email"] || $regfail["emailbad"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["pwd"] || $regfail["pwdbad"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regpwd'>Hasło:</label>
                <div class='col-sm-9'>
                    <input type='password' class='form-control' name='regpwd' placeholder='Hasło' value ="<?php if(!$regfail["pwd"] && isset($regpwd) && !$regfail["pwdbad"]){echo $regpwd;} ?>" autocomplete="off">
                    <?php if($regfail["pwd"] || $regfail["pwdbad"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["pwd2"] || $regfail["pwd2no"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regpwd2'>Powtórz hasło:</label>
                <div class='col-sm-9'>
                    <input type='password' class='form-control' name='regpwd2' placeholder='Powtórz hasło' value ="<?php if(!$regfail["pwd2"] && isset($regpwd2) && !$regfail["pwd2no"]){echo $regpwd2;} ?>" autocomplete="off">
                    <?php if($regfail["pwd2"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>


            <div class='form-group <?php if($regfail["rang"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regrang'>Grupa:</label>
                <div class='col-sm-9'>
                    <select id='inputState' class='form-control' name='regrang'>
                        <?php
                            $db_query = mysqli_query($con, "SELECT id_rangi, nazwa FROM groups ORDER BY ord;");
                            while($db_row = mysqli_fetch_assoc($db_query)){
                                $addrang = "";
                                if(!$regfail["rang"]){
                                    if($regrang==$db_row['id_rangi']){
                                        $addrang = "selected";
                                    }
                                }
                                echo "<option value='".$db_row['id_rangi']."' $addrang>".$db_row['nazwa']."</option>";
                            }
                        ?>
                    </select>
                    <?php if($regfail["rang"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 sumb">
                    <input type='hidden' name='<?php if(!$edituser){echo "register";}else{echo "edituser";} ?>' value="<?php if(!$edituser){echo 1;}else{echo $_GET["usereditor"];} ?>">
                    <button type="submit" class="btn btn-default">Dodaj</button>
                </div>
            </div>
        </form>

        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Grupa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $db_query = mysqli_query($con, "SELECT users.login, users.email, groups.nazwa, users.user_id FROM users, groups WHERE users.ranga_id=groups.id_rangi ORDER BY users.ranga_id;");
                while($db_row = mysqli_fetch_assoc($db_query)){
                    echo "<tr><td>".$db_row['user_id']."</td><td>".$db_row['login']."</td><td>".$db_row['email']."</td><td>".$db_row['nazwa']."</td>
                    <td><a href='index.php?goto=register&deluser=".$db_row['user_id']."' class='reglink'><span class='glyphicon glyphicon-remove'></span></a></td>
                    <td><a href='index.php?goto=register&usereditor=".$db_row['user_id']."' class='reglink'><span class='glyphicon glyphicon-edit'></span></a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <?php
            }
        }
        ?>

    </div>
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
