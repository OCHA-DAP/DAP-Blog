<?php
/**
 * Radium Conditions Class
 *
 * Determine the conditions that apply to each screen within WordPress.
 *
 * @package WordPress
 * @subpackage Radium Sidebars
 * @author Radium Themes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $token
 * var $conditions
 * var $conditions_headings
 * var $conditions_reference
 * var $meta_box_settings
 *
 * - __construct()
 * - get_conditions()
 * - determine_conditions()
 * - setup_default_conditions_reference()
 * - is_hierarchy()
 * - is_taxonomy()
 * - is_post_type_archive()
 * - is_page_template()
 * - meta_box_setup()
 * - meta_box_content()
 * - meta_box_save()
 * - show_advanced_items()
 * - ajax_toggle_advanced_items()
 * - enqueue_scripts()
 */
class Radium_Conditions {
	var $token = '';
	var $conditions = array();
	var $conditions_headings = array();
	var $conditions_reference = array();
	var $meta_box_settings = array();
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct () {
		$this->meta_box_settings['title'] = __( 'Conditions', 'bean' );
		
		if ( is_admin() && get_post_type() == $this->token || ! get_post_type() ) {
			add_action( 'admin_menu', array( &$this, 'meta_box_setup' ), 20 );
			add_action( 'save_post', array( &$this, 'meta_box_save' ) );
		}

		if ( is_admin() ) {
			add_action( 'admin_print_scripts', array( &$this, 'enqueue_scripts' ), 12 );
		}

		add_action( 'get_header', array( &$this, 'get_conditions' ) );

		add_action( 'wp_ajax_radiumsidebars-toggle-advanced-items', array( &$this, 'ajax_toggle_advanced_items' ) );
	} // End __construct()
	
	/**
	 * get_conditions function.
	 * 
	 * @access public
	 * @return void
	 */
	function get_conditions () {
		$this->determine_conditions();

		$this->conditions = apply_filters( 'radium_conditions', $this->conditions );

		$this->conditions = array_reverse( $this->conditions );
	} // End get_conditions()
	
	/**
	 * determine_conditions function.
	 * 
	 * @access public
	 * @return void
	 */
	function determine_conditions () {		
		$this->is_hierarchy();
		$this->is_taxonomy();
		$this->is_post_type_archive();
		$this->is_page_template();			
	} // End determine_conditions()
	
