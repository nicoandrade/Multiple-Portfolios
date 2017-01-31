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
 * Portfolio post type.
 *
 * @package Multiple_Portfolios
 * @author  Quema Labs
 */
class Multiple_Portfolios_Post_Type extends Gamajo_Post_Type {
	/**
	 * Post type ID.
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
	 * Return post type default arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Post type default arguments.
	 */
	protected function default_args() {
		$labels = array(
			'name'                  => $this->post_type['name'],
			'singular_name'         => sprintf( __( '%s Item', 'multiple-portfolios' ), $this->post_type['name'] ),
			'menu_name'             => $this->post_type['name'],
			'name_admin_bar'        => sprintf( _x( '%s Item', 'add new on admin bar', 'multiple-portfolios' ), $this->post_type['name'] ),
			'add_new'               => __( 'Add New Item', 'multiple-portfolios' ),
			'add_new_item'          => sprintf( __( 'Add New %s Item', 'multiple-portfolios' ), $this->post_type['name'] ),
			'new_item'              => sprintf( __( 'Add New %s Item', 'multiple-portfolios' ), $this->post_type['name'] ),
			'edit_item'             => sprintf( __( 'Edit %s Item', 'multiple-portfolios' ), $this->post_type['name'] ),
			'view_item'             => __( 'View Item', 'multiple-portfolios' ),
			'all_items'             => sprintf( __( 'All %s Items', 'multiple-portfolios' ), $this->post_type['name'] ),
			'search_items'          => sprintf( __( 'Search %s', 'multiple-portfolios' ), $this->post_type['name'] ),
			'parent_item_colon'     => sprintf( __( 'Parent %s Item:', 'multiple-portfolios' ), $this->post_type['name'] ),
			'not_found'             => sprintf( __( 'No %s items found', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ),
			'not_found_in_trash'    => sprintf( __( 'No %s items found in trash', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ),
			'filter_items_list'     => sprintf( __( 'Filter %s items list', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ),
			'items_list_navigation' => sprintf( __( '%s items list navigation', 'multiple-portfolios' ), $this->post_type['name'] ),
			'items_list'            => sprintf( __( '%s items list', 'multiple-portfolios' ), $this->post_type['name'] ),
		);

		$supports = array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'author',
			'custom-fields',
			'revisions',
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => $this->post_type['slug'], ), // Permalinks format
			'menu_position'   => 5,
			'menu_icon'       => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-portfolio' : false ,
			'has_archive'     => true,
		);

		return apply_filters( 'multiple_portfolios_args', $args );
	}

	/**
	 * Return post type updated messages.
	 *
	 * @since 1.0.0
	 *
	 * @return array Post type updated messages.
	 */
	public function messages() {
		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( '%s item updated.', 'multiple-portfolios' ), $this->post_type['name'] ),
			2  => __( 'Custom field updated.', 'multiple-portfolios' ),
			3  => __( 'Custom field deleted.', 'multiple-portfolios' ),
			4  => sprintf( __( '%s item updated.', 'multiple-portfolios' ), $this->post_type['name'] ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( '%s item restored to revision from %s', 'multiple-portfolios' ), $this->post_type['name'], wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( '%s item published.', 'multiple-portfolios' ), $this->post_type['name'] ),
			7  => sprintf( __( '%s item saved.', 'multiple-portfolios' ), $this->post_type['name'] ),
			8  => sprintf( __( '%s item submitted.', 'multiple-portfolios' ), $this->post_type['name'] ),
			9  => sprintf(
				__( '%s item scheduled for: <strong>%1$s</strong>.', 'multiple-portfolios' ),
				$this->post_type['name'],
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'multiple-portfolios' ), strtotime( $post->post_date ) )
			),
			10 => sprintf( __( '%s item draft updated.', 'multiple-portfolios' ), $this->post_type['name'] ),
		);

		if ( $post_type_object->publicly_queryable ) {
			$permalink         = get_permalink( $post->ID );
			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );

			$view_link    = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), sprintf( __( 'View %s item', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ) );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), sprintf( __( 'Preview %s item', 'multiple-portfolios' ), strtolower( $this->post_type['name'] ) ) );

			$messages[1]  .= $view_link;
			$messages[6]  .= $view_link;
			$messages[9]  .= $view_link;
			$messages[8]  .= $preview_link;
			$messages[10] .= $preview_link;
		}

		return $messages;
	}
}
