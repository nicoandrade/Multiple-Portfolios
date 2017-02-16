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
 * Register post types and taxonomies.
 *
 * @package Multiple_Portfolios
 * @author  Devin Price
 * @author  Gary Jones
 */
class Multiple_Portfolios_Registrations {

	public $post_type;

	public $taxonomies;

	public function init() {
		// Add the portfolio post type and taxonomies
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 */
	public function register() {
		global $multi_portfolios_post_type, $multi_portfolios_taxonomy_category, $multi_portfolios_taxonomy_tag, $multiple_portfolios;
		
		if ( $multiple_portfolios ) {
			$multi_portfolios_names = $multiple_portfolios->get_post_types();
		}else{
			$multi_portfolios_names = array( 'slug' => 'portfolio', 'name' => 'Portfolio');
		}
		

		foreach ( $multi_portfolios_names as $multi_portfolios_name ) {



			$multi_portfolios_post_type = new Multiple_Portfolios_Post_Type;
			$multi_portfolios_post_type->post_type = $multi_portfolios_name;

			$multi_portfolios_post_type->register();
			$this->post_type = $multi_portfolios_post_type->get_post_type();

			$multi_portfolios_taxonomy_category = new Multiple_Portfolios_Taxonomy_Category;
			$multi_portfolios_taxonomy_category->post_type = $multi_portfolios_name;
			$multi_portfolios_taxonomy_category->taxonomy = $multi_portfolios_name['slug'] . '_category';
			$multi_portfolios_taxonomy_category->register();
			$this->taxonomies[] = $multi_portfolios_taxonomy_category->get_taxonomy();

			$multi_portfolios_post_type_slug = $multi_portfolios_post_type->get_post_type();
			register_taxonomy_for_object_type(
				$multi_portfolios_taxonomy_category->get_taxonomy(),
				$multi_portfolios_post_type_slug['slug']
			);

			$multi_portfolios_taxonomy_tag = new Multiple_Portfolios_Taxonomy_Tag;
			$multi_portfolios_taxonomy_tag->post_type = $multi_portfolios_name;
			$multi_portfolios_taxonomy_tag->taxonomy = $multi_portfolios_name['slug'] . '_tag';
			$multi_portfolios_taxonomy_tag->register();
			$this->taxonomies[] = $multi_portfolios_taxonomy_tag->get_taxonomy();
			$multi_portfolios_post_type_slug = $multi_portfolios_post_type->get_post_type();
			register_taxonomy_for_object_type(
				$multi_portfolios_taxonomy_tag->get_taxonomy(),
				$multi_portfolios_post_type_slug['slug']
			);
		}
	}

	/**
	 * Unregister post type and taxonomies registrations.
	 */
	public function unregister() {
		global $multi_portfolios_post_type, $multi_portfolios_taxonomy_category, $multi_portfolios_taxonomy_tag;
		$multi_portfolios_post_type->unregister();
		$this->post_type = null;

		$multi_portfolios_taxonomy_category->unregister();
		unset( $this->taxonomies[ $multi_portfolios_taxonomy_category->get_taxonomy() ] );

		$multi_portfolios_taxonomy_tag->unregister();
		unset( $this->taxonomies[ $multi_portfolios_taxonomy_tag->get_taxonomy() ] );
	}

}
