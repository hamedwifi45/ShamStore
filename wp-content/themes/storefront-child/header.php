<?php

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php wp_head(); ?>
	<?php
	if (get_theme_mod('storefront_display_theme') == 'theme3') {
	?>
		<style>
			.woocommerce-shop .overlay {
				position: absolute;
				transition: all 0.3s ease;
				opacity: 0;
				background-color: #9bcd9b;
				z-index: 2;
			}

			/* التعديل الرئيسي هنا */
			.woocommerce-shop .product:hover .overlay {
				opacity: 1;
			}

			.woocommerce-shop .overlayFade {
				height: 100%;
				width: 100%;
				top: 0;
				background-color: #5758578c;
			}
			

			.woocommerce-shop .woocommerce-loop-product__title,
			.woocommerce-shop .price,
			.woocommerce-shop .add_to_cart_button,
			.woocommerce-shop .product_type_grouped,
			.woocommerce-shop .product_type_external {
				bottom: 250px;
				position: relative;
				display: none;
				z-index: 3;
				/* أضف z-index هنا أيضًا */
			}

			.woocommerce-shop .price,
			.woocommerce-shop .amount,
			.woocommerce-shop .woocommerce-price-suffix {
				font-size: 18px;
				font-weight: 700;
				color: #ffffff;
				display: none;
				z-index: 3;
			}

			.woocommerce-shop .add_to_cart_button,
			.woocommerce-shop .product_type_grouped,
			.woocommerce-shop .product_type_external {
				background-color: #7f54b3;
				color: #ffffff;
				font-weight: 500;
				font-size: 16px;
				border-radius: 20px;
				z-index: 3;
			}

			.woocommerce-shop .product:hover .woocommerce-loop-product__title {
				display: block;
				z-index: 3;
			}

			.woocommerce-shop .product:hover .add_to_cart_button,
			.woocommerce-shop .product:hover .product_type_grouped,
			.woocommerce-shop .product:hover .product_type_external {
				display: inline-block;
				margin-bottom: 0;
				z-index: 3;
			}

			.woocommerce-shop .product:hover .price,
			.woocommerce-shop .product:hover .amount,
			.woocommerce-shop .product:hover .woocommerce-price-suffix {
				display: inline-flex;
				margin-bottom: 0;
			}

			.woocommerce-shop .product:hover .woocommerce-loop-product__title {
				color: #ffffff;
				font-size: 24px !important;
				font-weight: 700 !important;
			}

			.shop-thumbnail-wrap {
				position: relative;
				/* margin: 0; */
			}

			.woocommerce-shop .price del {
				color: #7f54b3;
			}

			.woocommerce-shop .product:hover{
				margin-bottom: 0 !important;
			}
			.woocommerce-shop .product{
				margin-bottom: 0 !important;
			}

			.woocommerce-shop .add_to_cart_button:hover {
				background-color: red;
				font-size: 20px;
				font-weight: 800;
				transform: rotate3d(1, 1, 1, 5deg);
				transition: all 0.3s ease;
			}
		</style>
	<?php
	}

	?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<?php do_action('storefront_before_site'); ?>

	<div id="page" class="hfeed site">
		<?php do_action('storefront_before_header'); ?>

		<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

			<?php
			/**
			 * Functions hooked into storefront_header action
			 *
			 * @hooked storefront_header_container                 - 0
			 * @hooked storefront_skip_links                       - 5
			 * @hooked storefront_social_icons                     - 10
			 * @hooked storefront_site_branding                    - 20
			 * @hooked storefront_secondary_navigation             - 30
			 * @hooked storefront_product_search                   - 40
			 * @hooked storefront_header_container_close           - 41
			 * @hooked storefront_primary_navigation_wrapper       - 42
			 * @hooked storefront_primary_navigation               - 50
			 * @hooked storefront_header_cart                      - 60
			 * @hooked storefront_primary_navigation_wrapper_close - 68
			 */
			do_action('storefront_header');
			?>


		</header><!-- #masthead -->

		<?php
		/**
		 * Functions hooked in to storefront_before_content
		 *
		 * @hooked storefront_header_widget_region - 10
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action('storefront_before_content');
		?>

		<div id="content" class="site-content" tabindex="-1">
			<div class="col-full">

				<?php
				do_action('storefront_content_top');
