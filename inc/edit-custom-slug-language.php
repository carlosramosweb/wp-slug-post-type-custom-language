<?php 
if(!function_exists('crrq_edit_language_page_callback'))  {	
	function crrq_edit_language_page_callback() { 
		if(current_user_can('manage_options') && isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'crrq-language-nonce' )) {
			$CRRQ_WP_Slug_Post_Type_Custom_Language = new CRRQ_WP_Slug_Post_Type_Custom_Language();
			$wp_slug_language = get_option( 'crrq_wp_slug_post_type_custom_language' );
			$edit_slug_language = NULL;
			
			if('0' == intval($_GET['slug_id'])) {
				$slug_id = 0;			
			} else if (isset($_GET['slug_id']) && is_numeric($_GET['slug_id'])){
				$slug_id = intval($_GET['slug_id']);
			}
			
			if (isset($_POST['action']) && 'edit' == esc_attr($_POST['action']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'crrq-language-nonce' )) {
				
				$wp_slug_language[$slug_id]['languages'] = sanitize_text_field($_POST['languages']);
				$wp_slug_language[$slug_id]['post_type'] = sanitize_text_field($_POST['post_type']);
				$wp_slug_language[$slug_id]['slug_post_type'] = sanitize_text_field($_POST['slug_post_type']);
	
				update_option( 'crrq_wp_slug_post_type_custom_language', $wp_slug_language);
				
				$wp_slug_language = get_option( 'crrq_wp_slug_post_type_custom_language' );
				$edit_slug_language = true;			
			} else {
				$edit_slug_language = false;	
			}
	?>
	<div id="wpbody" role="main">
		<div class="wrap">
				<h1>
					<?php _e( 'WP Slug Post Type Custom Language', 'crrq_load_plugin_textdomain' ); ?>
					<a href="<?php admin_url(); ?>tools.php?page=custom-slug-language" class="page-title-action">
						<?php _e( 'Back to list', 'crrq_load_plugin_textdomain' ); ?>
					</a>
				</h1>
				<hr/>
				<?php if ($edit_slug_language == true) { ?>
				<div id="message" class="updated notice notice-success is-dismissible">
					<p>
						<?php _e( 'The item has been successfully edited!', 'crrq_load_plugin_textdomain' ); ?>
					</p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">
					<?php _e( 'Dismiss this notice.', 'crrq_load_plugin_textdomain' ); ?>
					</span></button>
				</div>
				<?php } ?>
				<h3>
					<?php _e( 'Edit Slug Language', 'crrq_load_plugin_textdomain' ); ?>
				</h3>
				<p>
					<?php _e( 'Set by assigning a language for an item and typing your new slug.<br/> <strong>Rules:</strong> If <strong>Language</strong> ​​is equal to <strong>Post Type</strong> the then <strong>Slug</strong> will... <strong>(Languages == Post Type => Slug)</strong>', 'crrq_load_plugin_textdomain' ); ?>
				</p>
				<br/>
				<?php if (!empty($wp_slug_language[$slug_id])) { ?>
				<form method="post" action="<?php admin_url(); ?>tools.php?page=edit-custom-slug-language&slug_id=<?php echo esc_attr($slug_id); ?>">
					<input type="hidden" name="action" value="edit">
					<input type="hidden" name="slug_id" value="<?php echo esc_attr($slug_id); ?>">
                    <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($_REQUEST['_wpnonce']); ?>">
					<table id="commentstatusdiv" class="postbox" style="width:100%;">
						<tr>
							<td colspan="2" class="inside">                    
								<h2><strong><?php _e( 'Edit Slug Language', 'crrq_load_plugin_textdomain' ); ?></strong></h2>
								<hr/>
							 </td>
						</tr>
						<tr>
							<td class="inside" style="width:100px;">
								<?php _e( '<strong>Languages</strong>', 'crrq_load_plugin_textdomain' ); ?>
							</td>
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
										<option value="<?php echo esc_attr($languages); // locale ?>" lang="<?php echo esc_attr($languages); // flag_code ?>" <?php echo $CRRQ_WP_Slug_Post_Type_Custom_Language->crrq_check_option_languages(esc_attr($languages) /*esc_attr($languages->locale)*/, esc_attr($wp_slug_language[$slug_id]['languages']));?>>
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
									<?php if(count($language_list) > 0)  { ?>
									<optgroup label="<?php _e( 'WordPress', 'crrq_load_plugin_textdomain' ); ?>">
                                    	<?php foreach($language_list as $languages) { ?>
										<option value="<?php echo esc_attr($languages['language']); ?>" lang="<?php echo esc_attr($languages['iso'][1]); ?>" <?php echo $CRRQ_WP_Slug_Post_Type_Custom_Language->crrq_check_option_languages(esc_attr($languages['language']), esc_attr($wp_slug_language[$slug_id]['languages']));?>><?php echo esc_attr($languages['english_name']); ?></option>
                                        <?php } ?>
									</optgroup> 
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="inside"><?php _e( '<strong>Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?></td>
							<td class="inside">
								<select name="post_type" id="post_type" required>
								<?php 
								$i = 0;
								foreach ( get_post_types( '', 'names' ) as $post_type ) {
									if (array_search($post_type, $CRRQ_WP_Slug_Post_Type_Custom_Language->get_array_post_type_custom()) == false) {
										if ($i % 2 == 0) {
											echo '<option value="' . esc_attr($post_type) . '" class="color" '. $CRRQ_WP_Slug_Post_Type_Custom_Language->crrq_check_option_post_type(esc_attr($post_type), esc_attr($wp_slug_language[$slug_id]['post_type'])).'>' . esc_attr($post_type) . '</option>';
										} else {
											echo '<option value="' . esc_attr($post_type) . '" '. $CRRQ_WP_Slug_Post_Type_Custom_Language->crrq_check_option_post_type(esc_attr($post_type), esc_attr($wp_slug_language[$slug_id]['post_type'])).'>' . esc_attr($post_type) . '</option>';
										}
										$i++;
									}
								}
								?>
								</select>
								</td>
						</tr>
						<tr>
							<td class="inside"><?php _e( '<strong>Slug Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?></td>
							<td class="inside"><input name="slug_post_type" type="text" placeholder="<?php _e( 'Slug Language', 'crrq_load_plugin_textdomain' ); ?>" value="<?php echo esc_attr($wp_slug_language[$slug_id]['slug_post_type']); ?>"></td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
				<?php } else { ?>
				<div class="error inline">
					<p>
						<?php _e( '<strong>Notice:</strong> There is not configured with this item id.', 'crrq_load_plugin_textdomain' ); ?>
					</p>
				</div>
				<a href="<?php admin_url();?>tools.php?page=custom-slug-language" class="button button-primary button-large">
					<?php _e( 'Back to list', 'crrq_load_plugin_textdomain' ); ?>
				</a>
				<?php } ?>
			<?php crrq_footer_slug_language(); ?>
			</div>
		</div>
<?php 
		}
	}
}