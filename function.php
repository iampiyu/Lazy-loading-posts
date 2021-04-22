<!--  Add this code in function.php -->


<?php /**
 * Enqueue scripts and styles.  First Enqueue your JS file here.. and add Ajax-Object.
 * 
 */
// (This is most imp step to localize ajax otherwise it wil not initialize your AJAX call)
function a2n_adm_scripts()
{
	wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/custom.js?ca=' . date('Y-m-d-g-i'), array(), '', true);
	wp_localize_script('custom-js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'template_url' => get_template_directory_uri()));
}
add_action('wp_enqueue_scripts', 'a2n_adm_scripts');




?>
<?php
// Load More Posts on scroll of Reflisting  
add_action('wp_ajax_load_moreblog', 'load_moreblog_function');
add_action('wp_ajax_nopriv_load_moreblog', 'load_moreblog_function');

function load_moreblog_function()
{
	$lazyloaded_category = array();
	$count = 0;

	$blog_posts = array(
		'post_type'        => 'referanser', // Your CPT/Posts name
		'posts_per_page' => 3,
		'post_status' => 'publish',
		'offset' => $_POST['offset'],
		'orderby' => 'date',
		'order' => 'DESC'
		// 'orderby' => 'menu_order',
		// 'order'   => 'ASC',
	);


	//print_r($blog_posts);
	//$page_data = get_posts( array( 'post_type' => 'post','orderby' => 'post_date','order' => 'DESC','numberposts' => -1));
	$blog_args = new WP_Query($blog_posts);
	//print_r($blog_args);
	if ($blog_args->have_posts()) :
		while ($blog_args->have_posts()) : $blog_args->the_post();

			$image = get_the_post_thumbnail_url(get_the_ID(), 'full');
			$title = get_the_title();
			$linktdetail = get_the_permalink();

			$button_text = get_field('button_text');
			$excerpt_in = get_the_excerpt();
			if (has_excerpt()) {
				// $words = explode(" ", $string);
				// $finalStr = implode(" ", array_splice($words, 0, 15));
				// if (strlen($string) > 15) {
				// 	$excerpt = $finalStr . "...";
				// } else {
				// 	$excerpt = $finalStr;
				// }

				$excerpt = strip_tags($excerpt_in);
				if (strlen($excerpt) > 103) {
					// truncate string
					$stringCut = substr($excerpt, 0, 103);
					$endPoint = strrpos($stringCut, ' ');
					//if the excerpt doesn't contain any space then it will cut without word basis.
					$excerpt = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
					$excerpt .= '...';
				}
			} else {
				$excerpt = " ";
			}


			$lazyloaded_category[$count]['title'] = $title;
			$lazyloaded_category[$count]['linktdetail'] = $linktdetail;
			$lazyloaded_category[$count]['button_text'] = $button_text;
			// $lazyloaded_category[$count]['image'] = $image;

			$lazyloaded_category[$count]['excerpt'] = $excerpt;

			if ($image) {
				$lazyloaded_category[$count]['image'] = $image;
			} else {
				$placeholder = get_template_directory_uri() . '/images/placeholder.png';
				$lazyloaded_category[$count]['image'] =  $placeholder;
			}

			$cat_lists = wp_get_post_terms(get_the_ID(), 'category', array("fields" => "names"));

			$cat_string = implode(" ", $cat_lists);
			$lazyloaded_category[$count]['category'] = $cat_string;

			$count++;

		endwhile;
		$offset = $_POST['offset'] + $count;
		wp_reset_postdata();


	endif;
	wp_reset_query();
	$lazyloaded_category_data = array('data' => $lazyloaded_category, 'offset' => $offset);
	echo json_encode($lazyloaded_category_data);
	wp_die();
}

?>