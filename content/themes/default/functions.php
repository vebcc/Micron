<?php
    //echo " it works";
function get_header(){
    require( mic('theme_url',1).'header.php' );
}

function get_main(){
    require( mic('theme_url',1).'main.php' );

}
function get_sidebar(){
    require( mic('theme_url',1).'sidebar.php' );
}

function get_footer(){
    require( mic('theme_url',1).'footer.php' );
}

function get_page(){
    require( mic('theme_url',1).'page.php' );
}

function get_content(){
    require( mic('theme_url',1).'content.php' );
}

?>
