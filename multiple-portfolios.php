<?php
/**
 * Multiple Portfolios
 *
 * @package   Multiple_Portfolios
 * @author    Quema Labs
 * @license   GPL-2.0+
 * @link      https://www.quemalabs.com/
 * @copyright 2017 Quema Labs
 *
 * @wordpress-plugin
 * Plugin Name: Multiple Portfolios
 * Plugin URI:  https://github.com/nicoandrade/Multiple-Portfolios/
 * Description: Create multiple portfolios on your WordPress site.
 * Version:     1.0.1
 * Author:      Quema Labs
 * Author URI:  https://www.quemalabs.com/
 * Text Domain: multiple-portfolios
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Required files for registering the post type and taxonomies.
require plugin_dir_path( __FILE__ ) . 'includes/class-multiple-portfolios.php';
require plugin_dir_path( __FILE__ ) . 'includes/interface-gamajo-registerable.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-gamajo-post-type.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-gamajo-taxonomy.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-multiple-portfolios-post-type.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-multiple-portfolios-taxonomy-category.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-multiple-portfolios-taxonomy-tag.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-multiple-portfolios-registrations.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$multiple_portfolios_registrations = new Multiple_Portfolios_Registrations;

// Instantiate main plugin file, so activation callback does not need to be static.
$multiple_portfolios = new Multiple_Portfolios( $multiple_portfolios_registrations );

// Register callback that is fired when the plugin is activated.
register_activation_hook( __FILE__, array( $multiple_portfolios, 'activate' ) );

// Initialise registrations for post-activation requests.
$multiple_portfolios_registrations->init();

add_action( 'init', 'multiple_portfolios_init', 100 );
/**
 * Adds styling to the dashboard for the post type and adds portfolio posts
 * to the "At a Glance" metabox.
 *
 * Adds custom taxonomy body classes to portfolio posts on the front end.
 *
 * @since 0.8.3
 */
function multiple_portfolios_init() {
	if ( is_admin() ) {

		global $multiple_portfolios_admin, $multiple_portfolios_registrations;
		// Loads for users viewing the WordPress dashboard
		if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
			require plugin_dir_path( __FILE__ ) . 'includes/class-gamajo-dashboard-glancer.php';  // WP 3.8
		}
		require plugin_dir_path( __FILE__ ) . 'includes/class-multiple-portfolios-admin.php';
		$multiple_portfolios_admin = new Multiple_Portfolios_Admin( $multiple_portfolios_registrations );
		$multiple_portfolios_admin->init();

	} else {
		// Loads for users viewing the front end
		if ( apply_filters( 'multiple_portfolios_add_taxonomy_terms_classes', true ) ) {
			if ( ! class_exists( 'Gamajo_Single_Entry_Term_Body_Classes' ) ) {
				require plugin_dir_path( __FILE__ ) . 'includes/class-gamajo-single-entry-term-body-classes.php';
			}
			global $multiple_portfolios;
			if ( $multiple_portfolios ) {
				$multi_portfolios_names = $multiple_portfolios->get_post_types();
			}else{
				$multi_portfolios_names = array( 'slug' => 'portfolio', 'name' => 'Portfolio');
			}

			foreach ( $multi_portfolios_names as $multi_portfolios_name ) {
				$multiple_portfolios_body_classes = new Gamajo_Single_Entry_Term_Body_Classes;
				$multiple_portfolios_body_classes->init( $multi_portfolios_name['slug'] );
			}
		}
	}
}
