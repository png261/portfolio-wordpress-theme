<?php

function rc_static_html_task_events_activate() {
    if (! wp_next_scheduled ( 'wpptsh_daily_schedules' )) {
        wp_schedule_event( time(), 'daily', 'wpptsh_daily_schedules');
    }
}


add_action( 'wpptsh_daily_schedules', 'wpptsh_active_cron_job_after_five_second', 10, 2 );
function wpptsh_active_cron_job_after_five_second() {
	$home_url = get_home_url();
	$notices = file_get_contents('http://api.myrecorp.com/wpptsh_notices.php?version=free&url='.$home_url);

    update_option('wpptsh_notices', $notices);
}

function rc_static_html_task_events_deactivate() {
    wp_clear_scheduled_hook( 'wpptsh_daily_schedules' );
}

function wpptsh_right_side_notice(){
	$notices = get_option('wpptsh_notices');
	$notices = json_decode($notices);
	$html = "";

	if (!empty($notices)) {
		foreach ($notices as $key => $notice) {
			$title = $notice->title;
			$key = $notice->key;
			$publishing_date = $notice->publishing_date;
			$auto_hide = $notice->auto_hide;
			$auto_hide_date = $notice->auto_hide_date;
			$is_right_sidebar = $notice->is_right_sidebar;
			$content = $notice->content;
			$status = $notice->status;
			$version = isset($notice->version) ? $notice->version : array();
			$styles = isset($notice->styles) ? $notice->styles : "";

			$current_time = time();
			$publish_time = strtotime($publishing_date);
			$auto_hide_time = strtotime($auto_hide_date);

			if ( $status && $is_right_sidebar == 1 && $current_time > $publish_time && $current_time < $auto_hide_time && in_array('free', $version) ) {
				$html .= '<div class="sidebar_notice_section">';
				$html .=	'<div class="right_notice_title">'.$title.'</div>';
				$html .=	'<div class="right_notice_details">'.$content.'</div>';
				$html .= '</div>';

				if ( !empty($styles) ) {
					$html .= '<style>' . $styles . '</style>';
				}
			}
		}
	}


	echo $html;
}
add_action("wpptsh_right_side_notice", "wpptsh_right_side_notice");

function wpptsh_admin_notices(){


	$notices = get_option('wpptsh_notices');
	$notices = json_decode($notices);
	$html = "";

	
	if (!empty($notices)) {
		foreach ($notices as $key2 => $notice) {
			$title = isset($notice->title) ? $notice->title : "";
			$key = isset($notice->key) ? $notice->key : "";
			$publishing_date = isset($notice->publishing_date) ? $notice->publishing_date : time();
			$auto_hide = isset($notice->auto_hide) ? $notice->auto_hide : false;
			$auto_hide_date = isset($notice->auto_hide_date) ? $notice->auto_hide_date : time();
			$is_right_sidebar = isset($notice->is_right_sidebar) ? $notice->is_right_sidebar : true;
			$content = isset($notice->content) ? $notice->content : "";
			$status = isset($notice->status) ? $notice->status : false;
			$alert_type = isset($notice->alert_type) ? $notice->alert_type : "success";
			$version = isset($notice->version) ? $notice->version : array();
			$styles = isset($notice->styles) ? $notice->styles : "";

			$current_time = time();
			$publish_time = strtotime($publishing_date);
			$auto_hide_time = strtotime($auto_hide_date);

			$clicked_data = (array) get_option('wpptsh_notices_clicked_data');

			if ( $status && !$is_right_sidebar && $current_time > $publish_time && $current_time < $auto_hide_time && !in_array($key, $clicked_data) && in_array('free', $version) ) {
				$html .=  '<div class="notice notice-'. $alert_type .' is-dismissible dcim-alert wpptsh" wpptsh_notice_key="'.$key.'">
						'.$content.'
					</div>';

				if ( !empty($styles) ) {
					$html .= '<style>' . $styles . '</style>';
				}
			}
		}
	}

	echo $html;

}
add_action('admin_notices', 'wpptsh_admin_notices');


			
add_action('wp_ajax_wpptsh_notice_has_clicked', 'wpptsh_notice_has_clicked');
add_action('wp_ajax_nopriv_wpptsh_notice_has_clicked', 'wpptsh_notice_has_clicked');

function wpptsh_notice_has_clicked(){
	//$post = $_POST['post'];
	$wpptsh_notice_key = isset($_POST['wpptsh_notice_key']) ? $_POST['wpptsh_notice_key'] : "";
	$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";

	if(!empty($nonce)){
		if(!wp_verify_nonce( $nonce, "recorp_different_menu" )){
			echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

			die();
		}
	}

	$clicked_notices = get_option('wpptsh_notices_clicked_data');
	if (!empty($clicked_notices)) {
		$clicked_notices[] = $wpptsh_notice_key;
	} else {
		$clicked_notices[0] = $wpptsh_notice_key;
	}

	update_option('wpptsh_notices_clicked_data', $clicked_notices);
	$response = "";

	
	echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));

	die();
}

function rc_wpptsh_notice_dissmiss_scripts(){
	?>
	<script>
		jQuery(document).on("click", ".wpptsh .notice-dismiss", function(){
			if (jQuery(this).parent().attr('wpptsh_notice_key').length) {
				 var datas = {
				  'action': 'wpptsh_notice_has_clicked',
				  'rc_nonce': '<?php echo wp_create_nonce( "recorp_different_menu" ); ?>',
				  'wpptsh_notice_key': jQuery(this).parent().attr('wpptsh_notice_key'),
				};
				
				jQuery.ajax({
				    url: '<?php echo admin_url('admin-ajax.php'); ?>',
				    data: datas,
				    type: 'post',
				    dataType: 'json',
				
				    beforeSend: function(){
				
				    },
				    success: function(r){
				      if(r.success == 'true'){
				        console.log(r.response);
				
				        
				        } else {
				          alert('Something went wrong, please try again!');
				        }
				    	
				    }, error: function(){
				    	
				  }
				});
			}
		});
	</script>
	<?php
}
add_action("admin_footer", "rc_wpptsh_notice_dissmiss_scripts");
