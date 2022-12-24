<?php
/*---------------------------------------------------------
Plugin Name: WP Slug Post Type Custom Language
Plugin URI: https://wordpress.org/plugins/wp-slug-post-type-custom-language/
Author: carlosramosweb
Author URI: http://plugins.criacaocriativa.com/
Donate link: http://donate.criacaocriativa.com/
Description: Change the slug of your post type relative to the desired language.
Text Domain:  crrq_load_plugin_textdomain
Domain Path: /languages
Version: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
------------------------------------------------------------*/
 
/*
* Exit if accessed directly
* Sair se for acessado diretamente
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!class_exists('CRRQ_WP_Slug_Post_Type_Custom_Language'))  {	
define( 'CRRQ_WP_SLUG_POST_TYPE_CUSTOM_LANGUAGE_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );
define( 'CRRQ_WP_SLUG_POST_TYPE_CUSTOM_LANGUAGE_PLUGIN_BASENAME', plugin_basename(__FILE__) );
/**
 * Class WP_Slug_Post_Type_Custom_Language
 */
class CRRQ_WP_Slug_Post_Type_Custom_Language {
	
	public $wp_slug_language = array();
	public $get_post_type_object = array();
	
	
	public function __construct() {	
		
		if ( is_admin() ){
			add_filter( 'plugin_action_links_' . CRRQ_WP_SLUG_POST_TYPE_CUSTOM_LANGUAGE_PLUGIN_BASENAME, array( $this, 'crrq_plugin_action_links_settings' ) );
			add_action('admin_menu', array( $this, 'crrq_add_submenu_page_callback' ));		
			add_option( 'crrq_wp_slug_post_type_custom_language', '', '', 'yes' );
			add_action( 'init', array( $this, 'crrq_localise' ) );
			
			require_once( CRRQ_WP_SLUG_POST_TYPE_CUSTOM_LANGUAGE_PLUGIN_DIR_PATH . '/inc/list-custom-slug-language.php');
			require_once( CRRQ_WP_SLUG_POST_TYPE_CUSTOM_LANGUAGE_PLUGIN_DIR_PATH . '/inc/new-custom-slug-language.php');
			require_once( CRRQ_WP_SLUG_POST_TYPE_CUSTOM_LANGUAGE_PLUGIN_DIR_PATH . '/inc/edit-custom-slug-language.php');
			require_once( CRRQ_WP_SLUG_POST_TYPE_CUSTOM_LANGUAGE_PLUGIN_DIR_PATH . '/inc/footer-slug-language.php');
		}
			add_action('after_setup_theme', array( $this, 'crrq_action_slug_language_settings' ));
	}	

