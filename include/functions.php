<?php

function mic_head(){
    echo "<meta charset='"._charset_."'>
        <meta name='description' content='"._description_."'>
        <meta name='author' content='"._author_."'>
        <title>"._title_."</title>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";

        // Wstawianie deklaracji wszystkich styli
        //$u2=mysqli_query($con,$stylesheet);
        //while($t2 = mysqli_fetch_row($u2)){
        //echo "<link href='".$t2[0]."' rel='stylesheet'>";
}

function mic_banner($class){
    echo "<img src='content/images/"._banner_."' alt='"._title_."' class='$class'>";
}

//FIXME: tablica $db_row  nie jest wykrywana w functions.php  w mic_menu() a poza jest.

function mic_menu(){
    for($i=0;$i<count($db_menu);$i++){
        echo"<ul>
                <li><a href='".$db_menu[$i][2]."'>".$db_menu[$i][0]."</a></li>
             </ul>";

    }
}
?>

