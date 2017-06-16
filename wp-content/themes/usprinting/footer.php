<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>

		</div><!-- #content -->

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="wrap">
				<?php
				get_template_part( 'template-parts/footer/footer', 'widgets' );

				if ( has_nav_menu( 'social' ) ) : ?>
					<nav class="social-navigation" role="navigation" aria-label="<?php _e( 'Footer Social Links Menu', 'twentyseventeen' ); ?>">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'social',
								'menu_class'     => 'social-links-menu',
								'depth'          => 1,
								'link_before'    => '<span class="screen-reader-text">',
								'link_after'     => '</span>' . twentyseventeen_get_svg( array( 'icon' => 'chain' ) ),
							) );
						?>
					</nav><!-- .social-navigation -->
				<?php endif;

				
				?>
			</div><!-- .wrap -->
		</footer><!-- #colophon -->
		<?php get_template_part( 'template-parts/footer/site', 'info' ); ?>
	</div><!-- .site-content-contain -->
</div><!-- #page -->

<script>
	function format(input, format, sep) {
    var output = "";
    var idx = 0;
    for (var i = 0; i < format.length && idx < input.length; i++) {
        output += input.substr(idx, format[i]);
        if (idx + format[i] < input.length) output += sep;
        idx += format[i];
    }

    output += input.substr(idx);

    return output;
}
jQuery('.number-format').keyup(function() {
    var foo = jQuery(this).val().replace(/-/g, ""); // remove hyphens
    // You may want to remove all non-digits here
    // var foo = $(this).val().replace(/\D/g, "");

    if (foo.length > 0) {
        foo = format(foo, [3, 3], "-");
    }
  
    
    jQuery(this).val(foo);
});


// script for product search form
var $searchform = jQuery('.aws-search-form');

jQuery(document).ready(function(){
    $searchform.addClass('show-hide-search');
    jQuery('.my-search-icon').click(function(){
	$searchform.slideToggle('slow',function(){
	$searchform.toggleClass('show-hide-search');
    });
   });
});
</script>

<?php wp_footer(); ?>

</body>
</html>
