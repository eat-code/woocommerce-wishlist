<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('admin_menu', 'thm_notification_create_menu'); // create custom plugin settings menu
add_action( 'admin_init', 'thm_notification_register_mysettings' ); //Settings value Save
register_activation_hook( __FILE__, 'thm_notification_init_value' ); // Initial Value Save

function thm_notification_create_menu() {
    add_submenu_page('options-general.php', 'Notification Bar', 'Notification Bar', 'manage_options', 'thm_notice_bar', 'thm_notification_settings_page'); // Admin menu add
}

// Set init value data
function thm_notification_init_value(){
	if( !get_option('thm_enable') ){
		$thm_settings_array = array(
								'thm_enable' 			=> 'yes',
								'thm_message_text' 		=> 'Add your message here', 
								'thm_link_text' 		=> 'Learn More',
								'thm_link_url' 			=> '#',
								'thm_link_target' 		=> '_blank',
								'thm_align' 			=> 'center',
								'thm_font_size' 		=> '14',
								'thm_font_weight' 		=> '400',
								'thm_background_color' 	=> '#f36523',
								'thm_background_image' 	=> '',
								'thm_wrap_height' 		=> '54',
								'thm_font_color' 		=> '#fff',
								'thm_link_color' 		=> '#19b526',
								'thm_link_hcolor' 		=> '#19b526',
								'thm_button_color' 		=> '#1e50d5',
								'thm_btn_size' 			=> '14',
								'thm_btn_weight' 		=> '400',
								'thm_button_bgcolor' 	=> '#fff',
								'thm_button_hcolor' 	=> '#0039d6',
								'thm_button_hbgcolor' 	=> '#f4f4f4',
								'thm_btn_padding' 		=> '5px 12px 5px 12px',
								'thm_btn_border_radius' => '20px 20px 20px 20px',
								'thm_close_color' 		=> '#fff',
								'thm_close_hcolor' 		=> '5px 12px 5px 12px',
							);
		foreach ($thm_settings_array as $key=>$value) {
			update_option( $key , $value );
		}
	}
}


// Settings Save Data
function thm_notification_register_mysettings() {
	$thm_settings_array = array(
		'thm_enable',
		'thm_message_text', 
		'thm_link_text',
		'thm_link_url',
		'thm_link_target',
		'thm_align',
		'thm_font_size',
		'thm_font_weight',
		'thm_background_color',
		'thm_background_image',
		'thm_wrap_height',
		'thm_font_color',
		'thm_link_color',
		'thm_link_hcolor',
		'thm_button_color',
		'thm_btn_size',
		'thm_btn_weight',
		'thm_button_bgcolor',
		'thm_button_hcolor',
		'thm_button_hbgcolor',
		'thm_btn_padding',
		'thm_btn_border_radius',
		'thm_close_color',
		'thm_close_hcolor',
	);
	foreach ($thm_settings_array as $value) {
		register_setting( 'thm-settings-group', $value );
	}
}


