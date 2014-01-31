<?php
/**
 * Plugin Name: Bean Portfolio
 * Plugin URI: http://themebeans.com/plugin/portfolio-wordpress-plugin/?ref=plugin_bean_portfolios
 * Description: Enables a portfolio post type for use in our Bean WordPress Themes
 * Version: 1.3.1
 * Author: Rich Tabor / ThemeBeans
 * Author URI: http://themebeans.com/?ref=plugin_bean_portfolios
 *
 *
 * @package Bean Plugins
 * @subpackage BeanPortfolio
 * @author ThemeBeans
 * @since BeanPortfolio 1.0
 */




/*===================================================================*/
/* PLUGIN UPDATER
/*===================================================================*/
//CONSTANTS
define( 'BEANPORTFOLIO_EDD_TB_URL', 'http://themebeans.com' );
define( 'BEANPORTFOLIO_EDD_TB_NAME', 'Bean Portfolio' );

//INCLUDE UPDATER
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/updates/EDD_SL_Plugin_Updater.php' );
}

include( dirname( __FILE__ ) . '/updates/EDD_SL_Setup.php' );

//LICENSE KEY
$license_key = trim( get_option( 'edd_beanportfolio_license_key' ) );

//CURRENT BUILD
$edd_updater = new EDD_SL_Plugin_Updater( BEANPORTFOLIO_EDD_TB_URL, __FILE__, array(
		'version' 	=> '1.3.1',
		'license' 	=> $license_key,
		'item_name' => BEANPORTFOLIO_EDD_TB_NAME,
		'author' 	=> 'ThemeBeans'
	)
);




/*===================================================================*/
/* PLUGIN CLASS
/*===================================================================*/
if ( ! class_exists( 'Bean_Portfolio_Post_Type' ) ) :
class Bean_Portfolio_Post_Type
{
	function __construct()
	{
		// PLUGIN ACTIVATION
		register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );

		// TRANSLATION
		load_plugin_textdomain( 'bean', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// PORTFOLIO THUMBNAILS
		add_theme_support( 'post-thumbnails', array( 'portfolio' ) );

		add_action( 'init', array( &$this, 'portfolio_init' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'display_thumbnail' ), 10, 1 );
		add_action( 'restrict_manage_posts', array( &$this, 'add_taxonomy_filters' ) );
		add_action( 'right_now_content_table_end', array( &$this, 'add_portfolio_counts' ) );
		add_action( 'admin_head', array( &$this, 'custom_post_type_icon' ) );
		add_action( 'admin_menu', array( &$this, 'bean_create_portfolio_sort_page') );
		add_action( 'wp_ajax_portfolio_sort', array( &$this, 'bean_save_portfolio_sorted_order' ) );
		add_filter( 'manage_edit-portfolio_columns', array( &$this, 'add_thumbnail_column'), 10, 1 );
	}




	/*===================================================================*/
	/* FLUSH REWRITE RULE
	/*===================================================================*/
	function plugin_activation()
	{
		$this->portfolio_init();
		flush_rewrite_rules();
	}




