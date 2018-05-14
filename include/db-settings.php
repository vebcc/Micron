<?php

$db_query = mysqli_query($con,"SELECT name, pl_value FROM settings;");

while($db_row = mysqli_fetch_assoc($db_query)){
    //echo $db_row["name"] . "<br>";
    $db_settings[$db_row["name"]] = $db_row["pl_value"];
}

?>
