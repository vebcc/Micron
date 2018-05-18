<?php


// Get db_settings

$db_query = mysqli_query($con,"SELECT name, pl_value FROM settings;");

while($db_row = mysqli_fetch_assoc($db_query)){
    //echo $db_row["name"] . "<br>";
    $db_settings[$db_row["name"]] = $db_row["pl_value"];
}
$db_query->free();

// Get main menu

$db_query = mysqli_query($con,"SELECT menu_list.name, menu_list.tag, menu_list.value FROM menu_list, menu WHERE menu_list.id_menu=menu.id AND menu.main_menu=1 ORDER BY menu_list.ord;");

$i=0;
while($db_row = mysqli_fetch_assoc($db_query)){
    //echo $db_row["name"] . "<br>";
    $db_menu[$i] = array($db_row["name"], $db_row["tag"], $db_row["value"]);
    $i++;
}
$db_query->free();


?>
