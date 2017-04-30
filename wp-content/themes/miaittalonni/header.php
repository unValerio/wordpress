<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Miaittalonni
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php miaittalonni_get_page_preloader(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'miaittalonni' ); ?></a>
	<header id="masthead" <?php miaittalonni_header_class(); ?> role="banner">
		<?php miaittalonni_ads_header() ?>
		<?php get_template_part( 'template-parts/header/top-panel' ); ?>
		<div class="header-wrapper">
			<div class="header-container invert">
				<div <?php echo miaittalonni_get_container_classes( array( 'header-container_wrap' ), 'header' ); ?>>
					<?php get_template_part( 'template-parts/header/layout', get_theme_mod( 'header_layout_type' ) ); ?>
				</div>
			</div><!-- .header-container -->
		<?php get_template_part( 'template-parts/header/showcase-panel' ); ?>
		</div>
	</header><!-- #masthead -->

	<div id="content" <?php miaittalonni_content_class(); ?>>
