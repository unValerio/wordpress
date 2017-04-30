<?php

$profile_page = get_option('booked_profile_page');
if ($profile_page):

	if(!class_exists('booked_profiles')) {
		class booked_profiles {
		
			public function __construct() {
				add_action('init', array(&$this,'rewrite_add_rewrites'));
				add_action('the_content', array(&$this,'display_profile_markup'));
				add_filter('wp_title', array(&$this,'wp_profile_title'),10,2);
				register_activation_hook( __FILE__, array(&$this, 'rewrite_activation') );
			}
			
			public function rewrite_add_rewrites(){
			
				$profile_page = get_option('booked_profile_page');
				
				if ($profile_page):
					$profile_page_data = get_post($profile_page, ARRAY_A);
					$profile_slug = $profile_page_data['post_name'];
				else :
					$profile_slug = 'profile';
				endif;
				
			    add_rewrite_tag( '%profile%', '([^&]+)' );
			    add_rewrite_rule(
			        '^'.$profile_slug.'/([^/]*)/?',
			        'index.php?profile=$matches[1]',
			        'top'
			    );
			}
			
			public function rewrite_activation(){
			    $this->rewrite_add_rewrites();
			    flush_rewrite_rules();
			}
			
			public function display_profile_markup($content){
				$profile_page = get_option('booked_profile_page');
				if(is_page($profile_page) || get_query_var('profile')):
					if (is_user_logged_in() || get_query_var('profile')):
						ob_start();
						$this->display_profile_page_content();
						$content = ob_get_clean();
						return $content;
					else :
						return $content;
					endif;
				endif;
				return $content;
			}
					
			public function display_profile_page_content() {
				require(BOOKED_PLUGIN_TEMPLATES_DIR . 'profile.php');
			}
			
			public function wp_profile_title( $title, $sep = false ) {
				if (get_query_var('profile')):
					echo get_query_var('profile');
					$user_data = get_user_by( 'id', get_query_var('profile') );
					$title = sprintf(__("%s's Profile","booked"), $user_data->data->display_name) . ' - ';
					return $title;
				endif;
				return $title;
			}
		
		}
		
		new booked_profiles();
		
	}
	
endif;