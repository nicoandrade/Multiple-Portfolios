<?php
/**
 * Multiple Portfolios
 *
 * @package   Multiple_Portfolios
 * @author    Quema Labs
 * @license   GPL-2.0+
 * @link      https://www.quemalabs.com/
 * @copyright 2017 Quema Labs
 */

/**
 * Registration of CPT and related taxonomies.
 *
 * @since Unknown
 */
class Multiple_Portfolios {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.7.0
	 *
	 * @var    string VERSION Plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    0.7.0
	 *
	 * @var      string
	 */
	const PLUGIN_SLUG = 'multiple-portfolios';

	protected $registration_handler;

	/**
	 * Initialize the plugin by setting localization and new site activation hooks.
	 *
	 * @since     0.7.0
	 */
	public function __construct( $registration_handler ) {

		$this->registration_handler = $registration_handler;

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		//Add menu page
		add_action( 'init', array( $this, 'create_parent_portfolios_cpt' ) );

	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    0.7.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is
	 *                                       disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide && $blog_ids = $this->get_blog_ids() ) {
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->single_activate();
				}
				restore_current_blog();
			} else {
				$this->single_activate();
			}
		} else {
			$this->single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    0.7.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is
	 *                                       disabled or plugin is deactivated on an individual blog.
	 */
	public function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide && $blog_ids = $this->get_blog_ids() ) {
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->single_deactivate();
				}
				restore_current_blog();
			} else {
				$this->single_deactivate();
			}
		} else {
			$this->single_deactivate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    0.7.0
	 *
	 * @param	int	$blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) )
			return;

		switch_to_blog( $blog_id );
		$this->single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    0.7.0
	 *
	 * @return	array|false	The blog ids, false if no matches.
	 */
	private function get_blog_ids() {
		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";
		return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    0.7.0
	 */
	private function single_activate() {
		$this->registration_handler->register();
		flush_rewrite_rules();
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    0.7.0
	 */
	private function single_deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.7.0
	 */
	public function load_plugin_textdomain() {
		$domain = self::PLUGIN_SLUG;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages' );
	}

	/**
	 * Get all portfolio names, including user generated
	 */
	public static function get_post_types() {
		$multi_portfolios_names = array();
		$args = array(
		    'post_type' => 'parent-portfolio',
		    'posts_per_page' => -1,
			'order' => 'ASC',
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) { $the_query->the_post();
				$multi_portfolios_names[] = array(
												'name' => get_the_title(),
												'slug' => get_post_field( 'post_name', get_post() ),
											);
			}//while
		}// if have posts
		wp_reset_postdata();

		return $multi_portfolios_names;
	}



	function create_parent_portfolios_cpt() {

		$labels = array(
			'name'               => _x( 'Parent Portfolios', 'post type general name', 'multiple-portfolios' ),
			'singular_name'      => _x( 'Parent Portfolio', 'post type singular name', 'multiple-portfolios' ),
			'menu_name'          => _x( 'Multiple Portfolios', 'admin menu', 'multiple-portfolios' ),
			'name_admin_bar'     => _x( 'Parent Portfolios', 'add new on admin bar', 'multiple-portfolios' ),
			'add_new'            => _x( 'Create new Parent Portfolio', 'parent portfolio', 'multiple-portfolios' ),
			'add_new_item'       => esc_html__( 'Create New Parent Portfolio', 'multiple-portfolios' ),
			'new_item'           => esc_html__( 'New Parent Portfolio', 'multiple-portfolios' ),
			'edit_item'          => esc_html__( 'Edit Parent Portfolio', 'multiple-portfolios' ),
			'view_item'          => esc_html__( 'View Parent Portfolio', 'multiple-portfolios' ),
			'all_items'          => esc_html__( 'All Parent Portfolios', 'multiple-portfolios' ),
			'search_items'       => esc_html__( 'Search Parent Portfolios', 'multiple-portfolios' ),
			'parent_item_colon'  => esc_html__( 'Parent Parent Portfolio:', 'multiple-portfolios' ),
			'not_found'          => esc_html__( 'No parent portfolio found.', 'multiple-portfolios' ),
			'not_found_in_trash' => esc_html__( 'No parent portfolio in Trash.', 'multiple-portfolios' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => esc_html__( 'Description.', 'multiple-portfolios' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			//'show_in_menu'		 => 'plugins.php',
			'show_in_nav_menus'  => false,
			'show_in_admin_bar'  => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'parent-portfolio', 'with_front' => false ),
			'map_meta_cap'        => true,
			'has_archive'        => false,
			'menu_icon'          => 'dashicons-schedule',
			'hierarchical'       => false,
			'menu_position'      => 66,
			'supports'           => array( 'title' )
		);
		register_post_type( 'parent-portfolio', $args );

		global $multiple_portfolios;
		if ( $multiple_portfolios ) {
			$multi_portfolios_names = $this->get_post_types();
		}else{
			$multi_portfolios_names = array( 'slug' => 'portfolio', 'name' => 'Portfolio');
		}

		if ( empty( $multi_portfolios_names ) ) {
			//Create the defaul Portfolio
			$default_portfolio = array(
			  'post_title'    => 'Portfolio',
			  'post_status'   => 'publish',
			  'post_type'     => 'parent-portfolio'
			);
			// Insert the post into the database
			wp_insert_post( $default_portfolio );
		}
	}

}
