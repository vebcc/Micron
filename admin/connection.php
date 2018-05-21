<?php
if(isset($db)){
    $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($con->connect_error) {
        die("Blad polaczenia: " . $con->connect_error);
    }else{
        mysqli_set_charset( $con, 'utf8');
    }
}
?>