	public function crrq_localise() {
		load_plugin_textdomain( 'crrq_load_plugin_textdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}	
	
	public function get_array_post_type_custom() {
		return array( '', 'post', 'page', 'attachment', 'revision', 'nav_menu_item', 'polylang_mo', 'wpcf7_contact_form', 'acf-field-group', 'acf-field');
	}
	
	public function crrq_check_string_language($languages) {
		$languages = esc_attr($languages);
		if(!empty($languages)) {
			$language_list = $this->crrq_wp_get_available_translations();
			
			foreach($language_list as $language) {
				if($language['language'] == $languages) {
					return $language['english_name'];
				}
			}
		}
	}	
	
	public function crrq_wp_get_available_translations() {
		require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
		$language_list = wp_get_available_translations('core');
		
		return $language_list;
	}
	
	public function crrq_plugin_action_links_settings( $links ) {
		$action_links = array(
			'settings' => '<a href="'.admin_url().'tools.php?page=custom-slug-language" title="'.__( 'Plugin Settings', 'crrq_load_plugin_textdomain' ).'" class="error">'.__( 'Settings', 'crrq_load_plugin_textdomain' ).'</a>',
		);
		return array_merge( $action_links, $links );
	}
	
	public function crrq_check_option_languages($option, $languages) {
		if($option == $languages) {
			return ' selected="selected"';
		}
	}
	
	public function crrq_check_option_post_type($option, $post_type) {
		if($option == $post_type) {
			return ' selected="selected"';
		}
	}
	
	public function crrq_add_submenu_page_callback() {		
		add_submenu_page( 
			'tools.php', 
			__( 'Slug Language', 'crrq_load_plugin_textdomain' ), 
			__( 'Slug Language', 'crrq_load_plugin_textdomain' ),
			'manage_options', 
			'custom-slug-language',
			'crrq_language_page_callback'
		);
		add_submenu_page(
			NULL,
			__( 'Slug Language', 'crrq_load_plugin_textdomain' ), 
			__( 'Slug Language', 'crrq_load_plugin_textdomain' ),
			'manage_options',
			'new-custom-slug-language',
			'crrq_new_language_page_callback'
		); 
		add_submenu_page(
			NULL,
			__( 'Slug Language', 'crrq_load_plugin_textdomain' ), 
			__( 'Slug Language', 'crrq_load_plugin_textdomain' ),
			'manage_options',
			'edit-custom-slug-language',
			'crrq_edit_language_page_callback'
		); 
	}
	
	public function crrq_action_slug_language_settings() {
		$wp_slug_language = get_option( 'crrq_wp_slug_post_type_custom_language' );
		
		if(!empty($wp_slug_language[0])) {
			
			function crrq_custom_post_type_slug_custom_language() {
				$wp_slug_language = get_option( 'crrq_wp_slug_post_type_custom_language' );
				if(isset($wp_slug_language)) {
				for ($i = 0; $i < count($wp_slug_language); $i++) {
					if ( post_type_exists( sanitize_text_field($wp_slug_language[$i]['post_type']) ) ) {
						$get_post_type_object = get_post_type_object( sanitize_text_field($wp_slug_language[$i]['post_type']) );				
						if(get_locale() == sanitize_text_field($wp_slug_language[$i]['languages'])) {
							
							$label = sanitize_text_field($get_post_type_object->label);
							$description = sanitize_text_field($get_post_type_object->description);
							$capability_type = sanitize_text_field($get_post_type_object->capability_type);
							$map_meta_cap = sanitize_text_field(is_bool($get_post_type_object->map_meta_cap));
							$hierarchical = sanitize_text_field(is_bool($get_post_type_object->hierarchical));
							$menu_icon = sanitize_text_field($get_post_type_object->menu_icon);
							
							$label_name = sanitize_text_field($get_post_type_object->labels->name);
							$label_singular_name = sanitize_text_field($get_post_type_object->labels->singular_name);
							$label_menu_name = sanitize_text_field($get_post_type_object->labels->menu_name);
								
							if(empty($get_post_type_object->labels->add_new)) {
								$label_add_new = 'Add New';
							} else {
								$label_add_new = sanitize_text_field($get_post_type_object->labels->add_new);
							}							
							if(empty($get_post_type_object->labels->add_new_item)) {
								$label_add_new_item = 'Add New Item';
							} else {
								$label_add_new_item = sanitize_text_field($get_post_type_object->labels->add_new_item);
							}
							if(empty($get_post_type_object->labels->edit)) {
								$label_edit = 'Edit';
							} else {
								$label_edit = sanitize_text_field($get_post_type_object->labels->edit);
							}
							if(empty($get_post_type_object->labels->edit_item)) {
								$label_edit_item = 'Edit Item';
							} else {
								$label_edit_item = sanitize_text_field($get_post_type_object->labels->edit_item);
							}
							if(empty($get_post_type_object->labels->new_item)) {
								$label_new_item = 'New Item';
							} else {
								$label_new_item = sanitize_text_field($get_post_type_object->labels->new_item);
							}
							if(empty($get_post_type_object->labels->view)) {
								$label_view = 'View';
							} else {
								$label_view = sanitize_text_field($get_post_type_object->labels->view);
							}
							if(empty($get_post_type_object->labels->view_item)) {
								$label_view_item = 'View Item';
							} else {
								$label_view_item = sanitize_text_field($get_post_type_object->labels->view_item);
							}
							if(empty($get_post_type_object->labels->search_items)) {
								$label_search_items = 'Search Item';
							} else {
								$label_search_items = sanitize_text_field($get_post_type_object->labels->search_items);
							}
							if(empty($get_post_type_object->labels->not_found)) {
								$label_not_found = 'Not Found';
							} else {
								$label_not_found = sanitize_text_field($get_post_type_object->labels->not_found);
							}
							if(empty($get_post_type_object->labels->not_found_in_trash)) {
								$label_not_found_in_trash = 'Not Found in Trash';
							} else {
								$label_not_found_in_trash = sanitize_text_field($get_post_type_object->labels->not_found_in_trash);
							}
							
							$slug_post_type = sanitize_text_field($wp_slug_language[$i]['slug_post_type']);
							$post_type = sanitize_text_field($wp_slug_language[$i]['post_type']);
							
							$args = array(
								'label'						=> $label,
								'description'				=> $description,
								'show_ui'					=> true,
								'show_in_menu'				=> true,
								'show_in_admin_bar'			=> true,
								'capability_type'			=> $capability_type,
								'map_meta_cap'				=> $map_meta_cap,
								'hierarchical'				=> $hierarchical,
								'query_var'					=> true,
								'menu_icon'					=> $menu_icon,
								'supports'					=> array('title', 'editor', 'page-attributes','post-formats','comments'),
								'public'					=> true,
								'labels' => array (
									'name'					=> $label_name,
									'singular_name'			=> $label_singular_name,
									'menu_name'				=> $label_menu_name,
									'add_new'				=> $label_add_new,
									'add_new_item'			=> $label_add_new_item,
									'edit'					=> $label_edit,
									'edit_item'				=> $label_edit_item,
									'new_item'				=> $label_new_item,
									'view'					=> $label_view,
									'view_item'				=> $label_view_item,
									'search_items'			=> $label_search_items,
									'not_found'				=> $label_not_found,
									'not_found_in_trash'	=> $label_not_found_in_trash
								),
								'rewrite' => array(
									'slug'       => $slug_post_type,
									'with_front' => true
								),
							);				
							register_post_type( $post_type, $args );
						}
					}
				}
				}
				
			}
			add_action('init', 'crrq_custom_post_type_slug_custom_language');
			flush_rewrite_rules();
			return;
		}
	}
	
}

/**
 * Enable class
 */
new CRRQ_WP_Slug_Post_Type_Custom_Language();
}