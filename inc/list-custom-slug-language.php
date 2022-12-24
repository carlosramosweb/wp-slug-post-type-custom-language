<?php 
if(!function_exists('crrq_language_page_callback'))  {	
	function crrq_language_page_callback() { 
			if(current_user_can('manage_options')) {
			$wp_slug_language = get_option( 'crrq_wp_slug_post_type_custom_language' );
			$delete_slug_language = NULL;		
			$my_nonce = wp_create_nonce( 'crrq-language-nonce' );	
			$CRRQ_WP_Slug_Post_Type_Custom_Language = new CRRQ_WP_Slug_Post_Type_Custom_Language();		
			
			if(isset($_GET['action']) && isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'crrq-language-nonce' )) {
				$action = esc_attr($_GET['action']);
				if('0' == intval($_GET['slug_id'])) {
					$slug_id = '0';			
				} else {
					$slug_id = intval($_GET['slug_id']);
				}
				
				if (!is_string($_GET['action']) && !is_numeric($slug_id)) {
					$delete_slug_language = false;
					
				} else {	
					if (isset($action) && isset($slug_id)) {			
						if (isset($slug_id)) {
							unset($wp_slug_language[$slug_id]);	
							sort($wp_slug_language);			
							update_option( 'crrq_wp_slug_post_type_custom_language', $wp_slug_language );
							
							$delete_slug_language = true;
						}	
					}
				}
			}
			?>
	<div id="wpbody" role="main">
		<div class="wrap">
		<h1>
			<?php _e( 'WP Slug Post Type Custom Language', 'crrq_load_plugin_textdomain' ); ?>
		</h1>
		<?php if ($delete_slug_language == true) { ?>
		<div id="message" class="updated notice notice-success is-dismissible">
			<p>
				<?php _e( 'The item was deleted successfully!', 'crrq_load_plugin_textdomain' ); ?>
			</p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">
			<?php _e( 'Dismiss this notice.', 'crrq_load_plugin_textdomain' ); ?>
			</span></button>
		</div>
		<?php } ?>
		<h2 class="nav-tab-wrapper wp-clearfix"> 
		<a href="<?php admin_url(); ?>tools.php?page=custom-slug-language&tab=settings" class="nav-tab nav-tab-active">
			<?php _e( 'Settings', 'crrq_load_plugin_textdomain' ); ?>
			</a> <a href="<?php admin_url();?>tools.php?page=custom-slug-language&tab=donate" class="nav-tab">
			<?php _e( 'Donate', 'crrq_load_plugin_textdomain' ); ?>
			</a> </h2>
		<br/>
		<?php if (empty($wp_slug_language)) { ?>
		<div class="error inline">
			<p>
				<?php _e( '<strong>Notice:</strong> Plugin does not have configured item.', 'crrq_load_plugin_textdomain' ); ?>
			</p>
		</div>
		<?php } ?>
		<h3>
			<?php _e( 'Add new language', 'crrq_load_plugin_textdomain' ); ?>
		</h3>
		<p>
			<?php _e( 'Set by assigning a language for an item and typing your new slug.<br/> <strong>Rules:</strong> If <strong>Language</strong> ​​is equal to <strong>Post Type</strong> the then <strong>Slug</strong> will... <strong>(Languages == Post Type => Slug)</strong>', 'crrq_load_plugin_textdomain' ); ?>
		</p>
		<br/>
	  <ul class="subsubsub">
		<li class="all">
            <a class="current">
				<?php _e( 'All', 'crrq_load_plugin_textdomain' ); ?>
				<?php if (is_countable($wp_slug_language) && count($wp_slug_language) > 0) {
					$count_slug_language = count($wp_slug_language);
				} else {
					$count_slug_language = 0;
				} ?>
                <span class="count">(<?php echo esc_attr($count_slug_language); ?>)</span>
            </a> |
        </li>
		<li class="publish">
            <a>
				<?php _e( 'Published', 'crrq_load_plugin_textdomain' ); ?>
                <span class="count">(<?php echo esc_attr($count_slug_language); ?>)</span>
            </a>
        </li>
	  </ul>
		<table class="wp-list-table widefat fixed striped posts" id="wp_slug_post_type_custom_language">
			<thead>
				<tr>
					<th class="slug-id"><?php _e( '<strong>ID</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th class="slug-languages"><?php _e( '<strong>Languages</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th class="slug-post-type"><?php _e( '<strong>Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th class="slug-slug"><?php _e( '<strong>Slug Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th class="slug-original"><?php _e( '<strong>Slug Original</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th class="slug-action"><?php _e( '<strong>Action</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php if (empty($wp_slug_language) && 'Array' != $wp_slug_language) { ?>
				<tr class="inactive">
					<th colspan="6" scope="row">
						<?php _e( 'No items were found.', 'crrq_load_plugin_textdomain' ); ?>
					</th>
				</tr>
				<?php } else { ?>
				<?php 
						if (!empty($wp_slug_language)) {
						for ($i = 0; $i < esc_attr(count($wp_slug_language)); $i++) {
							if ( post_type_exists( esc_attr($wp_slug_language[$i]['post_type']) ) ) {
					?>
				<!-- class="active" class="inactive" -->
				<tr class="inactive">
					<td><?php echo $i; ?>
						</th>
					<td>
						<?php 
						$name_language = $CRRQ_WP_Slug_Post_Type_Custom_Language->crrq_check_string_language(esc_attr($wp_slug_language[$i]['languages'])); 
						if (empty($name_language)) {
							echo $wp_slug_language[$i]['languages'];
						} else {
							echo $name_language;
						}
						?>
					</th>
					<td><?php echo esc_attr($wp_slug_language[$i]['post_type']); ?></td>
					<td><?php echo esc_attr($wp_slug_language[$i]['slug_post_type']); ?></td>
					<td><?php _e( 'Undefined', 'crrq_load_plugin_textdomain' ); ?></td>
					<td><a href="<?php admin_url();?>tools.php?page=custom-slug-language&action=delete&slug_id=<?php echo esc_attr($i); ?>&_wpnonce=<?php echo $my_nonce; ?>" class="button tagadd">
						<?php _e( 'Delete', 'crrq_load_plugin_textdomain' ); ?>
						</a> <a href="<?php admin_url();?>tools.php?page=edit-custom-slug-language&slug_id=<?php echo esc_attr($i); ?>&_wpnonce=<?php echo $my_nonce; ?>" class="button button-primary">
						<?php _e( 'Edit', 'crrq_load_plugin_textdomain' ); ?>
						</a></td>
				</tr>
				<?php } ?>
				<?php } } ?>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php _e( '<strong>ID</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th><?php _e( '<strong>Languages</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th><?php _e( '<strong>Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th><?php _e( '<strong>Slug Post Type</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th><?php _e( '<strong>Slug Original</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
					<th><?php _e( '<strong>Action</strong>', 'crrq_load_plugin_textdomain' ); ?></th>
				</tr>
			</tfoot>
		</table>
		<br/>
		<a href="<?php admin_url();?>tools.php?page=new-custom-slug-language&_wpnonce=<?php echo $my_nonce; ?>" class="button button-primary button-large">
			<?php _e( 'Add New', 'crrq_load_plugin_textdomain' ); ?>
		</a>
			<?php crrq_footer_slug_language(); ?>
			</div>
		</div>
	<?php 
		}
	}
}