	/*===================================================================*/
	/* REGISTER POST TYPE
	/*===================================================================*/
	function portfolio_init()
	{
		// REGISTER THE POST TYPE
		$labels = array(
			'name' 				 => __( 'Portfolio', 'bean' ),
			'singular_name' 	 => __( 'Portfolio Post', 'bean' ),
			'add_new' 			 => __( 'Add New', 'bean' ),
			'add_new_item'		 => __( 'Add New Portfolio', 'bean' ),
			'edit_item' 		 => __( 'Edit Portfolio', 'bean' ),
			'new_item' 			 => __( 'Add New', 'bean' ),
			'view_item' 		 => __( 'View Portfolio', 'bean' ),
			'search_items' 		 => __( 'Search Portfolio', 'bean' ),
			'not_found' 		 => __( 'No portfolio items found', 'bean' ),
			'not_found_in_trash' => __( 'No portfolio items found in trash', 'bean' )
		);

		$args = array(
	    	'labels' 			=> $labels,
	    	'public' 			=> true,
			'supports' 			=> array( 'title', 'editor', 'excerpt', 'thumbnail'),
			'capability_type' 	=> 'post',
			'rewrite' 			=> array("slug" => "portfolio"),
			'menu_position' 	=> 20,
			'has_archive' 		=> true,
			'menu_icon'		    => '',
		);

		$args = apply_filters('bean_args', $args);

		register_post_type( 'portfolio', $args );


		// REGISTER TAGS
		$taxonomy_portfolio_tag_labels = array(
			'name' 							=> __( 'Portfolio Tags', 'bean' ),
			'singular_name' 				=> __( 'Portfolio Tag', 'bean' ),
			'search_items' 					=> __( 'Search Portfolio Tags', 'bean' ),
			'popular_items' 				=> __( 'Popular Portfolio Tags', 'bean' ),
			'all_items' 					=> __( 'All Portfolio Tags', 'bean' ),
			'parent_item' 					=> __( 'Parent Portfolio Tag', 'bean' ),
			'parent_item_colon' 			=> __( 'Parent Portfolio Tag:', 'bean' ),
			'edit_item' 					=> __( 'Edit Portfolio Tag', 'bean' ),
			'update_item' 					=> __( 'Update Portfolio Tag', 'bean' ),
			'add_new_item' 					=> __( 'Add New Portfolio Tag', 'bean' ),
			'new_item_name' 				=> __( 'New Portfolio Tag Name', 'bean' ),
			'separate_items_with_commas' 	=> __( 'Separate portfolio tags with commas', 'bean' ),
			'add_or_remove_items' 			=> __( 'Add or remove portfolio tags', 'bean' ),
			'choose_from_most_used' 		=> __( 'Choose from the most used portfolio tags', 'bean' ),
			'menu_name' 					=> __( 'Tags', 'bean' )
		);

		$taxonomy_portfolio_tag_args = array(
			'labels' => $taxonomy_portfolio_tag_labels,
			'public' => true,
			'show_in_nav_menus' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'portfolio_tag' ),
			'show_admin_column' => false,
			'query_var' => true
		);

		register_taxonomy( 'portfolio_tag', array( 'portfolio' ), $taxonomy_portfolio_tag_args );


		// REGISTER CATEGORIES
	    $taxonomy_portfolio_category_labels = array(
			'name' 							=> __( 'Portfolio Categories', 'bean' ),
			'singular_name' 				=> __( 'Portfolio Category', 'bean' ),
			'search_items' 					=> __( 'Search Portfolio Categories', 'bean' ),
			'popular_items'					=> __( 'Popular Portfolio Categories', 'bean' ),
			'all_items' 					=> __( 'All Portfolio Categories', 'bean' ),
			'parent_item' 					=> __( 'Parent Portfolio Category', 'bean' ),
			'parent_item_colon' 			=> __( 'Parent Portfolio Category:', 'bean' ),
			'edit_item' 					=> __( 'Edit Portfolio Category', 'bean' ),
			'update_item' 					=> __( 'Update Portfolio Category', 'bean' ),
			'add_new_item' 					=> __( 'Add New Portfolio Category', 'bean' ),
			'new_item_name' 				=> __( 'New Portfolio Category Name', 'bean' ),
			'separate_items_with_commas' 	=> __( 'Separate portfolio categories with commas', 'bean' ),
			'add_or_remove_items' 			=> __( 'Add or remove portfolio categories', 'bean' ),
			'choose_from_most_used' 		=> __( 'Choose from the most used portfolio categories', 'bean' ),
			'menu_name' 					=> __( 'Categories', 'bean' ),
	    );

	    $taxonomy_portfolio_category_args = array(
			'labels' 			=> $taxonomy_portfolio_category_labels,
			'public' 			=> true,
			'show_in_nav_menus' => true,
			'show_ui' 			=> true,
			'show_admin_column' => true,
			'show_tagcloud'		=> false,
			'hierarchical' 		=> true,
			'rewrite' 			=> array( 'slug' => 'portfolio_category' ),
			'query_var' 		=> true
	    );

