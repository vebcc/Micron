<?php
// themes function

// echo
mic("lang"); // np pl
mic("charset"); // np utf8
mic("description"); // np my CV
mic("author"); // author name
mic("title"); // title
mic("theme"); // theme name np default
mic("banner"); // banner file np banner.png
mic("icon"); // icon file np icon.ico
mic("adress"); // adress to www np /maslo/micron
mic("domain"); // domain np maselko.ovh
mic("theme_url"); // theme url np content/themes/default
// return dla developerow
mic("title",1);

mic_main_menu(); // ul li list with main menu

mic_menu("menu_name"); // ul li list with menu

mic_head(); // get title, meta tag etc;

mic_banner("banner"); // get banner with <a href="mainpage"> <img> and <img> class "banner"
?>
