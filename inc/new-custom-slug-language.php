<?php 
if(!function_exists('crrq_new_language_page_callback'))  {	
	function crrq_new_language_page_callback() { 
		if(current_user_can('manage_options') && isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'crrq-language-nonce' )) {
			$CRRQ_WP_Slug_Post_Type_Custom_Language = new CRRQ_WP_Slug_Post_Type_Custom_Language();
			$new_slug_language = NULL;
			
			if (isset($_POST['action']) && 'new' == esc_attr($_POST['action']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'crrq-language-nonce' )) {
				$wp_slug_language = get_option( 'crrq_wp_slug_post_type_custom_language' );
				$languages = sanitize_text_field($_POST['languages']);
				$post_type = sanitize_text_field($_POST['post_type']);
				$slug_post_type = sanitize_text_field($_POST['slug_post_type']);
				
				if(empty($wp_slug_language)) {
					$array_of_options = array( 
						array(
							'languages' => $languages,
							'post_type' => $post_type,
							'slug_post_type' => $slug_post_type
						),
					);	
					update_option( 'crrq_wp_slug_post_type_custom_language', $array_of_options );
				} else {
					$array_of_options = array(
						'languages' => $languages,
						'post_type' => $post_type,
						'slug_post_type' => $slug_post_type
					);
					array_push($wp_slug_language, $array_of_options);			
					update_option( 'crrq_wp_slug_post_type_custom_language', $wp_slug_language );
				}
				
				$new_slug_language = true;
			}
	?>
	<div id="wpbody" role="main">
		<div class="wrap">
				<h1>
					<?php _e( 'WP Slug Post Type Custom Language', 'crrq_load_plugin_textdomain' ); ?>
					<a href="<?php admin_url();?>tools.php?page=custom-slug-language" class="page-title-action">
						<?php _e( 'Back to list', 'crrq_load_plugin_textdomain' ); ?>
					</a>
				</h1>
				<hr/>
				<?php if ($new_slug_language == true) { ?>
				<div id="message" class="updated notice notice-success is-dismissible">
					<p>
						<?php _e( 'The item has been successfully added!', 'crrq_load_plugin_textdomain' ); ?>
					</p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">
					<?php _e( 'Dismiss this notice.', 'crrq_load_plugin_textdomain' ); ?>
					</span></button>
				</div>
				<?php } ?>
				<h3>
					<?php _e( 'New Slug Language', 'crrq_load_plugin_textdomain' ); ?>
				</h3>
				<p>
					<?php _e( 'Set by assigning a language for an item and typing your new slug.<br/> <strong>Rules:</strong> If <strong>Language</strong> ​​is equal to <strong>Post Type</strong> the then <strong>Slug</strong> will... <strong>(Languages == Post Type => Slug)</strong>', 'crrq_load_plugin_textdomain' ); ?>
				</p>
				<br/>
				<form method="post" action="<?php admin_url();?>tools.php?page=new-custom-slug-language">
					<input type="hidden" name="action" value="new">
                    <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($_REQUEST['_wpnonce']); ?>">
					<table id="commentstatusdiv" class="postbox" style="width:100%;">
						<tr>
							<td colspan="2" class="inside">                    
								<h2><strong><?php _e( 'New Slug Language', 'crrq_load_plugin_textdomain' ); ?></strong></h2>
								<hr/>
							 </td>
						</tr>
						<tr>
							<td class="inside" style="width:100px;"><?php _e( '<strong>Languages</strong>', 'crrq_load_plugin_textdomain' ); ?></td>
							<td class="inside">
								<?php 
								$language_list = $CRRQ_WP_Slug_Post_Type_Custom_Language->crrq_wp_get_available_translations(); 
								$args = array();
								?>
								<select name="languages" id="languages" required>
									<?php if(class_exists('Polylang'))  { 									
										global $polylang;
										if (isset($polylang)) {							
									?>
									<optgroup label="<?php _e( 'Polylang', 'crrq_load_plugin_textdomain' ); ?>">
										<?php foreach (pll_languages_list($args) as $key => $languages) { ?>
										<option value="<?php echo esc_attr($languages); // locale ?>" lang="<?php echo esc_attr($key); // flag_code ?>">
												<?php 
												$name_language = $CRRQ_WP_Slug_Post_Type_Custom_Language->crrq_check_string_language(esc_attr($languages)); 
												if (empty($name_language)) {
													echo $languages;
												} else {
													echo $name_language;
												}
												?>
										</option>
										<?php } ?>
									</optgroup> 
									<?php } } ?>
									<?php if (is_countable($language_list) && count($language_list) > 0) {?>
									<optgroup label="<?php _e( 'WordPress', 'crrq_load_plugin_textdomain' ); ?>">
                                    	<?php foreach($language_list as $languages) { ?>
										<option value="<?php echo esc_attr($languages['language']); ?>" lang="<?php echo esc_attr($languages['iso'][1]); ?>"><?php echo esc_attr($languages['english_name']); ?></option>
                                        <?php } ?>
									</optgroup> 
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="inside">
								<?php _e( '<strong>Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?>
							</td>
							<td class="inside">
							<select name="post_type" id="post_type" required>
								<?php 
								$i = 0;
								foreach ( get_post_types( '', 'names' ) as $post_type ) {
									if (array_search($post_type, $CRRQ_WP_Slug_Post_Type_Custom_Language->get_array_post_type_custom()) == false) {
										if ($i % 2 == 0) {
											echo '<option value="' . esc_attr($post_type) . '" class="color">' . esc_attr($post_type) . '</option>';
										} else {
											echo '<option value="' . esc_attr($post_type) . '">' . esc_attr($post_type) . '</option>';
										}
										$i++;
									}
								}
								?>
								</select>
								</td>
						</tr>
						<tr>
							<td class="inside">
								<?php _e( '<strong>Slug Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?>
							</td>
							<td class="inside">
								<input name="slug_post_type" type="text" placeholder="<?php _e( 'Slug Language', 'crrq_load_plugin_textdomain' ); ?>">
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			<?php crrq_footer_slug_language(); ?>
			</div>
		</div>
	<?php 
        }
    }
}