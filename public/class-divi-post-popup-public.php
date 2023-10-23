<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://fiverr.com/tassawer
 * @since      1.0.0
 *
 * @package    Divi_Post_Popup
 * @subpackage Divi_Post_Popup/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Divi_Post_Popup
 * @subpackage Divi_Post_Popup/public
 * @author     Tassawer Hussain <th.tassawer@gmail.com>
 */
class Divi_Post_Popup_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_filter('post_class', array($this, 'th_addCustomClass'), 999, 3);

		// ajax hook for logged-in users: wp_ajax_{action}
		// add_action( 'wp_ajax_public_hook', array($this, 'ajax_public_handler') );

		// ajax hook for non-logged-in users: wp_ajax_nopriv_{action}
		// add_action( 'wp_ajax_nopriv_public_hook', array($this, 'ajax_public_handler') );

		// display HTML popup in footer
		add_action( 'wp_footer', array( $this, 'display_popup_html'), 888 );
		add_action( 'wp_head', array( $this, 'display_loader_markup'), 2 );
		

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/divi-post-popup-public.css', array(), $this->version, 'all' );

		// wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/divi-post-popup-public.js', array( 'jquery' ), $this->version, false );

		// wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), '', true);

		wp_localize_script($this->plugin_name, 'fromPHP', array(
            'pluginUrl' => plugin_dir_url(__FILE__),
            'breakpoint' => '768',
			'styled' => '1',
            'disableScrolling' => true,
            'loader' => '1',
            'ajax_url' => admin_url('admin-ajax.php'),
            'siteUrl' => get_bloginfo('url'),
            'restMethod' => '1',
			'urlState' => '1',
            'containerID' => '#modal-ready',
            'modalLinkClass' => 'modal-link',
            'isAdmin' => is_admin(),
			'customizing' => is_customize_preview(),
        ));

	}

	public function th_addCustomClass($classes, $class, $post_id) {

		$checked = get_post_meta($post_id, 'th_display_popup', true);

        if ( is_home() && $checked === '1') { // Check if it's an archive page (blog page)
			$classes[] = 'th-display-popup';
		}
		// $classes[] = is_home() ? "is_home" : "not_home";

		return $classes;
	}

	// process ajax request
	public function ajax_public_handler() {

		// check nonce
		check_ajax_referer( 'ajax_public', 'nonce' );

		// define author id
		$post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : false;

		$post_id = explode("-", $post_id);

		// define user description
		$description = get_post_field('post_content', $post_id[1]);

		$description = apply_filters('the_content', $description);

		// output results
		echo $description;

		// end processing
		wp_die();

	}

	public function display_popup_html() {
		$styled = 'styled';

        $close = '×';

        $HTML = '<div class="modal-wrapper ' . $styled . '" role="dialog" aria-modal="true"  aria-label="' . __('Popup Dialog', 'wp-post-modal') . '">';
        $HTML .= '<div class="wp-post-modal">';
        $HTML .= '<button type="button" aria-label="' . __('Close', 'wp-post-modal') . '" class="close-modal"> ' . $close . ' </button>';
        $HTML .= '<div id="modal-content"></div>';
        $HTML .= '</div>';
        $HTML .= '</div>';

        echo html_entity_decode(esc_html($HTML));
	}

	public function any_post_api_route()
    {

        register_rest_route($this->plugin_name . '/v1', '/any-post-type/', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_content_by_slug'),
            'permission_callback' => '__return_true',
            'args' => array(
                'slug' => array(
                    'required' => false,
                ),
            ),
        ));
    }

    /**
     *
     * Get content by slug
     *
     * @param WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_content_by_slug(WP_REST_Request $request)
    {
        // get slug from request
        $slug = $request['slug'];

        // get title by slug
        $post = get_page_by_path($slug, ARRAY_A, get_post_types());

        if (!empty($post['post_password'])) {
            $response = new WP_Error('post_password_protected', 'Post is password protected', array('status' => 403));
        } elseif ($post['post_status'] !== "publish") {
            $response = new WP_Error('post_private', 'Post is not published', array('status' => 403));
        } elseif ($post['post_content'] && $post['post_status'] === "publish") {

            // render shortcodes from Visual Composer
            $post['post_content'] = apply_filters('the_content', $post['post_content']);
            $filtered_post = array_intersect_key($post, array_flip(array('post_content')));

            $response = new WP_REST_Response($filtered_post);
        } else {
            $response = new WP_Error('post_empty', 'Post is empty', array('status' => 404));
        }

        return $response;
    }

	public function display_loader_markup() {
		echo "<div class='th-loading'></div>";
	}
	
}