// Settings Page
function thm_notification_settings_page() {
	?>
	<div class="wrap">
		<h2>Themeum Notice Settings</h2>
		<form method="post" action="options.php">
		    <?php settings_fields( 'thm-settings-group' ); ?>
		    <?php do_settings_sections( 'thm-settings-group' ); ?>
			
			<?php //do_action( 'thm_previvew' ); ?>
		    <div id="thm-steps">
				<h3><?php _e('General Settings','notification-bar') ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Enable Notice','notification-bar') ?></th>
						<td>
							<?php 
								$thm_enable = get_option('thm_enable');
								$thm_enable_select = '';
								if ( $thm_enable == 'yes' ) {
									$thm_enable_select = 'checked';
								}
							?>
							<label style="margin-right: 20px;"><input type="radio" name="thm_enable" value="no" checked><?php _e('No','notification-bar') ?></label>
							<label style="margin-right: 20px;"><input type="radio" name="thm_enable" value="yes" <?php echo $thm_enable_select; ?> ><?php _e('Yes','notification-bar') ?></label>
						</td>
					</tr>
				</table>

				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Text Message','notification-bar') ?></th>
						<td>
							<textarea name="thm_message_text" rows="5" cols="50"><?php echo get_option('thm_message_text'); ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Button Text','notification-bar') ?></th>
						<td>
							<input class="regular-text" type="text" name="thm_link_text" placeholder="<?php _e('Add Button text','notification-bar'); ?>" value="<?php echo get_option('thm_link_text'); ?>" />
						</td>
					</tr>
					<tr class="thm-link-url">
						<th scope="row"><?php _e('Button URL','notification-bar') ?></th>
						<td>
							<input class="regular-text" type="text" name="thm_link_url" placeholder="<?php _e('#','notification-bar'); ?>" value="<?php echo get_option('thm_link_url'); ?>" />
						</td>
					</tr>
					<tr class="thm-link-target">
						<th scope="row"><?php _e('Target Link','notification-bar') ?></th>
						<td>
							<?php 
								$thm_link_target = get_option('thm_link_target');
								$thm_link_target_select = '';
								if ( $thm_link_target == '_blank' ) {
									$thm_link_target_select = 'checked';
								}
							?>
							<label style="margin-right: 20px;"><input type="radio" name="thm_link_target" value="_self" checked> <?php _e('No','notification-bar'); ?> </label>

							<label style="margin-right: 20px;"><input type="radio" name="thm_link_target" value="_blank" <?php echo $thm_link_target_select; ?>> <?php _e('Yes','notification-bar'); ?> </label>
						</td>
					</tr>
				</table>

				<h3><?php _e('Content Style','notification-bar') ?></h3>
				<table class="form-table">
					<tr valign="top">
						<?php
							$thm_font_size = get_option('thm_font_size');
							if ( $thm_font_size == '' ) {
								$thm_font_size = '14';
							}
						?>
						<th scope="row"><?php _e('Font size','notification-bar') ?></th>
						<td><input type="text" name="thm_font_size" value="<?php echo $thm_font_size; ?>" />px</td>
					</tr>
					<tr valign="top">
						<?php
							$thm_font_weight = get_option('thm_font_weight');
							if ( $thm_font_weight == '' ) {
								$thm_font_weight = '400';
							}
						?>
						<th scope="row"><?php _e('Font Weight','notification-bar') ?></th>
						<td><input type="text" name="thm_font_weight" value="<?php echo $thm_font_weight; ?>" /></td>
					</tr>
					<tr valign="top">
						<?php
							$thm_font_color = get_option('thm_font_color');
							if ( $thm_font_color == '' ) {
								$thm_font_color = '';
							}
						?>
						<th scope="row"><?php _e('Text Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_font_color" value="<?php echo $thm_font_color; ?>" /></td>
					</tr>
					<tr valign="top" class="thm-link-color"> 
						<?php
							$thm_link_color = get_option('thm_link_color');
							if ( $thm_link_color == '' ) {
								$thm_link_color = '';
							}
						?>
						<th scope="row"><?php _e('Link Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_link_color" value="<?php echo $thm_link_color; ?>" /></td>
					</tr>
					<tr valign="top" class="thm-link-color"> 
						<?php
							$thm_link_hcolor = get_option('thm_link_hcolor');
							if ( $thm_link_hcolor == '' ) {
								$thm_link_hcolor = '';
							}
						?>
						<th scope="row"><?php _e('Link Hover Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_link_hcolor" value="<?php echo $thm_link_hcolor; ?>" /></td>
					</tr>
					<tr valign="top">
						<?php
							$thm_close_color = get_option('thm_close_color');
							if ( $thm_close_color == '' ) {
								$thm_close_color = '';
							}
						?>
						<th scope="row"><?php _e('Close Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_close_color" value="<?php echo $thm_close_color; ?>" /></td>
					</tr>			
					<tr valign="top">
						<?php
							$thm_close_hcolor = get_option('thm_close_hcolor');
							if ( $thm_close_hcolor == '' ) {
								$thm_close_hcolor = '';
							}
						?>
						<th scope="row"><?php _e('Close Hover Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_close_hcolor" value="<?php echo $thm_close_hcolor; ?>" /></td>
					</tr>
				</table>

				<h3><?php _e('Button Style','notification-bar') ?></h3>
				<table class="form-table">
					<tr valign="top">
						<?php
							$thm_btn_size = get_option('thm_btn_size');
							if ( $thm_btn_size == '' ) {
								$thm_btn_size = '12';
							}
						?>
						<th scope="row"><?php _e('Button Font size','notification-bar') ?></th>
						<td><input type="text" name="thm_btn_size" value="<?php echo $thm_btn_size; ?>" />px</td>
					</tr>
					<tr valign="top">
						<?php
							$thm_btn_weight = get_option('thm_btn_weight');
							if ( $thm_btn_weight == '' ) {
								$thm_btn_weight = '400';
							}
						?>
						<th scope="row"><?php _e('Button Font Weight','notification-bar') ?></th>
						<td><input type="text" name="thm_btn_weight" value="<?php echo $thm_btn_weight; ?>" /></td>
					</tr>
					<tr valign="top">
						<?php
							$thm_button_color = get_option('thm_button_color');
							if ( $thm_button_color == '' ) {
								$thm_button_color = '';
							}
						?>
						<th scope="row"><?php _e('Button Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_button_color" value="<?php echo $thm_button_color; ?>" /></td>
					</tr>
					<tr valign="top">
						<?php
							$thm_button_hcolor = get_option('thm_button_hcolor');
							if ( $thm_button_hcolor == '' ) {
								$thm_button_hcolor = '';
							}
						?>
						<th scope="row"><?php _e('Button Hover Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_button_hcolor" value="<?php echo $thm_button_hcolor; ?>" /></td>
					</tr>
					<tr valign="top">
						<?php
							$thm_button_bgcolor = get_option('thm_button_bgcolor');
							if ( $thm_button_bgcolor == '' ) {
								$thm_button_bgcolor = '';
							}
						?>
						<th scope="row"><?php _e('Button Bg Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_button_bgcolor" value="<?php echo $thm_button_bgcolor; ?>" /></td>
					</tr>			
					<tr valign="top">
						<?php
							$thm_button_hbgcolor = get_option('thm_button_hbgcolor');
							if ( $thm_button_hbgcolor == '' ) {
								$thm_button_hbgcolor = '';
							}
						?>
						<th scope="row"><?php _e('Button Bg Hover Color','notification-bar') ?></th>
						<td><input class="color-picker" type="text" name="thm_button_hbgcolor" value="<?php echo $thm_button_hbgcolor; ?>" /></td>
					</tr>
					<tr valign="top">
						<?php
							$thm_btn_padding = get_option('thm_btn_padding');
							if ( $thm_btn_padding == '' ) {
								$thm_btn_padding = '6px 12px 6px 12px';
							}
						?>
						<th scope="row"><?php _e('Button padding','notification-bar') ?></th>
						<td><input type="text" name="thm_btn_padding" value="<?php echo $thm_btn_padding; ?>" />6px 12px 6px 12px</td>
					</tr>				

					<tr valign="top">
						<?php
							$thm_btn_border_radius = get_option('thm_btn_border_radius');
							if ( $thm_btn_border_radius == '' ) {
								$thm_btn_border_radius = '3px 3px 3px 3px';
							}
						?>
						<th scope="row"><?php _e('Border Radius','notification-bar') ?></th>
						<td><input type="text" name="thm_btn_border_radius" value="<?php echo $thm_btn_border_radius; ?>" />3px 3px 3px 3px</td>
					</tr>
				</table>
				
				<h3><?php _e('Notice Wrapper Style','notification-bar') ?></h3>
				<table class="form-table">
					<tr valign="top">
						<?php
							$thm_background_color = get_option('thm_background_color');
							if ( $thm_background_color == '' ) {
								$thm_background_color = '';
							}
						?>
						<th scope="row"><?php _e('Background Color','notification-bar') ?></th>
						<td><input type="text" name="thm_background_color" value="<?php echo $thm_background_color; ?>" class="color-picker" data-alpha="true" /></td>
					</tr>
					<tr valign="top">
						<?php
							$thm_background_image = get_option('thm_background_image');
							if ( $thm_background_image == '' ) {
								$thm_background_image = '';
							}
						?>
						<th scope="row"><?php _e('Background Image','notification-bar') ?></th>
						<td>
							<input class="regular-text thm_background_image" type="text" name="thm_background_image" value="<?php echo $thm_background_image; ?>" placeholder="<?php _e('add image url','notification-bar'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<?php
							$thm_wrap_height = get_option('thm_wrap_height');
							if ( $thm_wrap_height == '' ) {
								$thm_wrap_height = '40';
							}
						?>
						<th scope="row"><?php _e('Bar Height','notification-bar') ?></th>
						<td><input type="text" name="thm_wrap_height" value="<?php echo $thm_wrap_height; ?>" />px</td>
					</tr>
					<tr class="thm-align">
						<th scope="row"><?php _e('Alignment','notification-bar') ?></th>
						<td>
							<?php 
								$thm_align = get_option('thm_align');
								$thm_align_select = '';
								if ( $thm_align == 'center' ) {
									$thm_align_select = 'checked';
								}
							?>
							<label style="margin-right: 20px;"><input type="radio" name="thm_align" value="left" checked> <?php _e('Left','notification-bar'); ?> </label>

							<label style="margin-right: 20px;"><input type="radio" name="thm_align" value="center" <?php echo $thm_align_select; ?>> <?php _e('Center','notification-bar'); ?> </label>
						</td>
					</tr>
				</table>
			</div><!--/.thm-steps-->
		    <?php submit_button(); ?>
		</form><!--/.form-->
	</div><!--/.wrap-->
<?php } 
