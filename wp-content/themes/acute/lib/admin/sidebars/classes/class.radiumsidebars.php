<?php
/**
 * Radium Sidebars Base Class
 *
 * All functionality pertaining to core functionality of the Radium Sidebars Manager.
 *
 * @package WordPress
 * @subpackage Radium Sidebars
 * @author RadiumThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $token
 * var $prefix
 * var $conditions
 *
 * - __construct()
 * - init()
 * - register_post_type_columns()
 * - register_post_type()
 * - register_custom_columns()
 * - register_custom_column_headings()
 * - meta_box_setup()
 * - meta_box_content()
 * - meta_box_save()
 * - description_meta_box()
 * - enter_title_here()
 * - update_messages()
 * - get_registered_sidebars()
 * - register_custom_sidebars()
 * - init_sidebar_replacement()
 * - replace_sidebars()
 * - find_best_sidebars()
 * - enqueue_styles()
 * - add_post_column_headings()
 * - add_post_column_data()
 * - enable_custom_post_sidebars()
 * - multidimensional_search()
 *
 * - activation()
 * - register_plugin_version()
 */
class Radium_Sidebars {
	var $token;
	var $prefix;
	var $conditions;
	
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct () {
		$this->version = '1.1.3';

		$this->token = 'sidebar';
		$this->prefix = 'radium_sidebar_';

		$this->conditions = new Radium_Conditions();
		$this->conditions->token = $this->token;
	} // End __construct()
	
	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init () {

		add_action( 'init', array( &$this, 'register_post_type' ), 20 );
		add_action( 'admin_menu', array( &$this, 'meta_box_setup' ), 20 );
		add_action( 'save_post', array( &$this, 'meta_box_save' ) );
		add_filter( 'enter_title_here', array( &$this, 'enter_title_here' ) );
		add_filter( 'post_updated_messages', array( &$this, 'update_messages' ) );
		add_action( 'widgets_init', array( &$this, 'register_custom_sidebars' ) );
		add_action( 'get_header', array( &$this, 'init_sidebar_replacement' ) );
 		
		// By default, add post type support for sidebars to the "post" post type.
		add_post_type_support( 'post', 'radiumsidebars' );
		
		add_action( 'admin_head', array( &$this, 'register_post_type_columns' ) );
		
		add_action( 'wp_ajax_radiumsidebars-post-enable', array( &$this, 'enable_custom_post_sidebars' ) );
		
 		if ( is_admin() ) {
			global $pagenow;
			
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_styles' ), 12 );
			
			if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == $this->token ) {
				add_filter( 'manage_edit-' . $this->token . '_columns', array( &$this, 'register_custom_column_headings' ), 10, 1 );
				add_action( 'manage_posts_custom_column', array( &$this, 'register_custom_columns' ), 10, 2 );
			}
						
		}
		
	} // End init()
	
	/**
	 * register_post_type_columns function.
	 * 
	 * @access public
	 * @return void
	 */
	public function register_post_type_columns () {
		$post_type = get_post_type();
					
		if ( $post_type != '' && post_type_supports( $post_type, 'bean' ) ) {
			add_filter( 'manage_edit-' . $post_type . '_columns', array( &$this, 'add_post_column_headings' ), 10, 1 );
			add_action( 'manage_posts_custom_column', array( &$this, 'add_post_column_data' ), 10, 2 );
			add_action( 'manage_pages_custom_column', array( &$this, 'add_post_column_data' ), 10, 2 );
		}
	} // End register_post_type_columns()
	
	/**
	 * register_post_type function.
	 * 
	 * @access public
	 * @return void
	 */
	public function register_post_type () {	
			
		$page 		= 'themes.php';
		$singular 	= __( 'Widget Area', 'bean' );
		$plural 	= __( 'Widget Areas', 'bean' );
		$rewrite 	= array( 'slug' => 'sidebars' );
		$supports 	= array( 'title', 'excerpt' );
		
		if ( $rewrite == '' ) { $rewrite = $this->token; }
		
		$labels = array(
			'name' 					=> _x( 'Widget Areas', 'post type general name', 'bean' ),
			'singular_name' 		=> _x( 'Widget Area', 'post type singular name', 'bean' ),
			'add_new' 				=> _x( 'Add New', 'Sidebar Area', 'bean' ),
			'add_new_item' 			=> sprintf( __( 'Add New %s', 'bean' ), $singular ),
			'edit_item' 			=> sprintf( __( 'Edit %s', 'bean' ), $singular ),
			'new_item' 				=> sprintf( __( 'New %s', 'bean' ), $singular ),
			'all_items' 			=> sprintf( __( 'Widget Areas', 'bean' ), $plural ),
			'view_item' 			=> sprintf( __( 'View %s', 'bean' ), $singular ),
			'search_items' 			=> sprintf( __( 'Search %a', 'bean' ), $plural ),
			'not_found' 			=> sprintf( __( 'No %s Found', 'bean' ), $plural ),
			'not_found_in_trash' 	=> sprintf( __( 'No %s Found In Trash', 'bean' ), $plural ),
			'parent_item_colon' 	=> '',
			'menu_name' 			=> $plural
		);
		
		$args = array(
			'labels' 				=> $labels,
			'public' 				=> false,
			'publicly_queryable' 	=> true,
			'show_ui' 				=> true,
			'show_in_nav_menus' 	=> false, 
			'show_in_admin_bar' 	=> false, 
			'show_in_menu' 			=> $page,
			'query_var'	 			=> true,
			'rewrite' 				=> $rewrite,
			'capability_type' 		=> 'post',
			'has_archive' 			=> false,
			'hierarchical' 			=> false,
			'menu_position' 		=> null,
			'supports' 				=> $supports
		);
		
		register_post_type( $this->token, $args );
		
	} // End register_post_type()
	
	/**
	 * register_custom_columns function.
	 * 
	 * @access public
	 * @param string $column_name
	 * @param int $id
	 * @return void
	 */
	public function register_custom_columns ( $column_name, $id ) {
		global $wpdb, $post;
		
		$meta = get_post_custom( $id );
		$sidebars = $this->get_registered_sidebars();

		$this->conditions->setup_default_conditions_reference();

		switch ( $column_name ) {
			
			case 'sidebar_to_replace':
				$value = '';

				if ( isset( $meta['_sidebar_to_replace'] ) && ( $meta['_sidebar_to_replace'][0] != '' ) ) {
					$value = $meta['_sidebar_to_replace'][0];
					
					if ( isset( $sidebars[$value] ) ) {
						$value = $sidebars[$value]['name'];
					} else {
						$value .= '<br /><strong>' . __( '(Not in use by current theme)', 'bean' ) . '</strong>';
					}
				}

				echo $value;
			break;
			
			case 'condition':
				$value = '';

				if ( isset( $meta['_condition'] ) && ( $meta['_condition'][0] != '' ) ) {
					foreach ( $meta['_condition'] as $k => $v ) {
						$value .= $this->multidimensional_search( $v, $this->conditions->conditions_reference ) . '<br />' . "\n";
					}
				}

				echo $value;
			break;

			default:
			break;
		
		}
	} // End register_custom_columns()
	
	/**
	 * register_custom_column_headings function.
	 * 
	 * @access public
	 * @param array $defaults
	 * @return void
	 */
	public function register_custom_column_headings ( $defaults ) {
		$this->conditions->setup_default_conditions_reference();
		
		$new_columns = array( 'sidebar_to_replace' => __( 'Sidebar To Replace', 'bean' ), 'condition' => __( 'Condition(s)', 'bean' ) );
		
		$last_item = '';

		if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }

		if ( count( $defaults ) > 2 ) { 
			$last_item = array_slice( $defaults, -1 );

			array_pop( $defaults );
		}
		$defaults = array_merge( $defaults, $new_columns );
		
		if ( $last_item != '' ) {
			foreach ( $last_item as $k => $v ) {
				$defaults[$k] = $v;
				break;
			}
		}
		
		return $defaults;
	} // End register_custom_column_headings()
	
	/**
	 * meta_box_setup function.
	 * 
	 * @access public
	 * @return void
	 */
	public function meta_box_setup () {		
		add_meta_box( 'sidebar-to-replace', __( 'Sidebar To Replace', 'bean' ), array( &$this, 'meta_box_content' ), $this->token, 'side', 'low' );
		
		// Remove "Custom Settings" meta box.
		remove_meta_box( 'radiumthemes-settings', 'sidebar', 'normal' );

		// Customise the "Excerpt" meta box for the sidebars.
		remove_meta_box( 'postexcerpt', $this->token, 'normal' );
		add_meta_box( 'sidebar-description', __( 'Description', 'bean' ), array( &$this, 'description_meta_box' ), $this->token, 'normal', 'core' );
	} // End meta_box_setup()
	
	/**
	 * meta_box_content function.
	 * 
	 * @access public
	 * @return void
	 */
	public function meta_box_content () {
		global $post_id;
		
		$sidebars = $this->get_registered_sidebars();
		
		$selected_sidebar = get_post_meta( $post_id, '_sidebar_to_replace', true );
		
		$html = '';
		
		$html .= '<input type="hidden" name="radium_' . $this->token . '_noonce" id="radium_' . $this->token . '_noonce" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		if ( count( $sidebars ) > 0 ) {
			$html .= '<select name="sidebar_to_replace" class="widefat">' . "\n";
				foreach ( $sidebars as $k => $v ) {
					$html .= '<option value="' . $v['id'] . '"' . selected( $selected_sidebar, $v['id'], false ) . '>' . $v['name'] . '</option>' . "\n";
				}
			$html .= '</select>' . "\n";
		} else {
			$html .= '<p>' . __( 'No sidebars are available with this theme.', 'bean' ) . '</p>';
		}
		
		echo $html;	
		
	} // End meta_box_content()
	
	/**
	 * meta_box_save function.
	 * 
	 * @access public
	 * @param int $post_id
	 * @return void
	 */
	public function meta_box_save ( $post_id ) {
		global $post, $messages;
		
		// Verify
		if ( ( get_post_type() != $this->token ) || ! wp_verify_nonce( $_POST['radium_' . $this->token . '_noonce'], plugin_basename(__FILE__) ) ) {  
			return $post_id;  
		}
		  
		if ( 'page' == $_POST['post_type'] ) {  
			if ( ! current_user_can( 'edit_page', $post_id ) ) { 
				return $post_id;
			}
		} else {  
			if ( ! current_user_can( 'edit_post', $post_id ) ) { 
				return $post_id;
			}
		}
		
		$fields = array( 'sidebar_to_replace' );
		
		foreach ( $fields as $f ) {
		
			${$f} = strip_tags(trim($_POST[$f]));
			
			if ( get_post_meta( $post_id, '_' . $f ) == '' ) { 
				add_post_meta( $post_id, '_' . $f, ${$f}, true ); 
			} elseif( ${$f} != get_post_meta( $post_id, '_' . $f, true ) ) { 
				update_post_meta( $post_id, '_' . $f, ${$f} );
			} elseif ( ${$f} == '' ) { 
				delete_post_meta( $post_id, '_' . $f, get_post_meta( $post_id, '_' . $f, true ) );
			}	
		}
	} // End meta_box_save()
	
	/**
	 * description_meta_box function.
	 *
	 * @param object $post
	 */
	public function description_meta_box ( $post ) {
	?>
	<label class="screen-reader-text" for="excerpt"><?php _e( 'Description', 'bean' ); ?></label><textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
	<p><?php printf( __( 'Add an optional description, to be displayed when adding widgets to this widget area on the %sWidgets%s screen.', 'bean' ), '<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">', '</a>' ); ?></p>
	<?php
	} // End description_meta_box()

	/**
	 * enter_title_here function.
	 * 
	 * @access public
	 * @param string $title
	 * @return void
	 */
	public function enter_title_here ( $title ) {
		if ( get_post_type() == $this->token ) {
			$title = __( 'Enter widget area name here', 'bean' );
		}
		return $title;
	} // End enter_title_here()
	
	/**
	 * update_messages function.
	 * 
	 * @access public
	 * @param array $messages
	 * @return void
	 */
	public function update_messages ( $messages ) {
		if ( get_post_type() != $this->token ) {
			return $messages;
		}
		
		$messages[$this->token][1] = __( 'Widget Area updated.', 'bean' );			
	
		return $messages;
	} // End update_messages()
	
	/**
	 * get_registered_sidebars function.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_registered_sidebars () {
		global $wp_registered_sidebars;
		
		$sidebars = array();
		$to_ignore = array();
		
		$custom_sidebars = get_posts( 'post_type=sidebar&numberposts=-1' );
		if ( ! is_wp_error( $custom_sidebars ) && count( $custom_sidebars ) > 0 ) {
			foreach ( $custom_sidebars as $k => $v ) {
				$to_ignore[] = $v->post_name;
			}
		}

		if ( is_array( $wp_registered_sidebars ) && ( count( $wp_registered_sidebars ) > 0 ) ) {
			foreach ( $wp_registered_sidebars as $k => $v ) {
				if ( ! stristr( $v['id'], $this->prefix ) && ! stristr( $v['id'], 'radium_sbm_' ) && ! in_array( $v['id'], $to_ignore ) ) {
					$sidebars[$k] = $v;
				}
			}
		}
		
		return $sidebars;
	} // End get_registered_sidebars()
	
	/**
	 * register_custom_sidebars function.
	 * 
	 * @access public
	 * @return void
	 */
	public function register_custom_sidebars () {
		$sidebars = get_posts( array( 'post_type' => 'sidebar', 'posts_per_page' => -1 ) );
		
		if ( count( $sidebars ) > 0 ) {
			foreach ( $sidebars as $k => $v ) {
				$sidebar_id = $v->post_name;
				// $sidebar_id = $this->prefix . $v->ID;
				register_sidebar( array( 'name' => $v->post_title, 'id' => $sidebar_id, 'description' => $v->post_excerpt ) );
			}
		}
	} // End register_custom_sidebars()
	
	/**
	 * init_sidebar_replacement function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init_sidebar_replacement () {
		add_filter( 'sidebars_widgets', array( &$this, 'replace_sidebars' ) );
	} // End init_sidebar_replacement()
	
	/**
	 * replace_sidebars function.
	 * 
	 * @access public
	 * @param array $sidebars_widgets
	 * @return void
	 */
	public function replace_sidebars ( $sidebars_widgets ) {
		if ( is_admin() ) {
	 		return $sidebars_widgets;
	 	}

		// Determine the conditions to construct the query.
		$conditions = $this->conditions->conditions;

		if ( ! isset( $this->conditions->conditions ) || count( $this->conditions->conditions ) <= 0 ) {
			return $sidebars_widgets;
		}
		
	 	global $radium_custom_sidebar_data;
	 	
	 	if ( ! isset( $radium_custom_sidebar_data ) ) {
		 	
		 	$conditions_str = join( ', ', $conditions );
		 	
		 	$args = array(
		 		'post_type' => $this->token, 
		 		'posts_per_page' => -1
		 	);
		 	
		 	$meta_query = array(
		 					'key' => '_sidebar_to_replace', 
		 					'compare' => '!=', 
		 					'value' => ''
		 					);
		 	
		 	$args['meta_query'][] = $meta_query;
		 	
		 	$meta_query = array(
		 					'key' => '_condition', 
		 					'compare' => 'IN', 
		 					'value' => $conditions
		 					);
		 	
		 	$args['meta_query'][] = $meta_query;

		 	$sidebars = get_posts( $args );

		 	if ( count( $sidebars ) > 0 ) {
		 		foreach ( $sidebars as $k => $v ) {
		 			$to_replace = get_post_meta( $v->ID, '_sidebar_to_replace', true );
		 			$sidebars[$k]->to_replace = $to_replace;
		 			
		 			$conditions = get_post_meta( $v->ID, '_condition', false );
		 			
		 			$sidebars[$k]->conditions = array();
		 			
		 			// Remove any irrelevant conditions from the array.
		 			if ( is_array( $conditions ) ) {
		 				foreach ( $conditions as $i => $j ) {
		 					if ( in_array( $j, $this->conditions->conditions ) ) {
		 						$sidebars[$k]->conditions[] = $j;
		 					}
		 				}
		 			}
		 			
		 		}
		 	}
		 	
		 	$radium_custom_sidebar_data = $sidebars;	
	 	}
	 		 	
		// Make sure only the most appropriate sidebars are kept.
		// $radium_custom_sidebar_data = $this->remove_unwanted_sidebars( $radium_custom_sidebar_data );
		$radium_custom_sidebar_data = $this->find_best_sidebars( $radium_custom_sidebar_data );	 	
	 	
	 	if ( count( $radium_custom_sidebar_data ) > 0 ) {
	 		foreach ( $radium_custom_sidebar_data as $k => $v ) {
	 			$sidebar_id = $v->post_name;
				// $sidebar_id = $this->prefix . $v->ID;
	 			if ( isset( $sidebars_widgets[$sidebar_id] ) && isset( $v->to_replace ) && $v->to_replace != '' ) {
				 	$widgets = $sidebars_widgets[$sidebar_id];
					unset( $sidebars_widgets[$sidebar_id] );
					$sidebars_widgets[$v->to_replace] = $widgets;
				}
	 		}
	 	}

		return $sidebars_widgets;
	} // End replace_sidebars()
	
	/**
	 * find_best_sidebars function.
	 * 
	 * @access public
	 * @param array $sidebars
	 * @return array $sorted_sidebars
	 */
	public function find_best_sidebars ( $sidebars ) {
		$sorted_sidebars = array();
		
		if ( ! isset( $this->conditions->conditions ) || count( $this->conditions->conditions ) <= 0 ) {
			return $sidebars;
		}
		
		// Keep track of each sidebar we'd like to replace widgets for.
		foreach ( $sidebars as $k => $v ) {
			if ( isset( $v->to_replace ) && ( $v->to_replace != '' ) && ! isset( $sorted_sidebars[$v->to_replace] ) ) {
				$sorted_sidebars[$v->to_replace] = '';
			}
		}
		
		foreach ( $sidebars as $k => $v ) {
			if ( isset( $sorted_sidebars[$v->to_replace] ) && ( $sorted_sidebars[$v->to_replace] == '' ) ) {
				$sorted_sidebars[$v->to_replace] = $v;
			} else {
				continue;
			}
		}
		
		return $sorted_sidebars;
	} // End find_best_sidebars()

	/**
	 * enqueue_styles function.
	 * 
	 * @access public
	 * @return void
	 */
	public function enqueue_styles () {
		global $pagenow;
		
		if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
			if ( get_post_type() != $this->token ) { return; }
			wp_enqueue_style( 'jquery-ui-tabs' );
			
			wp_register_style( $this->token . '-admin', BEAN_ADMIN_URL . '/sidebars/assets/css/sidebars_admin.css', array(), '1.0.0' );
			
			wp_enqueue_style( $this->token . '-admin' );
			
			wp_dequeue_style( 'jquery-ui-datepicker' );
		}
		
		if ( in_array( $pagenow, array( 'edit.php' ) ) ) {
			wp_register_style( $this->token . '-admin-posts', BEAN_ADMIN_URL . '/sidebars/assets/css/sidebars_admin_posts.css', array(), '1.0.0' );
			
			wp_enqueue_style( $this->token . '-admin-posts' );
		}
	} // End enqueue_styles()
	
	/**
	 * add_post_column_headings function.
	 * 
	 * @access public
	 * @param array $defaults
	 * @return array $new_columns
	 */
	public function add_post_column_headings ( $defaults ) {
		$defaults['radiumsidebars_enable'] = __( 'Custom Sidebars', 'bean' );
		return $defaults;
	} // End add_post_column_headings()
	
	/**
	 * add_post_column_data function.
	 * 
	 * @access public
	 * @param string $column_name
	 * @param int $id
	 * @return void
	 */
	public function add_post_column_data ( $column_name, $id ) {
		global $wpdb, $post;
		
		$meta = get_post_custom( $id );
		
		switch ( $column_name ) {
			
			case 'radiumsidebars_enable':
				$image = 'success-off';
				$value = '';
				$class = 'custom-sidebars-disabled';
				
				if ( isset( $meta['_enable_sidebar'] ) && ( $meta['_enable_sidebar'][0] != '' ) && ( $meta['_enable_sidebar'][0] == 'yes' ) ) {
					$image = 'success';
					$class = 'custom-sidebars-enabled';
				}
				
				$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=radiumsidebars-post-enable&post_id=' . $post->ID ), 'radiumsidebars-post-enable' );
				
				$value = '<span class="' . esc_attr( $class ) . '"><a href="' . esc_url( $url ) . '"><img src="' . $this->assets_url . '/images/' . $image . '.png" /></a></span>';
				
				echo $value;
			break;

			default:
			break;
		
		}
	} // End add_post_column_data()
	
	/**
	 * enable_custom_post_sidebars function.
	 * 
	 * @access public
	 * @return void
	 */
	public function enable_custom_post_sidebars () {
		if( ! is_admin() ) die;

		if( ! current_user_can( 'edit_posts' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.', 'bean' ) );
		
		if( ! check_admin_referer( 'radiumsidebars-post-enable' ) ) wp_die( __( 'You have taken too long. Please go back and retry.', 'bean' ) );
		
		$post_id = isset( $_GET['post_id'] ) && (int)$_GET['post_id'] ? (int)$_GET['post_id'] : '';
		
		if( ! $post_id ) die;
		
		$post = get_post( $post_id );
		if( ! $post ) die;
		
		$meta = get_post_meta( $post->ID, '_enable_sidebar', true );
		
		if ( $meta == 'yes' ) { 
			update_post_meta($post->ID, '_enable_sidebar', 'no' );
		} else {
			update_post_meta($post->ID, '_enable_sidebar', 'yes' );
		}
		
		$sendback = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );
		wp_safe_redirect( $sendback );
	} // End enable_custom_post_sidebars()
	
	/**
	 * multidimensional_search function.
	 * 
	 * @access public
	 * @param string $needle
	 * @param array $haystack
	 * @return string $m
	 */
	public function multidimensional_search ( $needle, $haystack ) {
		if (empty($needle) || empty($haystack)) {
            return false;
        }
        
        foreach ($haystack as $key => $value) {
            $exists = 0;
        	foreach ( (array)$needle as $nkey => $nvalue) {
                if ( ! empty( $value[$nvalue] ) && is_array( $value[$nvalue] ) ) {
                    return $value[$nvalue]['label'];
                }
            }
        }
        
        return false;
	} // End multidimensional_search()

	/**
	 * activation function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * register_plugin_version function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( 'bean' . '-version', $this->version );
		}
	} // End register_plugin_version()
} // End Class
?>