	/**
	 * setup_default_conditions_reference function.
	 *
	 * @description Setup the default conditions and their information, for display when selecting conditions.
	 * @access public
	 * @return void
	 */
	function setup_default_conditions_reference () {
		$conditions = array();
		$conditions_headings = array();
		
		// Pages
		$conditions['pages'] = array();
		
		$pages = get_pages();
		
		if ( count( $pages ) > 0 ) {
		
			$conditions_headings['pages'] = __( 'Pages', 'bean' );
			
			foreach ( $pages as $k => $v ) {
				$token = 'post-' . $v->ID;
				$conditions['pages'][$token] = array(
									'label' => $v->post_title, 
									'description' => sprintf( __( 'The "%s" page', 'bean' ), $v->post_title )
									);
			}

		}
		
		$args = array(
					'show_ui' => true,
					'public' => true,
					'publicly_queryable' => true,
					'_builtin' => false
					);

		$post_types = get_post_types( $args, 'object' );
		
		// Set certain post types that aren't allowed to have custom sidebars.
		$disallowed_types = array( 'slide' );

		// Make the array filterable.
		$disallowed_types = apply_filters( 'radiumsidebars_disallowed_post_types', $disallowed_types );
		
		if ( count( $post_types ) ) {
			foreach ( $post_types as $k => $v ) {
				if ( in_array( $k, $disallowed_types ) ) {
					unset( $post_types[$k] );
				}
			}
		}
		
		// Add per-post support for any post type that supports it.
		
		$args = array(
				'show_ui' => true,
				'public' => true,
				'publicly_queryable' => true,
				'_builtin' => true
				);

		$built_in_post_types = get_post_types( $args, 'object' );
		
		foreach ( $built_in_post_types as $k => $v ) {
			if ( $k == 'post' ) {
				$post_types[$k] = $v;
				break;
			}
		}
		
		foreach ( $post_types as $k => $v ) {
			if ( ! post_type_supports( $k, 'radiumsidebars' ) ) { continue; }
			
			$conditions_headings[$k] = $v->labels->name;
			
			$query_args = array( 'numberposts' => -1, 'post_type' => $k, 'meta_key' => '_enable_sidebar', 'meta_value' => 'yes', 'meta_compare' => '=' );
			
			$posts = get_posts( $query_args );
			
			if ( count( $posts ) > 0 ) {
				foreach ( $posts as $i => $j ) {
					$conditions[$k]['post' . '-' . $j->ID] = array(
										'label' => $j->post_title, 
										'description' => sprintf( __( 'A custom sidebar for "%s"', 'bean' ), esc_attr( $j->post_title ) )
										);
				}					
			}
		}
				
		// Page Templates
		$conditions['templates'] = array();
		
		$page_templates = get_page_templates();
		
		if ( count( $page_templates ) > 0 ) {
		
			$conditions_headings['templates'] = __( 'Page Templates', 'bean' );
			
			foreach ( $page_templates as $k => $v ) {
				$token = str_replace( '.php', '', 'page-template-' . $v );
				$conditions['templates'][$token] = array(
									'label' => $k, 
									'description' => sprintf( __( 'The "%s" page template', 'bean' ), $k )
									);
			}
		}
		
		// Post Type Archives
		$conditions['post_types'] = array();
					
		if ( count( $post_types ) > 0 ) {
		
			$conditions_headings['post_types'] = __( 'Post Types', 'bean' );
			
			foreach ( $post_types as $k => $v ) {
				$token = 'post-type-archive-' . $k;
				
				if ( $v->has_archive ) {
					$conditions['post_types'][$token] = array(
										'label' => sprintf( __( '"%s" Post Type Archive', 'bean' ), $v->labels->name ), 
										'description' => sprintf( __( 'The "%s" post type archive', 'bean' ), $v->labels->name )
										);
				}
			}
			
			foreach ( $post_types as $k => $v ) {
				$token = 'post-type-' . $k;
				$conditions['post_types'][$token] = array(
									'label' => sprintf( __( 'Individual %s', 'bean' ), $v->labels->name ), 
									'description' => sprintf( __( 'Entries in the "%s" post type', 'bean' ), $v->labels->name )
									);
			}

		}
		
		// Taxonomies and Taxonomy Terms
		$conditions['taxonomies'] = array();
		
		$args = array(
					'public' => true, 
					'show_ui' => true
					);
		
		$taxonomies = get_taxonomies( $args, 'objects' );
		
		if ( count( $taxonomies ) > 0 ) {
		
			$conditions_headings['taxonomies'] = __( 'Taxonomy Archives', 'bean' );
			
			foreach ( $taxonomies as $k => $v ) {
				$taxonomy = $v;

				if ( $taxonomy->public == true ) {
					$conditions['taxonomies']['archive-' . $k] = array(
										'label' => $taxonomy->labels->name . ' (' . $k . ')', 
										'description' => sprintf( __( 'The default "%s" archives', 'bean' ), strtolower( $taxonomy->labels->name ) )
										);
					
					// Setup each individual taxonomy's terms as well.
					$conditions_headings['taxonomy-' . $k] = $taxonomy->labels->name;
					$terms = get_terms( $k );
					if ( count( $terms ) > 0 ) {
						$conditions['taxonomy-' . $k] = array();
						foreach ( $terms as $i => $j ) {
							$conditions['taxonomy-' . $k]['term-' . $j->term_id] = array( 'label' => $j->name, 'description' => sprintf( __( 'The %s %s archive', 'bean' ), $j->name, strtolower( $taxonomy->labels->name ) ) );
							if ( $k == 'category' ) {
								$conditions['taxonomy-' . $k]['in-term-' . $j->term_id] = array( 'label' => sprintf( __( 'All posts in "%s"', 'bean' ), $j->name ), 'description' => sprintf( __( 'All posts in the %s %s archive', 'bean' ), $j->name, strtolower( $taxonomy->labels->name ) ) );
							}
						}
					}
				
				}
			}
		}
		
		$conditions_headings['hierarchy'] = __( 'Template Hierarchy', 'bean' );
		
		// Template Hierarchy
		$conditions['hierarchy']['page'] = array(
									'label' => __( 'Pages', 'bean' ),
									'description' => __( 'Displayed on all pages that don\'t have a more specific widget area.', 'bean' )
									);
		
		$conditions['hierarchy']['search'] = array(
									'label' => __( 'Search Results', 'bean' ),
									'description' => __( 'Displayed on search results screens.', 'bean' )
									);
									
		$conditions['hierarchy']['home'] = array(
									'label' => __( 'Default "Your Latest Posts" Screen', 'bean' ),
									'description' => __( 'Displayed on the default "Your Latest Posts" screen.', 'bean' )
									);
									
		$conditions['hierarchy']['front_page'] = array(
									'label' => __( 'Front Page', 'bean' ),
									'description' => __( 'Displayed on any front page, regardless of the settings under the "Settings -> Reading" admin screen.', 'bean' )
									);
									
		$conditions['hierarchy']['single'] = array(
									'label' => __( 'Single Entries', 'bean' ),
									'description' => __( 'Displayed on single entries of any public post type other than "Pages".', 'bean' )
									);
									
		$conditions['hierarchy']['archive'] = array(
									'label' => __( 'All Archives', 'bean' ),
									'description' => __( 'Displayed on all archives (category, tag, taxonomy, post type, dated, author and search).', 'bean' )
									);
									
		$conditions['hierarchy']['author'] = array(
									'label' => __( 'Author Archives', 'bean' ),
									'description' => __( 'Displayed on all author archive screens (that don\'t have a more specific sidebar).', 'bean' )
									);
									
		$conditions['hierarchy']['date'] = array(
									'label' => __( 'Date Archives', 'bean' ),
									'description' => __( 'Displayed on all date archives.', 'bean' )
									);
									
		$conditions['hierarchy']['404'] = array(
									'label' => __( '404 Error Screens', 'bean' ),
									'description' => __( 'Displayed on all 404 error screens.', 'bean' )
									);

		$this->conditions_reference = (array)apply_filters( 'radium_conditions_reference', $conditions );
		$this->conditions_headings = (array)apply_filters( 'radium_conditions_headings', $conditions_headings );
	} // End setup_default_conditions_reference()
	
