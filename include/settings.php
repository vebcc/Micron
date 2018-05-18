<?php
//require( ABSPATH . WPINC . '/class-walker.php' );

define('_lang_', $db_settings["lang"]);
define('_charset_', $db_settings["charset"]);
define('_description_', $db_settings["description"]);
define('_author_', $db_settings["author"]);
define('_title_', $db_settings["title"]);
define('_theme_', $db_settings["theme"]);
define('_banner_', $db_settings["banner"]);

define('_theme_url', 'content/themes/'._theme_.'/');


?>
