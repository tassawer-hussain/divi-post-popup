<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://fiverr.com/tassawer
 * @since      1.0.0
 *
 * @package    Divi_Post_Popup
 * @subpackage Divi_Post_Popup/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Divi_Post_Popup
 * @subpackage Divi_Post_Popup/admin
 * @author     Tassawer Hussain <th.tassawer@gmail.com>
 */
class Divi_Post_Popup_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('add_meta_boxes', array($this, 'th_addMetabox'));
        add_action('save_post', array($this, 'th_saveMetabox'));

	}

	public function th_addMetabox() {
        add_meta_box(
            'custom_metabox_id',
            'Popup Settings',
            array($this, 'th_renderMetabox'),
            'post',  // Change this to the desired post type
            'side',
            'default'
        );
    }

    public function th_renderMetabox($post) {
        $checked = get_post_meta($post->ID, 'th_display_popup', true);
        ?>
        <label for="th_display_popup">
            <input type="checkbox" id="th_display_popup" name="th_display_popup" value="1" <?php checked($checked, '1'); ?>>
            Display Content in Popup
        </label>
        <?php
    }

    public function th_saveMetabox($post_id) {
        if (isset($_POST['th_display_popup'])) {
            update_post_meta($post_id, 'th_display_popup', '1');
        } else {
            delete_post_meta($post_id, 'th_display_popup');
        }
    }



}