	/**
	 * is_hierarchy function.
	 *
	 * @description Is the current view a part of the default template hierarchy?		 
	 * @access public
	 * @return void
	 */
	function is_hierarchy () {
		if ( is_front_page() && ! is_home() ) {
			$this->conditions[] = 'static_front_page';
		}
		
		if ( ! is_front_page() && is_home() ) {
			$this->conditions[] = 'inner_posts_page';
		}

		if ( is_front_page() ) {
			$this->conditions[] = 'front_page';
		}

		if ( is_home() ) {
			$this->conditions[] = 'home';
		}
		
		if ( is_singular() ) {
			$this->conditions[] = 'singular';
			$this->conditions[] = 'post-type-' . get_post_type();
			$categories = get_the_category( get_the_ID() );
 			if ( ! is_wp_error( $categories ) && ( count( $categories ) > 0 ) ) {
				foreach ( $categories as $k => $v ) {
					$this->conditions[] = 'in-term-' . $v->term_id;
				}
			}
 
 			$this->conditions[] = 'post' . '-' . get_the_ID();
		}
		
		if ( is_search() ) {
			$this->conditions[] = 'search';
		}
		
		if ( is_home() ) {
			$this->conditions[] = 'home';
		}
		
		if ( is_front_page() ) {
			$this->conditions[] = 'front_page';
		}
		
		if ( is_archive() ) {
			$this->conditions[] = 'archive';
		}
		
		if ( is_author() ) {
			$this->conditions[] = 'author';
		}
		
		if ( is_date() ) {
			$this->conditions[] = 'date';
		}
		
		if ( is_404() ) {
			$this->conditions[] = '404';
		}
	} // End is_hierarchy()
	
