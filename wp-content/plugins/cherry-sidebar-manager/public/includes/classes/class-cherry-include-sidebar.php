<?php
/**
 * Cherry Include Custom Sidebar.
 *
 * @package   Cherry Sidebar Manager
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2015 Cherry Team
 **/

if ( ! class_exists( 'Cherry_Include_Custom_Sidebar' ) ) {

	/**
	 * Class for Include Custom Sidebar.
	 *
	 * @since 1.0.0
	 */
	class Cherry_Include_Custom_Sidebar {

		/**
		 * Holds the instances of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Sets up our actions/filters.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'sidebars_widgets', array( $this, 'set_custom_sidebar' ), 10, 1 );
		}

		/**
		 * Set custom sidebar in global array $wp_registered_sidebars.
		 *
		 * @since 1.0.0
		 */
		public function set_custom_sidebar( $widgets ) {
			$object_id = get_queried_object_id();

			if ( function_exists( 'is_shop' ) ) {
				if ( is_shop() || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
					$object_id = wc_get_page_id( 'shop' );
				}
			}

			$post_sidebars = get_post_meta( apply_filters( 'cherry_sidebar_manager_object_id', $object_id ), 'post_sidebar', true );

			if ( $post_sidebars && !empty( $post_sidebars ) ) {

				$Custom_Sidebars_Methods = new Cherry_Custom_Sidebars_Methods();
				$custom_sidebar = $Custom_Sidebars_Methods->get_custom_sidebar_array();

				foreach ( $post_sidebars as $sidebar => $sidebar_value ) {

					if ( '' !== $sidebar_value && isset( $widgets[ $sidebar ] ) || false !== strripos( $sidebar_value, 'cherry-sidebar-manager') && array_key_exists( $sidebar_value, $custom_sidebar ) ) {
						$widgets[ $sidebar ] = $widgets[ $sidebar_value ];
					}
				}
			}

			return $widgets;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}

	Cherry_Include_Custom_Sidebar::get_instance();
}
?>
