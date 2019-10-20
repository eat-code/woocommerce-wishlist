<?php
/*
 * Plugin Name:       WooCommerce Wishlist
 * Plugin URI:        
 * Description:       Wishlist For WooCommerce.
 * Version:           1.0.0
 * Author:            Yoreyoga
 * Author URI:        
 * Text Domain:       woocommerce-wishlist
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
define( 'THM_WISHLIST_DIR_PATH', plugin_dir_path(__FILE__) );
define( 'THM_WISHLIST_PATH_URL', plugin_dir_url(__FILE__) );

include_once THM_WISHLIST_DIR_PATH . 'wishlist-option.php';


/*
*  Language Add
*/
add_action( 'init', 'thm_wishlist_language_load' );
function thm_wishlist_language_load(){
    $plugin_dir = basename(dirname(__FILE__))."/languages/";
    load_plugin_textdomain( 'woocommerce-wishlist', false, $plugin_dir );
}


/*
*  Save Option Data
*/
if ( ! function_exists( 'thm_wishlist_get_option' )) {
	function thm_wishlist_get_option( $option_name, $default = '' ) {
		$options = get_option( $option_name );
		if( $options != '' ) {
			return $options;
		}
		return $default;
	}
}


/*
*  Add Admin Style
*/
function thm_wishlist_scripts() {
	wp_enqueue_style( 'thm_style', THM_WISHLIST_PATH_URL . 'css/thm-wishlist.css');
	wp_enqueue_script('thm_style_js', THM_WISHLIST_PATH_URL . 'js/thm-wishlist.js', array( 'jquery'),'1.0',true);
	wp_localize_script( 'thm_style_js', 'ajax_', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}
add_action( 'wp_enqueue_scripts', 'thm_wishlist_scripts');


/*
*  Add Admin Scripts
*/
function thm_wishlist_admin_scripts(){
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script('thm_admin_style_js', THM_WISHLIST_PATH_URL . 'js/thm-admin-notification.js', array( 'jquery'),'1.0',true);
}
add_action('admin_enqueue_scripts', 'thm_wishlist_admin_scripts');


/*
*  Single Page Button Add
*/
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart_button_func' );
function add_content_after_addtocart_button_func() {
    echo '<a href="#" class="add-to-wishlist bordered-btn" data-productid="'.get_the_ID().'"><i class="winkel winkel-heart"></i> '.__("Add To Wishlist","woocommerce-wishlist").'</a>';
}


function thm_wishlist_button_add() {
	global $product;
	echo '<a href="#" class="add-to-wishlist love" data-productid="'.get_the_ID().'"><i class="winkel winkel-heart"></i></a>';
}
add_action( 'woocommerce_after_shop_loop_item', 'thm_wishlist_button_add', 9 );



/*
*  Set Wishlists
*/
add_action( 'wp_ajax_setwishlist', 'thm_set_wishlist_data');
add_action( 'wp_ajax_nopriv_setwishlist', 'thm_set_wishlist_data' );
function thm_set_wishlist_data(){
	if ( ! is_user_logged_in() ){
		die(json_encode(
				array(
					'success'	=> 0, 
					'title' 	=> __('You have to Sign In', 'woocommerce-wishlist'), 
					'message' 	=> __('Please Sign in First to Add Wishlist', 'woocommerce-wishlist'),
					'image' 	=> THM_WISHLIST_PATH_URL.'/img/cancel.png',
				 ))
			);
	}
	$productid		= sanitize_text_field($_POST['productid']);
	$wishlist_data	= get_user_meta( get_current_user_id(), 'wishlist_data', true);
	if( $productid ){
		$url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ).'wishlist';
		$wishlist_data = json_decode($wishlist_data, true);
		if ( in_array( $productid, $wishlist_data ) ){
			die(json_encode(
				array(
					'success'	=> 1, 
					'title' 	=> __('Product Already in Wishlist!', 'woocommerce-wishlist'), 
					'message' 	=> __('Product Already in Wishlist.', 'woocommerce-wishlist'),
					'image' 	=> THM_WISHLIST_PATH_URL.'/img/success.png',
					'btnText' 	=> __('View Wishlist', 'woocommerce-wishlist'),
					'btnUrl' 	=> $url,
				 ))
			);
		}
		$wishlist_data[] = $productid;
		$ids             = json_encode($wishlist_data);
		update_user_meta( get_current_user_id(), 'wishlist_data', $ids );
		die(json_encode(
			array(
				'success'	=> 1, 
				'title' 	=> __('Added to Wishlist', 'woocommerce-wishlist'), 
				'message' 	=> __('Product Added to the Wishlist.', 'woocommerce-wishlist'),
				'image' 	=> THM_WISHLIST_PATH_URL.'/img/success.png',
				'btnText' 	=> __('View Wishlist', 'woocommerce-wishlist'),
				'btnUrl' 	=> $url,
			 ))
		);

	}
}


