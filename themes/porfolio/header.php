<!DOCTYPE html>
<html class="no-js">
    <head>
        <title>WP2STATIC</title>
        <link rel="icon" href="<?php bloginfo('template_url'); ?>/assets/images/favicon.png" type="image/gif" sizes="16x16">
        <meta charset="UTF-8">
         <!-- GOOGLE-FONT -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- STYLESHEET -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/plugins.min.css">
        <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/main.css">
		<?php wp_head(); ?>
    </head>
    <body>
        
        <!-- HEADER -->
        <header>
            <nav>
                <a class="navbar-brand" href="#">
                    <?php 
                        $logo = get_field( "logo","option");
                    ?>
                    <img src="<?php echo $logo ?>" alt="">
                </a>

                <button class="navbar-toggler">
                    <span class="navbar-toggler-text">Menu</span>

                    <div class="navbar-toggler-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button><!-- .navbar-toggler -->
            </nav>
            
            <div id="sidebar">
                <div class="sidebar">
                    <div class="sidebar__heading">
                        <span class="subtitle">
                            NAVIGATION
                        </span>
                        <button class="sidebar__close navbar-toggler">
                        </button>
                    </div><!-- .sidebar__heading -->
                    <?php dynamic_sidebar('right_sidebar') ?>
                </div>
                <div id="overlay"></div>
            </div><!-- #sidebar -->
        </header>
        <!-- END HEADER -->