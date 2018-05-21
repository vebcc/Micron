<?php
 //echo "login works"
?>
<link rel="stylesheet" href="css/login.css" type="text/css">
</head>
<body>


<div id="content">
    <h1><a href="http://maslowski.it/" title="Oparte na Micronie" tabindex="-1"><img src="images/micron.png" alt="Oparte na Micronie"></a></h1>

    <form name="loginform" id="loginform" action="index.php" method="post">
        <div class="form-group">
            <label for="logem">Nazwa użytkownika lub e-mail</label>
            <input type="login" class="form-control" name="logem">
        </div>
        <div class="form-group">
            <label for="pwd">Hasło</label>
            <input type="password" class="form-control" name="pwd">
        </div>
        <div class="row">
            <div class="col-md-7 checkbox">
                <label><input type="checkbox" name="rememberme"> Zapamiętaj mnie</label>
            </div>
            <div class="col-md-5 button">
                <button type="submit" class="btn btn-primary">Zaloguj się</button>
            </div>
        </div>
        <input type="hidden" name="cookie" value="1">
    </form>



    <p id="backtoblog"><a href="http://94.177.231.204/maslo/micron/">← Powrót do Strony Głównej</a></p>

</div>
