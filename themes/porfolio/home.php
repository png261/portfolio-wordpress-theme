<?php

/**
 * Template Name: Home Page
 */

get_header(); 

?>
    <div id="loading">
        <div class="loading-progress">
            
        </div>
        <div class="line-scale-pulse-out">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <span>PNG</span>
        </div>
    </div
    
    <!-- HOME -->
    <section id="home">
        <?php 
            $home = get_field("home_section");
        ?>
        <div class="home__welcome" data-aos="fade-up" data-aos-duration="1000">
            <div class="container">
                <span class="home__welcome__sub-title">
                    <?php echo $home['sub_title'] ?>
                </span>

                <h2 class="home__welcome__slogan">
                    <?php echo $home['title'] ?>
                </h2>

                <div class="home__welcome__actions">
                    <a href="#contact">Start a Project</a>
                    <a href="#about">More About Me</a>
                </div>
            </div>
        </div><!-- .home__welcome -->

        <div class="home__socials" data-aos="fade-up" data-aos-duration="1000">
            <?php echo wp_nav_menu( array('menu'=> 'Socials') ); ?>
        </div><!-- .home__socials -->

        <a class="home__scroll-down" href="#about">
            <i class="fas fa-chevron-down"></i>
            <span>SCROLL DOWN</span>
        </a><!-- .scroll-down -->

    </section>
    <!-- END HOME -->

    <!-- ABOUT -->
    <section id="about">
        <?php 
            $about = get_field("about_section");
        ?>
        <div class="container">
            <div class="heading">
                <span class="subtitle text-dark">
                    <?php echo $about['sub_title'] ?>
                </span>
                <h1 class="text-white">
                    <?php echo $about['title'] ?>
                </h1>
            </div><!-- .heading -->
            
            <p class="about__des" data-aos="fade-up" data-aos-duration="500">
                 <?php echo $about['des'] ?>
            </p><!-- .about__des -->

            <ul class="about__stats">
                <?php foreach($about['stats'] as $stat){?>
                    <li class="about__stats__stat"  data-aos="fade-up" data-aos-duration="500">
                        <span class="about__stats__stat__number"><?php echo $stat['num'] ?></span>
                        <span class="about__stats__stat__title"><?php echo $stat['name_stat'] ?></span>
                    </li>
                <?php } ?>
            </ul><!-- .about__stats -->
        </div>
    </section>
    <!-- END ABOUT -->
    
    <!-- SERVICES -->
    <section id="services">
        <?php 
            $services = get_field("services_section");
        ?>
        <div class="container">
            <div class="heading">
                <span class="subtitle">
                    <?php echo $services['sub_title'] ?>
                </span>
                <h1><?php echo $services['title'] ?></h1>
            </div><!-- .heading -->

            <ul class="services__list"  data-aos="fade-up" data-aos-duration="500">
                <?php foreach( $services['skills'] as $skill ) { ?>
                    <li class="services__list__service">
                        <i class="fab fa-youtube services__list__service__icon"></i>

                        <div class="services__list__service__des">
                            <h4><?php echo $skill['name'] ?></h4>
                            <p><?php echo $skill['des'] ?></p>
                        </div>
                    </li>
                <?php } ?>
            </ul><!-- .services__list -->
        </div>
    </section>
    <!-- END SERVICES -->
            
    <!-- WORKS -->
    <section id="works">
        <?php 
            $works = get_field("works_section");
        ?>
        <div class="container">
            <div class="heading">
                <span class="subtitle">
                    <?php echo $works['sub_title'] ?>
                </span>
                <h1 class="text-white">
                    <?php echo $works['title'] ?>
                </h1>
            </div><!-- .heading -->

            <div class="works__list"  data-aos="fade-up" data-aos-duration="500">
                <?php foreach( $works['projects'] as $project ) { ?>
                    <a href="" class="works__list__project">
                    <div class="works__list__project__thumbnail">
                        <img src="<?php echo $project['thumbnail'] ?>" alt="">
                    </div>

                    <div class="works__list__project__info">
                        <span class="works__list__project__info__title"><?php echo $project['name'] ?></span>
                        <span class="works__list__project__info__cat"><?php echo $project['type'] ?></span>
                    </div>
                </a>
                <?php } ?>
            </div><!-- .works__list -->
        </div>
    </section>
    <!-- END WORKS -->
    
    <!-- CLIENTS -->
    <section id="clients">
        <?php 
            $clients = get_field("clients_section");
        ?>
        <div class="container">
            <div class="heading">
                <span class="subtitle">
                    <?php echo $clients['sub_title'] ?>
                </span>
                <h1><?php echo $clients['title'] ?></h1>
            </div><!-- .heading -->

            <div class="clients__testimonials" data-aos="fade-up" data-aos-duration="500">

                <?php foreach( $clients['comments'] as $comment ) { ?>
                    <div class="clients__testimonials__testimonial">
                        <blockquote>
                            <?php echo $comment['comment'] ?>
                        </blockquote>

                        <div class="clients__testimonials__testimonial__author">
                            <div class="clients__testimonials__testimonial__author__avt">
                                <img src="<?php echo $comment['author']['avt']?>" alt="">
                            </div>
                            <div class="clients__testimonials__testimonial__author__name">
                                <?php echo $comment['author']['name'] ?>
                            </div>
                            <div class="clients__testimonials__testimonial__author__pos">
                                <?php echo $comment['author']['position'] ?>
                            </div>
                        </div>
                    </div><!-- .clients__testimonials__testimonial -->
                <?php } ?>
               
            </div><!-- .clients__testimonials -->
        </div>
    </section>
    <!-- END CLIENTS -->
    
    <!-- CONTACT -->
    <section id="contact">
        <?php 
            $contact = get_field("contact_section");
        ?>
        <div class="container">
            <div class="heading">
                <span class="subtitle">
                    <?php echo $contact['sub_title'] ?>
                </span>
                <h1 class="text-white"><?php echo $contact['title'] ?></h1>
            </div><!-- .heading -->

            <div class="contact__content" data-aos="fade-up" data-aos-duration="500">
                <div class="contact__content__form">
                    <h4 class="contact__content__subheading">
                        SEND ME A MESSAGE
                    </h4>
                    <?php echo do_shortcode('[contact-form-7 id="93" title="Contact"]') ?>
                </div><!-- .contact__content_form -->
               

                <div class="contact__content__infos">
                    <h4 class="contact__content__subheading">
                        CONTACT INFO
                    </h4>
                    
                    <?php foreach( $contact['infos'] as $info ) { ?>
                        <div class="contact__content__infos__info">
                            <h5><?php echo $info['title'] ?></h5>

                            <?php 
                                foreach($info['info_list'] as $item){ 
                                    echo '<span>'.$item['text'].'</span>';
                                } 
                            ?>

                        </div>
                    <?php } ?>

                    <?php echo wp_nav_menu( array('menu'=> 'Socials') ); ?>
                </div><!-- .contact__content_infos -->
            </div><!-- .contact__content -->
        </div>
    </section>
    <!-- END CONTACT -->
    
    <?php get_footer(); ?>
