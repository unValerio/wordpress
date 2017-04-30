<div class="booked-settings-wrap wrap">
	
	<div class="booked-settings-title"><?php _e('Booked Add-Ons','booked'); ?></div>
	
	<div id="booked-admin-panel-container">
		<div class="form-wrapper">
			<div id="booked-add-ons" class="tab-content">
				
				<div class="section-row" style="margin-bottom:-40px;">
					<div class="section-head bookedClearFix">
						
						<?php
							
						$json_array = get_transient( 'booked_addons_json' );
						
						$temp = 0; $total_addons = 0;
						
						if (!$json_array): $json_array = array(); else : $json_array = json_decode($json_array,true); endif;
													
						if (!empty($json_array)):
							
							foreach($json_array as $product):
							
								if (isset($product['envato_market_url']) && isset($product['product_price'])):
							
									$temp++; $total_addons++;
									
									if ($temp == 1): echo '<div class="bookedClearFix">'; endif;
								
									$plugin_slug = $product['plugin_slug'];
									
									echo '<div class="booked-addon-block">';
									
										if (!get_option('booked_addon_viewed_'.$plugin_slug)):
											echo '<span class="addon-new">'.__('New!','booked').'</span>';
										endif;
								
										echo '<img src="'.$product['image_icon'].'">';
										echo '<h3>'.$product['title'].'</h3>';
										echo wpautop($product['description']);
										if( in_array($plugin_slug . DIRECTORY_SEPARATOR . $plugin_slug . '.php',apply_filters('active_plugins',get_option('active_plugins')))):
											echo '<p><button class="button" disabled="disabled">'.__('Installed &amp; Active','booked').'</button></p>';
										else:
											echo '<p><a class="button button-primary" href="'.$product['envato_market_url'].'" target="_blank">'.$product['product_price'].' - Purchase</a></p>';
										endif;
									echo '</div>';
									
									update_option('booked_addon_viewed_'.$plugin_slug,true);
									
									if ($temp == 3): echo '</div>'; $temp = 0; endif;
									
								endif;
									
							endforeach;
							
							if ($temp != 3): echo '</div>'; $temp = 0; endif;
							
						endif;
						
						if (!$total_addons):
						
							echo '<p style="margin:1.5%; text-align:center; font-size:16px;"><i class="fa fa-exclamation-triangle" style="color:#56C477; display:block; text-align:center; font-size:75px; margin:0 0 20px;"></i>' . sprintf( __('There was an issue loading the available add-ons, please %s to purchase directly from our website.','booked'), '<strong><a href="http://boxystudio.com/add-ons/" target="_blank">'.__('click here','booked').'</a></strong>') . '</p>';
						
						endif;
														
						?>
											
					</div><!-- /section-head -->
				</div><!-- /section-row -->
				
			</div><!-- /tab-content -->
		</div><!-- /form-wrapper -->
		
	</div><!-- /booked-admin-panel-container -->
</div><!-- /booked-settings-wrap -->