	/**
	 * is_taxonomy function.
	 *
	 * @description Is the current view an archive within a specific taxonomy, that doesn't have a specific sidebar?
	 * @access public
	 * @return void
	 */
	function is_taxonomy () {
		if ( ( is_tax() || is_archive() ) && ! is_post_type_archive() ) {
			$obj = get_queried_object();

			if ( ! is_category() && ! is_tag() ) {
				$this->conditions[] = 'taxonomies';
			}
			@$this->conditions[] = 'archive-' . $obj->taxonomy;
			@$this->conditions[] = 'term-' . $obj->term_id;
		}
	} // End is_taxonomy()
	
	/**
	 * is_post_type_archive function.
	 *
	 * @description Is the current view an archive of a post type?
	 * @access public
	 * @return void
	 */
	function is_post_type_archive () {
		if ( is_post_type_archive() ) {
			$this->conditions[] = 'post-type-archive-' . get_post_type();
		}
	} // End is_post_type_archive()
	
	/**
	 * is_page_template function.
	 *
	 * @description Does the current view have a specific page template attached (used on single views)?
	 * @access public
	 * @return void
	 */
	function is_page_template () {
		if ( is_singular() ) {
			global $post;
			$template = get_post_meta( $post->ID, '_wp_page_template', true );
			
			if ( $template != '' && $template != 'default' ) {
				$this->conditions[] = str_replace( '.php', '', 'page-template-' . $template );
			}
		}
	} // End is_page_template()
	
	/**
	 * meta_box_setup function.
	 * 
	 * @access public
	 * @return void
	 */
	function meta_box_setup () {		
		add_meta_box( 'radium-conditions', $this->meta_box_settings['title'], array( &$this, 'meta_box_content' ), $this->token, 'normal', 'low' );
	} // End meta_box_setup()
	
