<?php
/**
 * Displays header site branding
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<div class="site-branding header-top">
	<div class="wrap">

		<?php the_custom_logo(); ?>

		<div class="site-branding-text">
			<?php if ( is_front_page() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>

			<?php $description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; ?></p>
				<?php endif; ?>
		</div><!-- .site-branding-text -->

		<div class="topbar-navigation">
                    <div class="leftopside">
                        <div class="login-bar">

                            <ul>
                                <li><?php //echo get_product_search_form(); ?>
                                <div class="search-my-form">
                            <?php echo do_shortcode('[aws_search_form]');?>
                            <span class="my-search-icon"><i class='fa fa-search' aria-hidden='true'></i></span>
                            </div>
                                </li>
                                <li><a href="<?php echo '/my-account'; ?>"><i class="fa fa-user" aria-hidden="true"></i> My Account</a></li>
                                <li><a href="<?php echo '/checkout'; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Checkout</a></li>
                                <?php if(is_user_logged_in()){ ?>
                                <li><a href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-user" aria-hidden="true"></i> Logout</a></li>
                                <?php } else { ?>
								<li><a href="<?php echo wp_login_url(); ?>"><i class="fa fa-user" aria-hidden="true"></i> Login</a></li>
								<?php } ?>

                            </ul>
                        </div>
                            <?php if ( is_active_sidebar( 'sidebar-top' ) ) : ?>
							    <?php dynamic_sidebar( 'sidebar-top' ); ?>
						    <?php endif; ?>
                         </div>
                         <?php  global $woocommerce;
                         $cart_url = $woocommerce->cart->get_cart_url(); ?>
                        <div class="cart-status">
                        	<ul>
	                        	<li><a href="<?php echo $cart_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cart-image.png" alt="cart" /></a></li>
                                <li><a href="<?php echo $cart_url; ?>"><?php echo sprintf ( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() );?></a></li>
                                <li><a href="<?php echo $cart_url; ?>">Total: <?php echo WC()->cart->get_cart_total(); ?></a></li>
                            </ul>
                        </div>
				</div>

				<button class="btn-hamburger js-slideout-toggle">
         <span class="tooltip shake" id="slideout-button">All Products</span>
       </button>

		<?php if ( ( twentyseventeen_is_frontpage() || ( is_home() && is_front_page() ) ) && ! has_nav_menu( 'top' ) ) : ?>
		<a href="#content" class="menu-scroll-down"><?php echo twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ); ?><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'twentyseventeen' ); ?></span></a>
	<?php endif; ?>

	</div><!-- .wrap -->
</div><!-- .site-branding -->
