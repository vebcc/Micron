<?php
    //echo " it works";
function get_header(){
    require( _theme_url.'header.php' );
}

function get_main(){
    require( _theme_url.'main.php' );

}

function get_sidebar(){
    require( _theme_url.'sidebar.php' );
}

function get_footer(){
    require( _theme_url.'footer.php' );
}

?>