	/**
	 * meta_box_content function.
	 * 
	 * @access public
	 * @return void
	 */
	function meta_box_content () {
		global $post_id;

		if ( count( $this->conditions_reference ) <= 0 ) $this->setup_default_conditions_reference();
		
		$selected_conditions = get_post_meta( $post_id, '_condition', false );

		if ( $selected_conditions == '' ) {
			$selected_conditions = array();
		}

		$html = '';
		
		$html .= '<input type="hidden" name="radium_' . $this->token . '_conditions_noonce" id="radium_' . $this->token . '_noonce" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		if ( count( $this->conditions_reference ) > 0 ) {
			
			// Separate out the taxonomy items for use as sub-tabs of "Taxonomy Terms".
			$taxonomy_terms = array();
			
			foreach ( $this->conditions_reference as $k => $v ) {
				if ( substr( $k, 0, 9 ) == 'taxonomy-' ) {
					$taxonomy_terms[$k] = $v;
					unset( $this->conditions_reference[$k] );
				}
			}
			
			$html .= '<div id="taxonomy-category" class="categorydiv tabs radium-conditions">' . "\n";
			
				$html .= '<ul id="category-tabs" class="conditions-tabs">' . "\n";
				
				$count = 0;

				// Determine whether or not to show advanced items, based on user's preference (default: false).
				$show_advanced = $this->show_advanced_items();

				foreach ( $this->conditions_reference as $k => $v ) {
					$count++;
					$class = '';
					if ( $count == 1 ) {
						$class = 'tabs';
					} else {
						$class = 'hide-if-no-js';
					}
					if ( in_array( $k, array( 'pages' ) ) ) {
						$class .= ' basic';
					} else {
							$class .= ' advanced';
							if ( ! $show_advanced ) { $class .= ' hide'; }
					}

					if ( isset( $this->conditions_headings[$k] ) ) {
						$html .= '<li class="' . esc_attr( $class ) . '"><a href="#tab-' . $k . '">' . $this->conditions_headings[$k] . '</a></li>' . "\n";
					}

					if ( $k == 'taxonomies' ) {
						$html .= '<li class="' . $class . '"><a href="#tab-taxonomy-terms">' . __( 'Taxonomy Terms', 'bean' ) . '</a></li>' . "\n";
					}
				}
				
				$class = 'hide-if-no-js advanced';
				if ( ! $show_advanced ) { $class .= ' hide'; }
				
				$html .= '<li class="advanced-settings alignright hide-if-no-js"><a href="#">' . __( 'Advanced', 'bean' ) . '</a></li>' . "\n";

				$html .= '</ul>' . "\n";
			
			foreach ( $this->conditions_reference as $k => $v ) {
				$count = 0;
				
				$tab = '';

				$tab .= '<div id="tab-' . $k . '" class="condition-tab">' . "\n";
				$tab .= '<h4>' . $this->conditions_headings[$k] . '</h4>' . "\n";
				$tab .= '<ul class="alignleft conditions-column">' . "\n";
					foreach ( $v as $i => $j ) {
						$count++;
						
						$checked = '';
						if ( in_array( $i, $selected_conditions ) ) {
							$checked = ' checked="checked"';
						}
						$tab .= '<li><label class="selectit" title="' . esc_attr( $j['description'] ) . '"><input type="checkbox" name="conditions[]" value="' . $i . '" id="checkbox-' . $i . '"' . $checked . ' /> ' . $j['label'] . '</label></li>' . "\n";
						
						if ( $count % 10 == 0 && $count < ( count( $v ) ) ) {
							$tab .= '</ul><ul class="alignleft conditions-column">';
						}
					}
					
				$tab .= '</ul>' . "\n";
				// Filter the contents of the current tab.
				$tab = apply_filters( 'radium_conditions_tab_' . esc_attr( $k ), $tab );
				$html .= $tab;
				$html .= '<div class="clear"></div>';
				$html .= '</div>' . "\n";
			}
			
			// Taxonomy Terms Tab
			$html .= '<div id="tab-taxonomy-terms" class="condition-tab inner-tabs">' . "\n";
					$html .= '<ul class="conditions-tabs-inner hide-if-no-js">' . "\n";
				
				foreach ( $taxonomy_terms as $k => $v ) {
					if ( ! isset( $this->conditions_headings[$k] ) ) { unset( $taxonomy_terms[$k] ); }
				}

				$count = 0;
				foreach ( $taxonomy_terms as $k => $v ) {
					$count++;
					$class = '';
					if ( $count == 1 ) {
						$class = 'tabs';
					} else {
						$class = 'hide-if-no-js';
					}
					
					$html .= '<li><a href="#tab-' . $k . '" title="' . __( 'Taxonomy Token', 'bean' ) . ': ' . str_replace( 'taxonomy-', '', $k ) . '">' . $this->conditions_headings[$k] . '</a>';
						if ( $count != count( $taxonomy_terms ) ) {
							$html .= ' |';
						}
					$html .= '</li>' . "\n";
				}

				$html .= '</ul>' . "\n";
			
			foreach ( $taxonomy_terms as $k => $v ) {
				$count = 0;
				
				$html .= '<div id="tab-' . $k . '" class="condition-tab">' . "\n";
				$html .= '<h4>' . $this->conditions_headings[$k] . '</h4>' . "\n";
				$html .= '<ul class="alignleft conditions-column">' . "\n";
					foreach ( $v as $i => $j ) {
						$count++;
						
						$checked = '';
						if ( in_array( $i, $selected_conditions ) ) {
							$checked = ' checked="checked"';
						}
						$html .= '<li><label class="selectit" title="' . esc_attr( $j['description'] ) . '"><input type="checkbox" name="conditions[]" value="' . $i . '" id="checkbox-' . $i . '"' . $checked . ' /> ' . $j['label'] . '</label></li>' . "\n";
						
						if ( $count % 10 == 0 && $count < ( count( $v ) ) ) {
							$html .= '</ul><ul class="alignleft conditions-column">';
						}
					}
					
				$html .= '</ul>' . "\n";
				$html .= '<div class="clear"></div>';
				$html .= '</div>' . "\n";
			}
			$html .= '</div>' . "\n";
		}
		