/*
*  Wishlists To Cart
*/
add_action( 'wp_ajax_setcartlist', 'thm_set_cartlist_data');
add_action( 'wp_ajax_nopriv_setcartlist', 'thm_set_cartlist_data' );
function thm_set_cartlist_data(){
	if ( ! is_user_logged_in() ){
		die(json_encode(
			array(
				'success'	=> 0, 
				'title' 	=> __('You have to Sign In', 'woocommerce-wishlist'), 
				'message' 	=> __('Please Sign in First to Add Wishlist', 'woocommerce-wishlist'),
				'image' 	=> THM_WISHLIST_PATH_URL.'/img/cancel.png',
			 ))
		);
	}
	$productid = sanitize_text_field($_POST['productid']);
	if( $productid ){
		$productid = explode( ',',$productid );
		if( $productid ){
			global $woocommerce;
			foreach( $productid as $value ){
				$woocommerce->cart->add_to_cart($value);
			}
			$url = $woocommerce->cart->get_cart_url();
			die(json_encode(
				array(
					'success'	=> 0, 
					'title' 	=> __('Add to Cart Done', 'woocommerce-wishlist'), 
					'message' 	=> __('Product Add to Cart.', 'woocommerce-wishlist'),
					'image' 	=> THM_WISHLIST_PATH_URL.'/img/success.png',
					'btnText'	=> __('Go to Cart', 'woocommerce-wishlist'),
					'btnUrl'	=> $url,
				 ))
			);
		}
	}
}


/*
*  Remove From Cart List
*/
add_action( 'wp_ajax_removecart', 'thm_remove_cartlist_data');
add_action( 'wp_ajax_nopriv_removecart', 'thm_remove_cartlist_data' );
function thm_remove_cartlist_data(){
	if ( ! is_user_logged_in() ){
		die(json_encode(
			array(
				'success'	=> 0, 
				'title' 	=> __('You have to Sign In', 'woocommerce-wishlist'), 
				'message' 	=> __('Please Sign in First to Add Wishlist', 'woocommerce-wishlist'),
				'image' 	=> THM_WISHLIST_PATH_URL.'/img/cancel.png',
			 ))
		);
	}
	$productid			= sanitize_text_field($_POST['productid']);
	$wishlist_data		= get_user_meta( get_current_user_id(), 'wishlist_data', true);
	if( $productid ){
		if( $wishlist_data ){
			$wishlist_data = json_decode($wishlist_data, true);
			foreach (  $wishlist_data as $key=>$value ){
				if( $value == $productid ){
					unset( $wishlist_data[$key] );
				}
			}
			$ids = json_encode($wishlist_data);
			update_user_meta( get_current_user_id(), 'wishlist_data', $ids );
			$url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ).'wishlist';
			die(json_encode(
				array(
					'success'	=> 1,
					'title' 	=> __('Wishlist Update Done!', 'woocommerce-wishlist'), 
					'message' 	=> __('Wishlist Update Done!', 'woocommerce-wishlist'),
					'image' 	=> THM_WISHLIST_PATH_URL.'/img/success.png',
					'btnText' 	=> __('Confirm', 'woocommerce-wishlist'),
					'btnUrl' 	=> $url,
				 ))
			);
		}
	}
	die(json_encode(
		array(
			'success'	=> 0, 
			'title' 	=> __('Error Occured', 'woocommerce-wishlist'), 
			'message' 	=> __('Error Occured', 'woocommerce-wishlist'),
			'image' 	=> THM_WISHLIST_PATH_URL.'/img/cancel.png',
		 ))
	);
}


