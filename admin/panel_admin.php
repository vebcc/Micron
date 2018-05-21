<?php
if(isset($_SESSION['token']) && isset($_SESSION['login']) && isset($_SESSION['token2'])){
    if($_SESSION['token']==md5($_SERVER['HTTP_USER_AGENT']) && $_SESSION['token2']==md5($_SERVER['REMOTE_ADDR'])){
        $login = $_SESSION['login'];
        echo "Siemka zalogowany $login!";

?>

<link rel="stylesheet" href="css/panel_admin.css" type="text/css">
</head>
<body>

    <div class="container">
        <div class="jumbotron">
            <h1>Micron</h1>
            <p>Witaj w panelu administracyjnym twojej strony.</p>
        </div>

        <div id="content">

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
    header('Location: index.php');
}
?>
