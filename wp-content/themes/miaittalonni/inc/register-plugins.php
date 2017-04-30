<?php
/**
 * Register required plugins for TGM Plugin Activator
 *
 * @package Miaittalonni
 */
add_action( 'tgmpa_register', 'miaittalonni_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function miaittalonni_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array (
			'name'     => esc_html__( 'Restaurant Menu by MotoPress', 'miaittalonni' ),
			'slug'     => 'mp-restaurant-menu',
			'required' => false,
		),
		array (
			'name'         => esc_html__( 'Cherry Sidebar Manager', 'miaittalonni' ),
			'slug'         => 'cherry-sidebar-manager',
			'source'       => 'https://github.com/CherryFramework/cherry-sidebar-manager/archive/master.zip',
			'external_url' => 'https://github.com/CherryFramework/cherry-sidebar-manager',
		),
		array(
			'name'     => esc_html__( 'Cherry LD Switcher', 'miaittalonni' ),
			'slug'     => 'cherry-ld-switcher',
			'required' => false,
			'source'   => MIAITTALONNI_THEME_DIR . '/assets/plugins/cherry-ld-mods-switcher.zip',
		),
		array(
			'name'     => esc_html__( 'Power builder', 'miaittalonni' ),
			'slug'     => 'power-builder',
			'required' => false,
			'source'   => MIAITTALONNI_THEME_DIR . '/assets/plugins/power-builder.zip',
		),
		array (
			'name'     => esc_html__( 'Builder integrator', 'miaittalonni' ),
			'slug'     => 'tm-builder-integrator',
			'required' => false,
			'source'   => MIAITTALONNI_THEME_DIR . '/assets/plugins/tm-builder-integrator.zip',
		),
		array (
			'name'     => 'booked',
			'slug'     => 'booked',
			'required' => false,
			'source'   => MIAITTALONNI_THEME_DIR . '/assets/plugins/booked.zip',
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'miaittalonni',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
