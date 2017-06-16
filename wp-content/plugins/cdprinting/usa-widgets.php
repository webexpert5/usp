<?php // Creating the widget 
class upb_widget extends WP_Widget {

function __construct() {
	parent::__construct(
	// Base ID of your widget
	'upb_widget', 

	// Widget name will appear in UI
	__('Homepage Sidebar', 'upb_widget_domain'), 

	// Widget description
	array( 'description' => __( 'This widget will list all product links in specified categoreis', 'upb_widget_domain' ), ) 
	);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$ids = apply_filters( 'widget_ids', $instance['ids'] );
// before and after widget arguments are defined by themes


// This is where you run the code and display the output
?>
	<?php
	$idArray		=	explode(',',$ids);
	foreach($idArray as $key=>$id){
		if( $term = get_term_by( 'slug', $id, 'product_cat' ) ){
	
			?>
				<h2><?php echo $term->name; ?></h2>
										
				<?php
						//$args = array( 'post_type' => 'product', 'posts_per_page' => 50, 'product_cat' => $id, 'orderby' => 'title' );
					$args = array(
						'post_type'             => 'product',
						'post_status'           => 'publish',
						'ignore_sticky_posts'   => 1,
						'posts_per_page'        => '50',
						'orderby'				=> 'title',
						'order'   => 'ASC',
						'meta_query'            => array(
							array(
								'key'           => '_visibility',
								'value'         => array('catalog', 'visible'),
								'compare'       => 'IN'
							)
						),
						'tax_query'             => array(
							array(
								'taxonomy'      => 'product_cat',
								'field' => 'term_id', //This is optional, as it defaults to 'term_id'
								'terms'         => $term->term_id,
								'operator'      => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.,
								'include_children' =>false
							)
						)
					);
						$loop = new WP_Query( $args );
							?>
								
							<?php
							
						echo "<ul>";
						while ( $loop->have_posts() ) : $loop->the_post(); 
						?>
							<li><a href="<?php echo get_permalink();?>"><?php echo ucwords(strtolower(str_replace(array("SHORT RUN","Short Run","Short run"),"",get_the_title()))); ?></a></li>
						<?php
						
						endwhile;
						echo "</ul>";
						wp_reset_query(); 				
				
				
				
		}		
	}
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'ids' ] ) ) {
$ids = $instance[ 'ids' ];
}
else {
$ids = __( 'New ids', 'upb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'ids' ); ?>"><?php _e( 'Comma Separated List of Category Slug:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'ids' ); ?>" name="<?php echo $this->get_field_name( 'ids' ); ?>" type="text" value="<?php echo esc_attr( $ids ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['ids'] = ( ! empty( $new_instance['ids'] ) ) ? strip_tags( $new_instance['ids'] ) : '';
return $instance;
}
} // Class upb_widget ends here

// Register and load the widget
function upb_load_widget() {
	register_widget( 'upb_widget' );
}
add_action( 'widgets_init', 'upb_load_widget' );
?>