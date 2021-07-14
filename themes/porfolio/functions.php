<?php 
function wpse121723_register_sidebars() {
    register_sidebar( array(
        'name' => 'Right Sidebar',
        'id' => 'right_sidebar',
        'before_widget' => '',
        'after_widget' => '',
    ) );
}
add_action( 'widgets_init', 'wpse121723_register_sidebars' );
