<?php
    // header
?>
<!DOCTYPE html>
<html lang="<?php _lang_; ?>" >
    <head>
        <?php mic_head(); ?>
        <link rel="stylesheet" id="main-css" href="<?php _theme_url ?>/css/main.css" type="text/css">
    </head>

    <body>
        <div id="page" class="hfeed site">
            <a class="skip-link screen-reader-text" href="#content">coś</a>

            <div id="sidebar" class="sidebar">
                <header id="masthead" class="site-header" role="banner">
                    <div class="site-branding">

                        <?php mic_banner("banner"); ?>

                    </div><!-- .site-branding -->
                </header><!-- .site-header -->

                <?php //get_sidebar(); ?>
            </div><!-- .sidebar -->

            <div id="content" class="site-content">