	    register_taxonomy( 'portfolio_category', array( 'portfolio' ), $taxonomy_portfolio_category_args );

	}




	/*===================================================================*/
	/* ADD THUMBNAIL COLUMN
	/*===================================================================*/
	function add_thumbnail_column( $columns )
	{
		$column_thumb = array( 'thumbnail' => __('Thumbnail','bean' ) );
		$columns = array_slice( $columns, 0, 2, true ) + $column_thumb + array_slice( $columns, 1, NULL, true );
		return $columns;
	}

	function display_thumbnail( $column )
	{
		global $post;
		switch ( $column ) {
			case 'thumbnail':
				echo get_the_post_thumbnail( $post->ID, array(35, 35) );
				break;
		}
	}




	/*===================================================================*/
	/* ADD TAXONOMY FILTERS TO THE ADMIN PAGE
	/*===================================================================*/
	function add_taxonomy_filters()
	{
		global $typenow;

		// USE TAXONOMY NAME OR SLUG
		$taxonomies = array( 'portfolio_category', 'portfolio_tag' );

	 	// POST TYPE FOR THE FILTER
		if ( $typenow == 'portfolio' )
		{

			foreach ( $taxonomies as $tax_slug ) {
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}




	/*===================================================================*/
	/* ADD COUNT TO "RIGHT NOW" DASHBOARD WIDGET
	/*===================================================================*/
	function add_portfolio_counts() {
	        if ( ! post_type_exists( 'portfolio' ) )
	        {
	             return;
	        }

	        $num_posts = wp_count_posts( 'portfolio' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Portfolio Item', 'Portfolio Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=portfolio'>$num</a>";
	            $text = "<a href='edit.php?post_type=portfolio'>$text</a>";
	        }
	        echo '<td class="first b b-portfolio">' . $num . '</td>';
	        echo '<td class="t portfolio">' . $text . '</td>';
	        echo '</tr>';

	        if ($num_posts->pending > 0)
	        {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Portfolio Item Pending', 'Portfolio Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=portfolio'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=portfolio'>$text</a>";
	            }
	            echo '<td class="first b b-portfolio">' . $num . '</td>';
	            echo '<td class="t portfolio">' . $text . '</td>';

	            echo '</tr>';
	        }
	}




	/*===================================================================*/
	/* SORTING
	/*===================================================================*/
	function bean_create_portfolio_sort_page()
	{
	    $bean_sort_page = add_submenu_page('edit.php?post_type=portfolio', __('Sort Portfolios', 'bean'), __('Sort', 'bean'), 'edit_posts', basename(__FILE__), array($this, 'bean_portfolio_sort'));

	    add_action('admin_print_styles-' . $bean_sort_page, array($this, 'bean_print_sort_styles')) ;
	    add_action('admin_print_scripts-' . $bean_sort_page , array($this,'bean_print_sort_scripts'));
	}

	//OUTPUT FOR SORTING PAGE
	function bean_portfolio_sort()
	{
	    $portfolios = new WP_Query('post_type=portfolio&posts_per_page=-1&orderby=menu_order&order=ASC'); ?>

	    <div class="wrap">

	        <div id="icon-tools" class="icon32"></div>

	        <h2><?php _e('Sort Portfolio', 'bean'); ?></h2>

	        <p><?php _e('Click, drag, re-order & repeat as necessary. The item at the top of the list will display first.', 'bean'); ?></p>

	        <ul id="portfolio_list">

	            <?php while( $portfolios->have_posts() ) : $portfolios->the_post();

	                if( get_post_status() == 'publish' ) { ?>

	                    <li id="<?php the_id(); ?>" class="menu-item">

	                        <dl class="menu-item-bar">

	                            <dt class="menu-item-handle">

	                                <span class="menu-item-title"><?php the_title(); ?></span>

	                            </dt><!-- END .menu-item-handle -->

	                        </dl><!-- END .menu-item-bar -->

	                        <ul class="menu-item-transport"></ul>

	                    </li><!-- END .menu-item -->

	            <?php } endwhile; wp_reset_postdata(); ?>

	        </ul><!-- END #portfolio_list -->

    	</div><!-- END .wrap -->

	<?php }

	//ORDER
	function bean_save_portfolio_sorted_order()
	{
	    global $wpdb;
	    $order = explode(',', $_POST['order']);
	    $counter = 0;
	    foreach($order as $portfolio_id) {
	        $wpdb->update($wpdb->posts, array('menu_order' => $counter), array('ID' => $portfolio_id));
	        $counter++;
	    }
	    die(1);
	}

	// SCRIPTS
	function bean_print_sort_scripts()
	{
	    wp_enqueue_script('jquery-ui-sortable');
	    wp_enqueue_script( 'bean_portfolio_sort', plugins_url( '/js/bean-sort.js', __FILE__ ), array('jquery') );
	}

	// SORTER STYLES
	function bean_print_sort_styles()
	{
	    wp_enqueue_style ('nav-menu');
	}




	/*===================================================================*/
	/* CUSTOM ICON FOR POST TYPE
	/*===================================================================*/
	function custom_post_type_icon()
	{ ?>
		<style type="text/css" media="screen">
			#adminmenu #menu-posts-portfolio div.wp-menu-image:before { content: '\f322'; }
		</style>
	<?php
	}
} //END class Bean_Portfolio_Post_Type

