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
 * Portfolio category taxonomy.
 *
 * @package Multiple_Portfolios
 * @author  Devin Price
 * @author  Gary Jones
 */
class Multiple_Portfolios_Taxonomy_Category extends Gamajo_Taxonomy {
	/**
	 * Taxonomy ID.
	 *
	 * @since 1.0.0
	 *
	 * @type string
	 */
	public $taxonomy = 'portfolio_category';

	/**
	 * Post Type ID.
	 *
	 * @since 1.0.0
	 *
	 * @type array
	 */
	public $post_type = array(
							'name' => 'Portfolio',
							'slug' => 'portfolio',
						);

	/**
	 * Return taxonomy default arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Taxonomy default arguments.
	 */
	protected function default_args() {

		$labels = array(
			'name'                       => sprintf( __( '%s Categories', 'multiple-portfolios' ), $this->post_type['name'] ),
			'singular_name'              => sprintf( __( '%s Category', 'multiple-portfolios' ), $this->post_type['name'] ),
			'menu_name'                  => sprintf( __( '%s Categories', 'multiple-portfolios' ), $this->post_type['name'] ),
			'edit_item'                  => sprintf( __( 'Edit %s Category', 'multiple-portfolios' ), $this->post_type['name'] ),
			'update_item'                => sprintf( __( 'Update %s Category', 'multiple-portfolios' ), $this->post_type['name'] ),
			'add_new_item'               => sprintf( __( 'Add New %s Category', 'multiple-portfolios' ), $this->post_type['name'] ),
			'new_item_name'              => sprintf( __( 'New %s Category Name', 'multiple-portfolios' ), $this->post_type['name'] ),
			'parent_item'                => sprintf( __( 'Parent %s Category', 'multiple-portfolios' ), $this->post_type['name'] ),
			'parent_item_colon'          => sprintf( __( 'Parent %s Category:', 'multiple-portfolios' ), $this->post_type['name'] ),
			'all_items'                  => sprintf( __( 'All %s Categories', 'multiple-portfolios' ), $this->post_type['name'] ),
			'search_items'               => sprintf( __( 'Search %s Categories', 'multiple-portfolios' ), $this->post_type['name'] ),
			'popular_items'              => sprintf( __( 'Popular %s Categories', 'multiple-portfolios' ), $this->post_type['name'] ),
			'separate_items_with_commas' => sprintf( __( 'Separate portfolio categories with commas', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s categories', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s categories', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ),
			'not_found'                  => sprintf( __( 'No %s categories found.', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ),
			'items_list_navigation'      => sprintf( __( '%s categories list navigation', 'multiple-portfolios' ), $this->post_type['name'] ),
			'items_list'                 => sprintf( __( '%s categories list', 'multiple-portfolios' ), $this->post_type['name'] ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => $this->post_type['slug'] . '_category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		return apply_filters( 'multiple_portfolios_category_args', $args );
	}
}
