<!--@Dodaj klienta@7@-->
<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
?>

<div id="register">
    <h1>Dodaj Użytkownika</h1>

            <?php
        if(checkpermission("section", "register")){
            if(checkpermission("register")){

                $regerror="";
                $regfail= array("login"=>0,"email"=>0,"pwd"=>0,"pwd2"=>0,"rang"=>0);
                $nofail = 1;
                if(isset($_POST["register"])){

                    if(!empty($_POST["reglogin"])){
                        $reglogin = htmlspecialchars(stripslashes(strip_tags(trim($_POST["reglogin"]))));
                    }else{
                        $regfail["login"]=1;
                        $regerror="Wprowadź login <br>";
                        $nofail=0;
                    }
                    if(!empty($_POST["regemail"])){
                        $regemail = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regemail"]))));
                    }else{
                        $regfail["email"]=1;
                        $regerror="Wprowadź email <br>";
                        $nofail=0;
                    }
                    if(!empty($_POST["regpwd"])){
                        $regpwd = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regpwd"]))));
                    }else{
                        $regfail["pwd"]=1;
                        $regerror="Wprowadź hasło <br>";
                        $nofail=0;
                    }
                    if(!empty($_POST["regpwd2"])){
                        $regpwd2 = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regpwd2"]))));
                    }else{
                        $regfail["pwd2"]=1;
                        $regerror="Wprowadź drugie hasło <br>";
                        $nofail=0;
                    }
                    if(!empty($_POST["regrang"])){
                        $regrang = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regrang"]))));
                    }else{
                        $regfail["rang"]=1;
                        $regerror="Wprowadź range <br>";
                        $nofail=0;
                    }

                    if(!$regfail["pwd"]){
                        if($regpwd!=$regpwd2){
                            $regfail["pwd2"]=1;
                            $regerror="Hasła się nie zgadzają<br>";
                            $nofail=0;
                        }
                    }

                    if($nofail){
                        $regpwd = md5($regpwd);
                        $regpwd2 = md5($regpwd2);

                        $db_query = mysqli_query($con, "INSERT INTO `users` (`user_id`, `login`, `email`, `password`, `ranga_id`) VALUES (NULL, '$reglogin', '$regemail', '$regpwd', '$regrang');");
                    }
                }
                ?>
    <div class="centercv">
        <form class="form-horizontal" action="index.php?goto=register" method="post">
            <div class='form-group <?php if($regfail["login"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='reglogin'>Login:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' id="inputError" name='reglogin' placeholder='Login'>
                    <?php if($regfail["login"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["email"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regemail'>Email:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' name='regemail' placeholder='Email'>
                    <?php if($regfail["email"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["pwd"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regpwd'>Hasło:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' name='regpwd' placeholder='Hasło'>
                    <?php if($regfail["pwd"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["pwd2"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regpwd2'>Powtórz hasło:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' name='regpwd2' placeholder='Powtórz hasło'>
                    <?php if($regfail["pwd2"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["rang"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regrang'>Grupa:</label>
                <div class='col-sm-9'>
                    <input type='text' class='form-control' name='regrang' placeholder='Grupa'>
                    <?php if($regfail["rang"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 sumb">
                    <input type='hidden' name='register' value="1">
                    <button type="submit" class="btn btn-default">Prześlij</button>
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
                $db_query = mysqli_query($con, "SELECT users.login, users.email, groups.nazwa, users.user_id FROM users, groups WHERE users.ranga_id=groups.id_rangi;");
                while($db_row = mysqli_fetch_assoc($db_query)){
                    echo "<tr><td>".$db_row['user_id']."</td><td>".$db_row['login']."</td><td>".$db_row['email']."</td><td>".$db_row['nazwa']."</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
            }
        }
        ?>

    </div>
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