		// Allow themes/plugins to act here (key, args).
		do_action( 'radium_conditions_meta_box', $k, $v );

		$html .= '<br class="clear" />' . "\n";
		
		echo $html;
	} // End meta_box_content()
	
	/**
	 * meta_box_save function.
	 * 
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 */
	function meta_box_save ( $post_id ) {
		global $post, $messages;
		
		// Verify
		if ( ( get_post_type() != $this->token ) || ! wp_verify_nonce( $_POST['radium_' . $this->token . '_conditions_noonce'], plugin_basename(__FILE__) ) ) {  
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
		
		if ( isset( $_POST['conditions'] ) && ( count( $_POST['conditions'] ) > 0 ) ) {
			
			delete_post_meta( $post_id, '_condition' );
			
			foreach ( $_POST['conditions'] as $k => $v ) {
				add_post_meta( $post_id, '_condition', $v, false );
			}
		}
	} // End meta_box_save()

	/**
	 * show_advanced_itesm function.
	 * 
	 * @access private
	 * @return boolean
	 */
	private function show_advanced_items () {
		$response = false;

		$setting = get_user_setting( 'radiumsidebarsshowadvanced', '0' );

		if ( $setting == '1' ) { $response = true; }

		return $response;
	} // End show_advanced_items()

	/**
	 * ajax_toggle_advanced_items function.
	 * 
	 * @access public
	 * @return void
	 */
	public function ajax_toggle_advanced_items () {
		$nonce = $_POST['radiumsidebars_advanced_noonce'];
		$status = $_POST['new_status'];	
		//Add nonce security to the request
		if ( ! wp_verify_nonce( $nonce, 'radiumsidebars_advanced_noonce' ) ) {
			die();
		}

		$response = set_user_setting( 'radiumsidebarsshowadvanced', $status );

		echo $response;
		die(); // WordPress may print out a spurious zero without this can be particularly bad if using JSON
	} // End ajax_toggle_advanced_items()

	/**
	 * enqueue_scripts function.
	 * 
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts () {
		global $pagenow;
		if ( get_post_type() != $this->token ) { return; }
		
		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
			wp_register_script( $this->token . '-admin', BEAN_ADMIN_URL . '/sidebars/assets/js/sidebars_admin.js', array( 'jquery', 'jquery-ui-tabs' ), '1.0.0', true );
			
			wp_enqueue_script( $this->token . '-admin' );
			
			wp_dequeue_script( 'jquery-ui-datepicker' );

			$translation_strings = array();
		
			$ajax_vars = array( 'radiumsidebars_advanced_noonce' => wp_create_nonce( 'radiumsidebars_advanced_noonce' ) );

			$data = array_merge( $translation_strings, $ajax_vars );

			wp_localize_script( $this->token . '-admin', 'radiumsidebars_localized_data', $data );
		}
	} // End enqueue_scripts()
} // End Class
?>
