<?php
/**
 * @package   Owl_Post_Carousel
 * @author    Justin McGuire <mcguirejus@gmail.com>
 * @license   GPL-2.0+
 * @link      http://
 * @copyright 2016 Justin McGuire
 *
 * @wordpress-plugin
 * Plugin Name:       Owl Post Carousel
 * Plugin URI:        
 * Description:       Creates a carousel of posts from a specific category
 * Version:           1.0.0
 * Author:            Justin McGuire
 * Author URI:        
 * Text Domain:       owl-post-carousel
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /lang
 */
 
 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

class Owl_Post_Carousel extends WP_Widget {

    /**
     *
     * Unique identifier for your widget.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_slug = 'owl-post-carousel';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		// load plugin text domain
		//add_action( 'init', array( $this, 'owl-post-carousel' ) );

		// Hooks fired when the Widget is activated and deactivated
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// TODO: update description
		parent::__construct(
			$this->get_widget_slug(),
			__( 'Owl Post Carousel', $this->get_widget_slug() ),
			array(
				'classname'  => $this->get_widget_slug().'-class',
				'description' => __( 'Creates a carousel of posts.', $this->get_widget_slug() )
			)
		);

		// Register admin styles and scripts
		if ( is_admin() ) {
			//add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
			//add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		}

		// Register site styles and scripts
		if ( !is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
		}
		// Refreshing the widget's cached output with each new post
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	} // end constructor


    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {
		// Check if there is a cached output
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];
		
		// go on with your widget logic, put everything into a string and …

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		// TODO: Here is where you manipulate your widget's values based on their input fields
		ob_start();

		//include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		//allows for overrinding of tempalte by placing widget.php file in folder 'vowl-carousel'
		include( $this->getTemplateHierarchy( 'widget' ) );
		
		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;

	} // end widget
	
	
	public function flush_widget_cache() 
	{
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['category']		=	esc_attr($new_instance['category']);
		$instance['featuredimage']	=	esc_attr($new_instance['featuredimage']);
		$instance['newwindow']		=	esc_attr($new_instance['newwindow']);
		$instance['imagesize']		=	esc_attr($new_instance['imagesize']);
		$instance['itemcount']		=	esc_attr($new_instance['itemcount']);
		$instance['itemmargin']		=	esc_attr($new_instance['itemmargin']);
		$instance['nav']			=	esc_attr($new_instance['nav']);
		$instance['navdots']		=	esc_attr($new_instance['navdots']);
		$instance['autoplay']		=	esc_attr($new_instance['autoplay']);

		return $instance;
	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$widget_defaults = array(
			'itemmargin' => '20',
			'itemcount' => '5'
		);

		$instance = wp_parse_args(
			(array) $instance, 
			$widget_defaults	
		);

		// Display the admin form
		include( plugin_dir_path(__FILE__) . 'views/admin.php' );

	} // end form

	public function getTemplateHierarchy($template) {
		// whether or not .php was added
		$template_slug = rtrim($template, '.php');
		$template = $template_slug . '.php';

		if ( $theme_file = locate_template(array('owl-carousel/'.$template)) ) {
			$file = $theme_file;
		} else {
			$file = 'views/' . $template;
		}
		return apply_filters( 'template_owl-carousel_'.$template, $file);
	}

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {

		load_plugin_textdomain( $this->get_widget_slug(), false, plugin_dir_path( __FILE__ ) . 'lang/' );

	} // end widget_textdomain

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		
		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {
		
		wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );
		wp_enqueue_style( 'owl-carousel-styles', plugins_url( 'vendor/assets/owl.carousel.css', __FILE__ ) );
		wp_enqueue_style( 'owl-carousel-default-theme', plugins_url( 'vendor/assets/owl.theme.default.min.css', __FILE__ ) );

	} // end register_widget_styles

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

		wp_enqueue_script( 'owl-carousel-script', plugins_url( 'vendor/owl.carousel.min.js', __FILE__ ), array('jquery') );

	} // end register_widget_scripts

} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("Owl_Post_Carousel");' ) );
