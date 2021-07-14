		<!-- FOOTER -->
		<footer>
			<?php 
				$footer = get_field( "footer","option") ;
			?>
			<div class="container">
				<div class="footer__desc">
					<div class="footer__desc__logo">

					</div>
					<p>
					<?php echo $footer['footer_des']?>
					</p>
				</div>

				<div class="footer__copyright">
					<span><?php echo $footer['footer_copyright']?></span>
				</div>
			</div>
			<?php wp_footer(); ?>
		</footer>
        <!-- END FOOTER -->

        <button id="scrollToTop">
            <i class="fas fa-arrow-up"></i>
        </button>

        <!-- SCRIPT -->
        <script src="<?php bloginfo('template_url'); ?>/assets/js/jquery-3.5.1.min.js"></script>
        <script src="<?php bloginfo('template_url'); ?>/assets/js/plugins.min.js"></script>
        <script src="<?php bloginfo('template_url'); ?>/assets/js/main.js"></script>
    </body>
</html>
