<!--@Dodaj przedmiot@10@-->
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
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($actual_ip)){
        $login = $_SESSION['login'];
?>

<div id="additem">
<h1>Dodaj Przedmiot</h1>
        <?php
        if(checkpermission("section", "addpost")){
            if(checkpermission("addpost")){

                if(isset($_POST["title"]) && !empty($_POST["addposttext"]) && !empty($_POST["date"])){
                    
                }
                    
            }
        }
        ?>
        <div class="centercv">
            <form class="form-horizontal" action="index.php?goto=addpost" method="post">
                <div class='form-group'>
                    <label class='control-label col-sm-3'>Tytuł</label>
                    <div class='col-sm-9'>
                        <input type='text' class='form-control' name='title' placeholder=''>
                    </div>
                    <label class='control-label col-sm-3'>Tekst</label>
                    <div class='col-sm-9'>
                        <input type='text' class='form-control' id='addposttext' name='addposttext' placeholder=''>
                    </div>
                    <label class='control-label col-sm-3'>Data publikacji</label>
                    <div class='col-sm-9'>
                        <input type='datetime-local' id='addposttime' class='form-control' name='date' value='2017-06-01T08:30'>
                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 sumb">
                        <button type="submit" class="btn btn-default">Dodaj post</button>
                    </div>
                </div>
            </form>
        </div>
    <?php


    ?>
</div>

<script type="text/javascript">
       Number.prototype.AddZero= function(b,c){
        var  l= (String(b|| 10).length - String(this).length)+1;
        return l> 0? new Array(l).join(c|| '0')+this : this;
     }//to add zero to less than 10,


       var d = new Date(),
       localDateTime= [d.getFullYear(),
                d.getDate().AddZero(),
                (d.getMonth()+1).AddZero()].join('-') +'T' +
               [d.getHours().AddZero(),
                d.getMinutes().AddZero()].join(':');
       var elem=document.getElementById('addposttime'); 
       //console.log(localDateTime);
       elem.value = localDateTime;
       //console.log(elem.value);
     </script>

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
Dodaj przedmiot
