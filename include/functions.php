<?php

function mic_head(){
    echo "<meta charset='"._charset_."'>
        <meta name='description' content='"._description_."'>
        <meta name='author' content='"._author_."'>
        <title>"._title_."</title>";

}

function mic_banner($class){
    echo "<img src='content/images/"._banner_."' alt='"._title_."' class='$class'>";
}
    // Wstawianie deklaracji wszystkich styli

    //$u2=mysqli_query($con,$stylesheet);

    //while($t2 = mysqli_fetch_row($u2)){
        //echo "<link href='".$t2[0]."' rel='stylesheet'>";
?>

