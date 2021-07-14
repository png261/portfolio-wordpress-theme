<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/admin/partials
 */


$args = array(
    'post_type' => 'page',
);

$query = new WP_Query( $args );

?>


    <div class="page-wrapper p-b-100 font-poppins">
        <div class="wrapper">
            <div class="card card-4">
                <div class="card-body">
                    <h2 class="title"><?php _e('Export WP Pages to Static HTML/CSS', 'export-wp-page-to-static-html'); ?><span class="badge badge-dark version">v<?php echo EXPORT_WP_PAGE_TO_STATIC_HTML_VERSION; ?></span></h2>

                    <div class="row">
                        <div class="col-7">
                            <form method="POST" class="">
                                <div class="input-group">
                                    <label class="label" for="export_pages"><?php _e('Select a page', 'export-wp-page-to-static-html'); ?></label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select id="export_pages" name="export_pages">
                                            <option disabled="disabled" selected=""><?php _e('Choose page', 'export-wp-page-to-static-html'); ?></option>

                                            <?php 

                                                if (!empty($query->posts)) {
                                                    foreach ($query->posts as $key => $post) {
                                                        $post_id = $post->ID; 
                                                        $post_title = $post->post_title; 
                                                    ?>
                                                        <option value="<?php echo $post_id; ?>"><?php echo $post_title; ?></option>
                                                    <?php
                                                    }
                                                }
                                             ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>


                                <div class="col-8">
                                    <div class="input-group">
                                        <label class="label"><?php _e('Advanced settings', 'export-wp-page-to-static-html'); ?></label>
                                        <div class="p-t-10">
                                            <label class="checkbox-container m-r-45"><?php _e('Replace all url to #', 'export-wp-page-to-static-html'); ?>
                                                <input type="checkbox" id="replace_all_url" name="replace_all_url">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-t-15">
                                    <button class="btn btn--radius-2 btn--blue export-btn" type="submit"><?php _e('Export HTML', 'export-wp-page-to-static-html'); ?> <span class="spinner_x hide_spin"></span></button>

                                    <a href="" class="btn btn--radius-2 btn--green download-btn hide" type="submit"><?php _e('Download the file', 'export-wp-page-to-static-html'); ?></a>
                                </div>
                            </form>  

                            <div class="logs p-t-15 col-10">
                                <h4 class="p-t-15"><?php _e('Export log', 'export-wp-page-to-static-html'); ?></h4>
                                <div class="logs_list">
                                </div>
                            </div>   
                        </div>
                        <div class="col-3 p-10 dev_section" >
                            
                          <div class="created_by py-2 mt-1 border-bottom"> <?php _e('Created by', 'export-wp-page-to-static-html'); ?> <a href="https://myrecorp.com"><img src="<?php echo home_url() . '/wp-content/plugins/export-wp-page-to-static-html/admin/images/recorp-logo.png'; ?>" alt="ReCorp" width="100"></a></div>


                          <div class="documentation my-2">
                              <span><?php _e('See the documentation', 'export-wp-page-to-static-html'); ?> </span> <a href="https://myrecorp.com/documentation/export-wp-page-to-html"><?php _e('here', 'export-wp-page-to-static-html'); ?></a>
                          </div>  
                        <div class="support my-2">
                              <span><?php _e('Need support ? Then do not waste your time. Just', 'export-wp-page-to-static-html'); ?> </span> <a href="https://myrecorp.com/support"><?php _e('click here', 'export-wp-page-to-static-html'); ?></a>
                        </div> 

                
                            <div class="right_side_notice mt-4">
                                <?php echo do_action('wpptsh_right_side_notice'); ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- This templates was made by Colorlib (https://colorlib.com) -->
