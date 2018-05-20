<?php


// Get db_settings

$db_query = mysqli_query($con,"SELECT name, pl_value FROM settings;");

while($db_row = mysqli_fetch_assoc($db_query)){
    $db_settings[$db_row["name"]] = $db_row["pl_value"];
}
$db_query->free();

// Get main menu

$db_query = mysqli_query($con,"SELECT menu_list.name, menu_list.tag, menu_list.value FROM menu_list, menu WHERE menu_list.id_menu=menu.id AND menu.main_menu=1 ORDER BY menu_list.ord;");

$i=0;
while($db_row = mysqli_fetch_assoc($db_query)){
    $db_main_menu[$i] = array($db_row["name"], $db_row["tag"], $db_row["value"]);
    $i++;
}
$db_query->free();

// Get all menu

$db_query = mysqli_query($con,"SELECT menu.name AS menu_name, menu_list.name, menu_list.tag, menu_list.value FROM menu_list, menu WHERE menu_list.id_menu=menu.id ORDER BY menu_list.ord;");

$i=0;
$temp="";
while($db_row = mysqli_fetch_assoc($db_query)){
    //echo $db_row["name"] . "<br>";
    if($temp!=$db_row["menu_name"]){
        $i=0;
        $temp = $db_row["menu_name"];
    }
    $db_menu[$temp][$i] = array($db_row["name"], $db_row["tag"], $db_row["value"]);
    $i++;
}
$db_query->free();


?>
