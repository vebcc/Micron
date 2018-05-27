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

                if(isset($_GET["deluser"]) && !empty($_GET["deluser"])){
                    $deluser = htmlspecialchars(stripslashes(strip_tags(trim($_GET["deluser"]))));
                    $db_query = mysqli_query($con, "DELETE FROM users WHERE users.user_id=$deluser");
                    //TODO: wyniki success dla np dobrze dodanego usera lub usuniecie poprawne
                }

                if(isset($_GET["edituser"])){
                    echo " Aktualnie nic";
                    //TODO: Panel edycji usera;
                }

                $regerror=" ";
                $regfail= array("login"=>0,"email"=>0,"pwd"=>0,"pwd2"=>0,"rang"=>0, "pwd2no"=>0, "loginex"=>0, "emailex"=>0);
                $nofail = 1;
                if(isset($_POST["register"])){

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
                    if(!empty($_POST["regrang"])){
                        $regrang = htmlspecialchars(stripslashes(strip_tags(trim($_POST["regrang"]))));
                    }else{
                        $regfail["rang"]=1;
                        $nofail=0;
                    }

                    if(!$regfail["pwd"]){
                        if($regpwd!=$regpwd2){
                            $regfail["pwd2no"]=1;
                            $nofail=0;
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
                        $regpwd2 = md5($regpwd2);

                        $db_query = mysqli_query($con, "INSERT INTO `users` (`user_id`, `login`, `email`, `password`, `ranga_id`) VALUES (NULL, '$reglogin', '$regemail', '$regpwd', '$regrang');");
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
                    if($regfail["loginex"] && !empty($reglogin)){
                        echo "<p>Login jest już zajęty</p>";
                    }
                    if($regfail["email"]){
                        echo "<p>Wprowadź email</p>";
                    }
                    if($regfail["emailex"] && !empty($regemail)){
                        echo "<p>Email jest już zajęty</p>";
                    }
                    if($regfail["pwd"]){
                        echo "<p>Wprowadź hasło</p>";
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
            ?>

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
                    <input type='password' class='form-control' name='regpwd' placeholder='Hasło'>
                    <?php if($regfail["pwd"]){echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";} ?>
                </div>
            </div>
            <div class='form-group <?php if($regfail["pwd2"] || $regfail["pwd2no"]){echo "has-error";} ?> has-feedback'>
                <label class='control-label col-sm-3' for='regpwd2'>Powtórz hasło:</label>
                <div class='col-sm-9'>
                    <input type='password' class='form-control' name='regpwd2' placeholder='Powtórz hasło'>
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
                                echo "<option value='".$db_row['id_rangi']."'>".$db_row['nazwa']."</option>";
                            }
                        ?>
                    </select>
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
                    echo "<tr><td>".$db_row['user_id']."</td><td>".$db_row['login']."</td><td>".$db_row['email']."</td><td>".$db_row['nazwa']."</td>
                    <td><a href='index.php?goto=register&deluser=".$db_row['user_id']."' class='reglink'><span class='glyphicon glyphicon-remove'></span></a></td>
                    <td><a href='index.php?goto=register&edituser=".$db_row['user_id']."' class='reglink'><span class='glyphicon glyphicon-edit'></span></a></td>
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
