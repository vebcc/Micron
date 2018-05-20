<?php
    // header
?>
<!DOCTYPE html>
<html lang="<?php _lang_; ?>" >
    <head>
        <?php mic_head(); ?>
        <link rel="stylesheet" href="<?php echo _theme_url ?>bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
        <link rel="stylesheet" id="main-css" href="<?php echo _theme_url ?>css/main.css" type="text/css">
    </head>

    <body>
        <div id="page" class="hfeed site">

            <div id="sidebar" class="sidebar">
                <header id="masthead" class="site-header" role="banner">
                    <div class="site-branding">
                        <a class="link-banner" href="#">
                            <?php mic_banner("banner"); ?>
                        </a>

                    </div><!-- .site-branding -->
                </header><!-- .site-header -->
                <div id="menu"></div>
                    <?php //mic_menu() ?>
                </div>
            </div><!-- .sidebar -->

            <div id="content" class="site-content">