new Bean_Portfolio_Post_Type;

endif;




/*===================================================================*/
/* ADMIN PAGE FOR LICENSE ENTRY
/*===================================================================*/
//MENU LINK
function bean_portfolio_admin_menu() {
	add_options_page(
		__('Bean Portfolio', 'bean'), __('Bean Portfolio', 'bean'), 'manage_options', 'bean_portfolio', 'bean_portfolio_admin_page');
}
add_action('admin_menu', 'bean_portfolio_admin_menu');

//PRINT PAGE
function bean_portfolio_admin_page()
{
	$license = get_option( 'edd_beanportfolio_license_key' );
	$status = get_option( 'edd_beanportfolio_license_status' );
	?>
		<div class="wrap">
		<h2><?php echo esc_html__('Bean Portfolio Plugin', 'bean'); ?></h2>
		<p>Display and maintain your online portfolio with our Bean Portfolio Plugin. You maintain complete control of your portfolio posts – even if you switch themes.  If you like this plugin, consider checking out our other <a href="http://themebeans.com/plugins/?ref=bean_portfolio" target="blank">Free Plugins</a> and our <a href="http://themebeans.com/themes/?ref=bean_portfolio" target="blank">Premium WordPress Themes</a>. Cheers!</p><br />

		<h4 style="font-size: 15px; font-weight: 600; color: #222; margin-bottom: 10px;"><?php _e('Activate License'); ?></h4>
		<p>Enter the license key <code style="padding: 1px 5px 2px; background-color: #FFF; border-radius: 2px; font-weight: bold; font-family: 'Open Sans',sans-serif;">BEANPORTFOLIO</code>, hit Save, then Activate, to turn on the plugin updater. You'll then be able to update this plugin from your Plugins Dashboard when future updates are available.</p>

		<form method="post" action="options.php">
			<?php settings_fields('edd_beanportfolio_license'); ?>
			<input id="edd_beanportfolio_license_key" name="edd_beanportfolio_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
				<?php if( $status !== false && $status == 'valid' ) { ?>
					<?php wp_nonce_field( 'edd_beanportfolio_nonce', 'edd_beanportfolio_nonce' ); ?>
					<input type="submit" class="button-secondary" name="edd_beanportfolio_license_deactivate" style="outline: none!important;" value="<?php _e('Deactivate License'); ?>"/>
					<span style="color: #7AD03A;"><?php _e('&nbsp;&nbsp;Good to go!'); ?></span>
				<?php } else {
					wp_nonce_field( 'edd_beanportfolio_nonce', 'edd_beanportfolio_nonce' ); ?>
					<input type="submit" name="submit" id="submit" class="button button-secondary" value="Save License Key">
					<input type="submit" class="button-secondary" name="edd_beanportfolio_license_activate" style="outline: none!important;" value="<?php _e('Activate License'); ?>"/>
					<span style="color: #DD3D36;"><?php _e('&nbsp;&nbsp;Inactive'); ?></span>
				<?php } ?>
		</form>
    </div>
    <?php
} //END function bean_portfolio_admin_page()