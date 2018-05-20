<?php

function mic($name, $admin=0){
    global $db_settings;
    switch($name){
        case "theme_url":
            if($admin==0){
                echo 'content/themes/'.$db_settings["theme"].'/';
            }else{
                return 'content/themes/'.$db_settings["theme"].'/';
            }
            break;
        default:
            if($admin==0){
                echo $db_settings[$name];
            }else{
                return $db_settings[$name];
            }
            break;
    }
}

function mic_head(){
    echo "<meta charset='".mic('charset',1)."'>
        <meta name='description' content='".mic('description',1)."'>
        <meta name='author' content='".mic('author',1)."'>
        <title>".mic('title',1)."</title>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";

        // Wstawianie deklaracji wszystkich styli
        //$u2=mysqli_query($con,$stylesheet);
        //while($t2 = mysqli_fetch_row($u2)){
        //echo "<link href='".$t2[0]."' rel='stylesheet'>";
}

function mic_banner($class){
    echo "<img src='content/images/".mic('banner',1)."' alt='".mic('title',1)."' class='$class'>";
}

function mic_main_menu(){
    global $db_main_menu;
    for($i=0;$i<count($db_main_menu);$i++){
        echo"<ul>
                <li><a href='". mic('adress',1) . $db_main_menu[$i][2]."'>".$db_main_menu[$i][0]."</a></li>
             </ul>";

    }
}

function mic_menu($name){
    global $db_menu;
    for($i=0;$i<count($db_menu[$name]);$i++){
        echo"<ul>
                <li><a href='". mic('adress',1) . $db_menu[$name][$i][2]."'>".$db_menu[$name][$i][0]."</a></li>
             </ul>";

    }
}

?>