/*
*  My Account Menu Add
*/
function thm_wishlist_endpoints() {
    add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'thm_wishlist_endpoints' );

function thm_wishlist_query_vars( $vars ) {
    $vars[] = 'wishlist';
    return $vars;
}
add_filter( 'query_vars', 'thm_wishlist_query_vars', 0 );

function thm_wishlist_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'thm_wishlist_flush_rewrite_rules' );

function thm_wishlist_my_account_menu_items( $items ) {
	$items = array_merge(array_slice($items, 0, (count($items) - 1) ) , array('wishlist'=>__( 'Wishlist', 'woocommerce' )), array_slice($items, (count($items) - 1)));
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'thm_wishlist_my_account_menu_items' );

function thm_wishlist_endpoint_content() {
   echo '<table class="table table-bordered">';
		echo '<tr>';
			echo '<th>'.__("#","woocommerce-wishlist").'</th>';
			echo '<th>'.__("Image","woocommerce-wishlist").'</th>';
			echo '<th>'.__("Name","woocommerce-wishlist").'</th>';
			echo '<th>'.__("Action","woocommerce-wishlist").'</th>';
		echo '</tr>';

		$wishlist_data		= get_user_meta( get_current_user_id(), 'wishlist_data', true);
		$wishlist_data 		= json_decode($wishlist_data, true);

		if( $wishlist_data ){
			$args = array(
				'post_type' 	=> array( 'product' ),
				'orderby'   	=> 'ASC',
				'post__in'  	=> $wishlist_data,
				'posts_per_page'=> -1
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					echo '<tr>';
						echo '<td>#'.get_the_ID().'</td>';
						echo '<td>'.get_the_post_thumbnail( get_the_ID(), 'shop_thumbnail' ).'</td>';
						echo '<td>'.get_the_title().'</td>';
						echo '<td><a href="#" class="wishlist-remove" data-productid="'.get_the_ID().'">Remove</a></td>';
					echo '<tr>';
				}
				wp_reset_postdata();
			}
		}
    echo '</table>';
	
    if( $wishlist_data ){
		echo '<a href="#" class="wishlist-to-cart" data-productid="'.implode( ",",$wishlist_data ).'">Add Cart Page</a>';
	}
}
add_action( 'woocommerce_account_wishlist_endpoint', 'thm_wishlist_endpoint_content' );



// Add Shortcode
function thm_wishlist_shortcode( $atts, $content = "" ) {
	$html = '';

	$html .= '<table>';
	$html .= '<tr>';
		$html .= '<th>'.__("#","woocommerce-wishlist").'</th>';
		$html .= '<th>'.__("Image","woocommerce-wishlist").'</th>';
		$html .= '<th>'.__("Name","woocommerce-wishlist").'</th>';
		$html .= '<th>'.__("Action","woocommerce-wishlist").'</th>';
	$html .= '</tr>';

	$wishlist_data		= get_user_meta( get_current_user_id(), 'wishlist_data', true);
	$wishlist_data 		= json_decode($wishlist_data, true);

	if( $wishlist_data ){
		$args = array(
			'post_type' 	=> array( 'product' ),
			'orderby'   	=> 'ASC',
			'post__in'  	=> $wishlist_data,
			'posts_per_page'=> -1
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$html .= '<tr>';
					$html .= '<td>#'.get_the_ID().'</td>';
					$html .= '<td>'.get_the_post_thumbnail( get_the_ID(), 'shop_thumbnail' ).'</td>';
					$html .= '<td>'.get_the_title().'</td>';
					$html .= '<td><a href="#" class="wishlist-remove" data-productid="'.get_the_ID().'">Remove</a></td>';
				$html .= '<tr>';
			}
			wp_reset_postdata();
		}
	}
	$html .= '</table>';

	if( $wishlist_data ){
	$html .= '<a href="#" class="wishlist-to-cart" data-productid="'.implode( ",",$wishlist_data ).'">Add Cart Page</a>';
	}
	
	return $html;
}
add_shortcode( 'wishlist', 'thm_wishlist_shortcode' );
