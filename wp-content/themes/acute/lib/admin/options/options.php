<?php
global $radium_options;

$radium_options = array();

function register_theme_options( $options ){
    global $radium_options;
    $radium_options = array_merge( $radium_options, $options );
}


if ( ! class_exists('RADIUM_Options') ){
	
	// windows-proof constants: replace backward by forward slashes - thanks to: https://github.com/peterbouwmeester
	$fslashed_dir = trailingslashit(str_replace('\\','/',dirname(__FILE__)));
	$fslashed_abs = trailingslashit(str_replace('\\','/',ABSPATH));
	
	if(!defined('RADIUM_OPTIONS_DIR')){
		define('RADIUM_OPTIONS_DIR', $fslashed_dir);
	}
	
	if(!defined('RADIUM_OPTIONS_URL')){
		define('RADIUM_OPTIONS_URL', site_url(str_replace( $fslashed_abs, '', $fslashed_dir )));
	}
	
class RADIUM_Options{
	
	protected $framework_url = 'http://radiumthemes.com/framework/';
	protected $framework_version = '2.0';
		
	public $dir = RADIUM_OPTIONS_DIR;
	public $url = RADIUM_OPTIONS_URL;
	public $page = '';
	public $args = array();
	public $sections = array();
	public $extra_tabs = array();
	public $errors = array();
	public $warnings = array();
	public $options = array();
	
 
	/**
	 * Class Constructor. Defines the args for the theme options class
	 *
	 * @since RADIUM_Options 2.0
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function __construct($sections = array(), $args = array(), $extra_tabs = array()){
		
		$defaults = array();
		
		$defaults['opt_name'] = '';//must be defined by theme/plugin
		
		$defaults['google_api_key'] = '';//must be defined for use with google webfonts field type
		
		$defaults['menu_icon'] = RADIUM_OPTIONS_URL.'/assets/assets/images/menu_icon.png';
		$defaults['menu_title'] = __('Options', 'bean');
		$defaults['page_icon'] = 'icon-themes';
		$defaults['page_title'] = __('Options', 'bean');
		$defaults['page_slug'] = '_options';
		$defaults['page_cap'] = 'manage_options';
		$defaults['page_type'] = 'menu';
		$defaults['page_parent'] = '';
		$defaults['page_position'] = 100;
		$defaults['allow_sub_menu'] = true;
		
		$defaults['show_import_export'] = true;
		
		if( DEV_MODE ){
		
			$args['dev_mode'] = true;
			
		} else {
		
			$defaults['dev_mode'] = false;
			
		}
		
		$defaults['stylesheet_override'] = false;
				
		$defaults['help_tabs'] = array();
		$defaults['help_sidebar'] = __('', 'bean');
		
		//get args
		$this->args = wp_parse_args($args, $defaults);
		$this->args = apply_filters('radium-opts-args-'.$this->args['opt_name'], $this->args);
		
		//get sections
		$this->sections = apply_filters('radium-opts-sections-'.$this->args['opt_name'], $sections);
		
		//get extra tabs
		$this->extra_tabs = apply_filters('radium-opts-extra-tabs-'.$this->args['opt_name'], $extra_tabs);
		
		//set option with defaults
		add_action('init', array(&$this, '_set_default_options'));
		
		//options page
		add_action('admin_menu', array(&$this, '_options_page'));
		
		//register setting
		add_action('admin_init', array(&$this, '_register_setting'));
		
		//add the js for the error handling before the form
		add_action('radium-opts-page-before-form-'.$this->args['opt_name'], array(&$this, '_errors_js'), 1);
		
		//add the js for the warning handling before the form
		add_action('radium-opts-page-before-form-'.$this->args['opt_name'], array(&$this, '_warnings_js'), 2);
		
		//hook into the wp feeds for downloading the exported settings
		add_action('do_feed_radiumopts-'.$this->args['opt_name'], array(&$this, '_download_options'), 1, 1);
		
		//get the options for use later on
		$this->options = get_option($this->args['opt_name']);
		
		}//function
	
	
	/**
	 * ->get(); This is used to return and option value from the options array
	 *
	 * @since RADIUM_Options 2.0.1
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function get($opt_name, $default = null){
		return (!empty($this->options[$opt_name])) ? $this->options[$opt_name] : $default;
	}//function
	
	/**
	 * ->set(); This is used to set an arbitrary option in the options array
	 *
	 * @since RADIUM_Options 2.0.1
	 * 
	 * @param string $opt_name the name of the option being added
	 * @param mixed $value the value of the option being added
	 */
	function set($opt_name = '', $value = '') {
		if($opt_name != ''){
			$this->options[$opt_name] = $value;
			update_option($this->args['opt_name'], $this->options);
		}//if
	}
	
	/**
	 * ->show(); This is used to echo and option value from the options array
	 *
	 * @since RADIUM_Options 2.0.1
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function show($opt_name, $default = ''){
		$option = $this->get($opt_name);
		if(!is_array($option) && $option != ''){
			echo $option;
		}elseif($default != ''){
			echo $default;
		}
	}//function
 	
	
	/**
	 * Get default options into an array suitable for the settings API
	 *
	 * @since RADIUM_Options 2.0
	 *
	*/
	function _default_values(){
		
		$defaults = array();
		
		foreach($this->sections as $k => $section){
			
			if(isset($section['fields'])){
		
				foreach($section['fields'] as $fieldk => $field){
					
					if(!isset($field['std'])){$field['std'] = '';}
						
						$defaults[$field['id']] = $field['std'];
					
				}//foreach
			
			}//if
			
		}//foreach
		
		//fix for notice on first page load
		$defaults['last_tab'] = 0;

		return $defaults;
		
	}
	
 	
	/**
	 * Set default options on admin_init if option doesn't exist (theme activation hook caused problems, so admin_init it is)
	 *
	 * @since RADIUM_Options 2.0
	 *
	*/
	function _set_default_options(){
		if(!get_option($this->args['opt_name'])){
			add_option($this->args['opt_name'], $this->_default_values());
		}
		$this->options = get_option($this->args['opt_name']);
	}//function
	
	
	/**
	 * Class Theme Options Page Function, creates main options page.
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _options_page(){
		if($this->args['page_type'] == 'submenu'){
			if(!isset($this->args['page_parent']) || empty($this->args['page_parent'])){
				$this->args['page_parent'] = 'themes.php';
			}
			$this->page = add_submenu_page(
							$this->args['page_parent'],
							$this->args['page_title'], 
							$this->args['menu_title'], 
							$this->args['page_cap'], 
							$this->args['page_slug'], 
							array(&$this, '_options_page_html')
						);
		}else{
			$this->page = add_menu_page(
							$this->args['page_title'], 
							$this->args['menu_title'], 
							$this->args['page_cap'], 
							$this->args['page_slug'], 
							array(&$this, '_options_page_html'),
							$this->args['menu_icon'],
							$this->args['page_position']
						);
						
		if(true === $this->args['allow_sub_menu']){
						
			//this is needed to remove the top level menu item from showing in the submenu
			add_submenu_page($this->args['page_slug'],$this->args['page_title'],'',$this->args['page_cap'],$this->args['page_slug'],create_function( '$a', "return null;" ));
						
						
			foreach($this->sections as $k => $section){
							
				add_submenu_page(
						$this->args['page_slug'],
						$section['title'], 
						$section['title'], 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab='.$k, 
						create_function( '$a', "return null;" )
				);
					
			}
			
			if(true === $this->args['show_import_export']){
				
				add_submenu_page(
						$this->args['page_slug'],
						__('Import / Export / Reset', 'bean'), 
						__('Import / Export / Reset', 'bean'), 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab=import_export_default', 
						create_function( '$a', "return null;" )
				);
					
			}//if
						

			foreach($this->extra_tabs as $k => $tab){
				
				add_submenu_page(
						$this->args['page_slug'],
						$tab['title'], 
						$tab['title'], 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab='.$k, 
						create_function( '$a', "return null;" )
				);
				
			}

			if(true === $this->args['dev_mode']){
						
				add_submenu_page(
						$this->args['page_slug'],
						__('Dev Mode Info', 'bean'), 
						__('Dev Mode Info', 'bean'), 
						$this->args['page_cap'], 
						$this->args['page_slug'].'&tab=dev_mode_default', 
						create_function( '$a', "return null;" )
				);
				
			}//if

		}//if			
						
			
		}//else

		add_action('admin_print_styles-'.$this->page, array(&$this, '_enqueue'));
		add_action('load-'.$this->page, array(&$this, '_load_page'));
	}//function	
 	

	/**
	 * enqueue styles/js for theme page
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _enqueue(){
		
		wp_register_style(
				'radium-opts-css', 
				$this->url.'assets/css/options.css',
				array('farbtastic'),
				time(),
				'all'
			);
			
		wp_register_style(
			'radium-opts-jquery-ui-css',
			apply_filters('radium-opts-ui-theme', $this->url.'assets/css/jquery-ui-aristo/aristo.css'),
			'',
			time(),
			'all'
		);
			
			
		if(false === $this->args['stylesheet_override']){
			wp_enqueue_style('radium-opts-css');
		}
		
		wp_enqueue_script(
			'radium-opts-js', 
			$this->url.'assets/js/options.js', 
			array('jquery'),
			time(),
			true
		);
		wp_localize_script('radium-opts-js', 'radium_opts', array('reset_confirm' => __('Are you sure? Resetting will loose all custom values.', 'bean'), 'opt_name' => $this->args['opt_name']));
		
		do_action('radium-opts-enqueue-'.$this->args['opt_name']);
		
		
		foreach($this->sections as $k => $section){
			
			if(isset($section['fields'])){
				
				foreach($section['fields'] as $fieldk => $field){
					
					if(isset($field['type'])){
					
						$field_class = 'RADIUM_Options_'.$field['type'];
						
						if(!class_exists($field_class)){
							require_once($this->dir.'fields/'.$field['type'].'/field_'.$field['type'].'.php');
						}//if
				
						if(class_exists($field_class) && method_exists($field_class, 'enqueue')){
							$enqueue = new $field_class('','',$this);
							$enqueue->enqueue();
						}//if
						
					}//if type
					
				}//foreach
			
			}//if fields
			
		}//foreach
			
		
	}//function
	
	/**
	 * Download the options file, or display it
	 *
	 * @since RADIUM_Options 2.0.1
	*/
	function _download_options(){
		//-'.$this->args['opt_name']
		if(!isset($_GET['secret']) || $_GET['secret'] != md5(AUTH_KEY.SECURE_AUTH_KEY)){wp_die('Invalid Secret for options use');exit;}
		if(!isset($_GET['feed'])){wp_die('No Feed Defined');exit;}
		$backup_options = get_option(str_replace('radiumopts-','',$_GET['feed']));
		$backup_options['radium-opts-backup'] = '1';
		$content = '###'.serialize($backup_options).'###';
		
		
		if(isset($_GET['action']) && $_GET['action'] == 'download_options'){
			header('Content-Description: File Transfer');
			header('Content-type: application/txt');
			header('Content-Disposition: attachment; filename="'.str_replace('radiumopts-','',$_GET['feed']).'_options_'.date('d-m-Y').'.txt"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			echo $content;
			exit;
		}else{
			echo $content;
			exit;
		}
	}
 
 	
	/**
	 * show page help
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _load_page(){
		
		//do admin head action for this page
		add_action('admin_head', array(&$this, 'admin_head'));
		
		//do admin footer text hook
		add_filter('admin_footer_text', array(&$this, 'admin_footer_text'));
		
		$screen = get_current_screen();
		
		if(is_array($this->args['help_tabs'])){
			foreach($this->args['help_tabs'] as $tab){
				$screen->add_help_tab($tab);
			}//foreach
		}//if
		
		if($this->args['help_sidebar'] != ''){
			$screen->set_help_sidebar($this->args['help_sidebar']);
		}//if
		
		do_action('radium-opts-load-page-'.$this->args['opt_name'], $screen);
		
	}//function
	
	
	/**
	 * do action radium-opts-admin-head for theme options page
	 *
	 * @since RADIUM_Options 2.0
	*/
	function admin_head(){
		
		do_action('radium-opts-admin-head-'.$this->args['opt_name'], $this);
		
	}//function
	
	
	function admin_footer_text($footer_text){
		
	}//function
	
	
	
	
	/**
	 * Register Option for use
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _register_setting(){
		
		register_setting($this->args['opt_name'].'_group', $this->args['opt_name'], array(&$this,'_validate_options'));
		
		foreach($this->sections as $k => $section){
			
			add_settings_section($k.'_section', $section['title'], array(&$this, '_section_desc'), $k.'_section_group');
			
			if(isset($section['fields'])){
			
				foreach($section['fields'] as $fieldk => $field){
					
					if(isset($field['title'])){
					
						$th = (isset($field['sub_desc']))?$field['title'].'<span class="description">'.$field['sub_desc'].'</span>':$field['title'];
					}else{
						$th = '';
					}
					
					add_settings_field($fieldk.'_field', $th, array(&$this,'_field_input'), $k.'_section_group', $k.'_section', $field); // checkbox
					
				}//foreach
			
			}//if(isset($section['fields'])){
			
		}//foreach
		
		do_action('radium-opts-register-settings-'.$this->args['opt_name']);
		
	}//function
 
  	
	/**
	 * Validate the Options options before insertion
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _validate_options($plugin_options){
		
		set_transient('radium-opts-saved', '1', 1000 );
		
		if(!empty($plugin_options['import'])){
			
			if($plugin_options['import_code'] != ''){
				$import = $plugin_options['import_code'];
			}elseif($plugin_options['import_link'] != ''){
				$import = wp_remote_retrieve_body( wp_remote_get($plugin_options['import_link']) );
			}
			
			$imported_options = unserialize(trim($import,'###'));
			if(is_array($imported_options) && isset($imported_options['radium-opts-backup']) && $imported_options['radium-opts-backup'] == '1'){
				$imported_options['imported'] = 1;
				return $imported_options;
			}
			
			
		}
		
		
		if(!empty($plugin_options['defaults'])){
			$plugin_options = $this->_default_values();
			return $plugin_options;
		}//if set defaults

		
		//validate fields (if needed)
		$plugin_options = $this->_validate_values($plugin_options, $this->options);
		
		if($this->errors){
			set_transient('radium-opts-errors-'.$this->args['opt_name'], $this->errors, 1000 );		
		}//if errors
		
		if($this->warnings){
			set_transient('radium-opts-warnings-'.$this->args['opt_name'], $this->warnings, 1000 );		
		}//if errors
		
		do_action('radium-opts-options-validate-'.$this->args['opt_name'], $plugin_options, $this->options);
		
		
		unset($plugin_options['defaults']);
		unset($plugin_options['import']);
		unset($plugin_options['import_code']);
		unset($plugin_options['import_link']);
		
		return $plugin_options;	
	
	}//function
	 
	 	
	/**
	 * Validate values from options form (used in settings api validate function)
	 * calls the custom validation class for the field so authors can override with custom classes
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _validate_values($plugin_options, $options){
		foreach($this->sections as $k => $section){
			
			if(isset($section['fields'])){
			
				foreach($section['fields'] as $fieldk => $field){
					$field['section_id'] = $k;
					
					if(isset($field['type']) && $field['type'] == 'multi_text'){continue;}//we cant validate this yet
					
					if(!isset($plugin_options[$field['id']]) || $plugin_options[$field['id']] == ''){
						continue;
					}
					
					//force validate of custom filed types
					
					if(isset($field['type']) && !isset($field['validate'])){
						if($field['type'] == 'color' || $field['type'] == 'color_gradient'){
							$field['validate'] = 'color';
						}elseif($field['type'] == 'date'){
							$field['validate'] = 'date';
						}
					}//if
	
					if(isset($field['validate'])){
						$validate = 'RADIUM_Validation_'.$field['validate'];
						
						if(!class_exists($validate)){
							require_once($this->dir.'validation/'.$field['validate'].'/validation_'.$field['validate'].'.php');
						}//if
						
						if(class_exists($validate)){
							$validation = new $validate($field, $plugin_options[$field['id']], $options[$field['id']]);
							$plugin_options[$field['id']] = $validation->value;
							if(isset($validation->error)){
								$this->errors[] = $validation->error;
							}
							if(isset($validation->warning)){
								$this->warnings[] = $validation->warning;
							}
							continue;
						}//if
					}//if
					
					
					if(isset($field['validate_callback']) && function_exists($field['validate_callback'])){
						
						$callbackvalues = call_user_func($field['validate_callback'], $field, $plugin_options[$field['id']], $options[$field['id']]);
						$plugin_options[$field['id']] = $callbackvalues['value'];
						if(isset($callbackvalues['error'])){
							$this->errors[] = $callbackvalues['error'];
						}//if
						if(isset($callbackvalues['warning'])){
							$this->warnings[] = $callbackvalues['warning'];
						}//if
						
					}//if
					
					
				}//foreach
			
			}//if(isset($section['fields'])){
			
		}//foreach
		return $plugin_options;
	}//function
	
 	
	/**
	 * HTML OUTPUT.
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _options_page_html(){
		
		echo '<div class="wrap">';
			echo '<div id="'.$this->args['page_icon'].'" class="icon32"><br/></div>';
			echo '<h2 id="radium-opts-heading">'.get_admin_page_title().'</h2>';
			echo (isset($this->args['intro_text']))?$this->args['intro_text']:'';
			
			do_action('radium-opts-page-before-form-'.$this->args['opt_name']);

			echo '<form method="post" action="options.php" enctype="multipart/form-data" id="radium-opts-form-wrapper">';
				settings_fields($this->args['opt_name'].'_group');
				
				$this->options['last_tab'] = (isset($_GET['tab']) && !get_transient('radium-opts-saved'))?$_GET['tab']:$this->options['last_tab'];
				
				echo '<input type="hidden" id="last_tab" name="'.$this->args['opt_name'].'[last_tab]" value="'.$this->options['last_tab'].'" />';
				
				echo '<div id="radium-opts-header">';
				echo '<h3 id="radium-opts-theme-heading"><span id="opts-theme-heading">'.BEAN_THEME_NAME.' WordPress Theme</span>';
				echo '<span id="radium-opts-theme-ver">Version '.BEAN_THEME_VER.'</span></h3>';
						
					if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('radium-opts-saved') == '1'){
						if(isset($this->options['imported']) && $this->options['imported'] == 1){
							echo '<div id="radium-opts-imported">'.apply_filters('radium-opts-imported-text-'.$this->args['opt_name'], __('<strong>Settings Imported!</strong>', 'bean')).'</div>';
						}else{
							echo '<div id="radium-opts-save">'.apply_filters('radium-opts-saved-text-'.$this->args['opt_name'], __('<strong>Settings Saved!</strong>', 'bean')).'</div>';
						}
						delete_transient('radium-opts-saved');
					}
					echo '<div id="radium-opts-save-warn">'.apply_filters('radium-opts-changed-text-'.$this->args['opt_name'], __('', 'bean')).'</div>';
					echo '<div id="radium-opts-field-errors">'.__('<strong><span></span> error(s) were found!</strong>', 'bean').'</div>';
					
					echo '<div id="radium-opts-field-warnings">'.__('<strong><span></span> warning(s) were found!</strong>', 'bean').'</div>';
									
					submit_button('Save Changes', 'button-primary', 'button-primary', '', false);

					echo '<div class="clear"></div><!--clearfix-->';
					
				echo '</div>';
				echo '<div class="clear"></div><!--clearfix-->';
				
				echo '<div id="radium-opts-sidebar">';
					echo '<ul id="radium-opts-group-menu">';
						
						$icon = null;
					
						foreach($this->sections as $k => $section){
								echo '<li id="'.$k.'_section_group_li" class="radium-opts-group-tab-link-li">';
								echo '<a href="javascript:void(0);" id="'.$k.'_section_group_li_a" class="radium-opts-group-tab-link-a" data-rel="'.$k.'">'.$icon.'<span>'.$section['title'].'</span></a>';
							echo '</li>';
						}
						
						do_action('radium-opts-after-section-menu-items-'.$this->args['opt_name'], $this);
						
						if(true === $this->args['show_import_export']){
							echo '<li id="import_export_default_section_group_li" class="radium-opts-group-tab-link-li">';
									echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="radium-opts-group-tab-link-a" data-rel="import_export_default"> <span>'.__('Import / Export & Reset', 'bean').'</span></a>';
							echo '</li>';
						}
						
						
						foreach($this->extra_tabs as $k => $tab){
							$icon = (!isset($tab['icon']))?' ':'';
							echo '<li id="'.$k.'_section_group_li" class="radium-opts-group-tab-link-li">';
								echo '<a href="javascript:void(0);" id="'.$k.'_section_group_li_a" class="radium-opts-group-tab-link-a custom-tab" data-rel="'.$k.'">'.$icon.'<span>'.$tab['title'].'</span></a>';
							echo '</li>';
						}

//						
//						if(false === $this->args['dev_mode']){
//							echo '<li id="dev_mode_default_section_group_li" class="radium-opts-group-tab-link-li">';
//									echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="radium-opts-group-tab-link-a custom-tab" data-rel="dev_mode_default"> <span>'.__('Dev Mode Info', 'bean').'</span></a>';
//							echo '</li>';
//						}
//						
					echo '</ul>';
				echo '</div>';
				
				echo '<div id="radium-opts-main">';
				
					foreach($this->sections as $k => $section){
						echo '<div id="'.$k.'_section_group'.'" class="radium-opts-group-tab">';
							do_settings_sections($k.'_section_group');
						echo '</div>';
					}					
					
					
					if(true === $this->args['show_import_export']){
						echo '<div id="import_export_default_section_group'.'" class="radium-opts-group-tab">';
							
							
							echo '<div class="import-header">';
								echo '<h3>'.__('Import / Export & Reset Options', 'bean').'</h3>';
								echo '<div class="radium-opts-section-desc">';
								echo '<p class="description">'.__('Copy & download your current options settings, import a new set, or do a full reset.', 'bean').'</p>';
								echo '</div>';
							echo '</div>';
      
      
							echo '<h4>'.__('Import Theme Options', 'bean').'</h4>';
							echo '<div class="radium-opts-section-desc">';
								echo '<p class="description1">'.apply_filters('radium-opts-backup-description', __('Input your backup file below and hit Import to restore your sites options from a backup. Input the URL to another sites options set and hit Import to load the options from that site.', 'bean')).'</p>';
							echo '</div>';

							echo '<div class="radium-opts-import-warning"><span>'.apply_filters('radium-opts-import-warning', __('WARNING! This will overwrite any existing options, please proceed with extreme caution.', 'bean')).'</span></div>';				
							
							echo '<p><a href="javascript:void(0);" id="radium-opts-import-code-button" class="button-secondary">Open Uploader</a> <a href="javascript:void(0);" id="radium-opts-import-link-button" class="button-secondary">URL Import</a></p>';
							
							echo '<div id="radium-opts-import-code-wrapper">';
								echo '<textarea id="import-code-value" name="'.$this->args['opt_name'].'[import_code]" class="large-text" rows="8"></textarea>';
							echo '</div>';
							
							echo '<div id="radium-opts-import-link-wrapper">';
								echo '<input type="text" id="import-link-value" name="'.$this->args['opt_name'].'[import_link]" class="large-text" value="" />';
							echo '</div>';
							
							
							echo '<p id="radium-opts-import-action"><input type="submit" id="radium-opts-import" name="'.$this->args['opt_name'].'[import]" class="button-primary" value="'.__('Import', 'bean').'"</p>';
							
			
							
							echo '<div id="import_divide"></div>';
							
							
							
							echo '<h4>'.__('Export Theme Options', 'bean').'</h4>';
							
							echo '<div class="radium-opts-section-desc">';
								echo '<p class="description1">'.apply_filters('radium-opts-backup-description', __('Here you can copy/download your themes current option settings. Keep this safe as you can use it as a backup should anything go wrong. Or you can use it to restore your settings on this site (or any other site). You also have the handy option to copy the link to yours sites settings. Which you can then use to duplicate on another site', 'bean')).'</p>';
							echo '</div>';
							
								echo '<p>
								<a href="'.add_query_arg(array('feed' => 'radiumopts-'.$this->args['opt_name'], 'action' => 'download_options', 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()).'" id="radium-opts-export-code-dl" class="button-primary">Download</a>
								
								<a href="javascript:void(0);" id="radium-opts-export-code-copy" class="button-secondary">Copy</a>
								
								<a href="javascript:void(0);" id="radium-opts-export-link" class="button-secondary">Copy Link</a></p>';

								$backup_options = $this->options;
								$backup_options['radium-opts-backup'] = '1';
								$encoded_options = '###'.serialize($backup_options).'###';
								echo '<textarea class="large-text" id="radium-opts-export-code" rows="8">';print_r($encoded_options);echo '</textarea>';
								echo '<input type="text" class="large-text" id="radium-opts-export-link-value" value="'.add_query_arg(array('feed' => 'radiumopts-'.$this->args['opt_name'], 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()).'" />';
							
							
							
							echo '<div id="import_divide"></div>';
							
							echo '<h4>'.__('Theme Options Reset', 'bean').'</h4>';
							
							echo '<div class="radium-opts-section-desc">';
								echo '<p class="description1">'.apply_filters('radium-opts-backup-description', __('Conduct a full reset of your current theme options  and return all settings to their default values.  ', 'bean')).'</p>';
							echo '</div>';
							
							echo '<div class="radium-opts-import-warning"><span>'.apply_filters('radium-opts-import-warning', __('WARNING! This will definitely reset all your theme options settings.', 'bean')).'</span></div>';	
							
							submit_button(__('Reset All Current Options', 'bean'), 'button-primary', $this->args['opt_name'].'[defaults]', false);
							
							
						
						echo '</div>';
					}
					
					
					
					foreach($this->extra_tabs as $k => $tab){
						echo '<div id="'.$k.'_section_group'.'" class="radium-opts-group-tab">';
						echo '<h3>'.$tab['title'].'</h3>';
						echo $tab['content'];
						echo '</div>';
					}

					
					
					if(true === $this->args['dev_mode']){
						echo '<div id="dev_mode_default_section_group'.'" class="radium-opts-group-tab">';
							echo '<h3>'.__('Dev Mode Info', 'bean').'</h3>';
							echo '<div class="radium-opts-section-desc">';
							echo '<textarea class="large-text" rows="24">'.print_r($this, true).'</textarea>';
							echo '</div>';
						echo '</div>';
					}
					
					
					do_action('radium-opts-after-section-items-'.$this->args['opt_name'], $this);
				
					echo '<div class="clear"></div><!--clearfix-->';
				echo '</div>';
				echo '<div class="clear"></div><!--clearfix-->';
				
				echo '<div id="radium-opts-footer">';
				
				
					
					submit_button('', 'primary', '', false);
					echo '<div class="clear"></div><!--clearfix-->';
				echo '</div>';
			
			echo '</form>';
			
			do_action('radium-opts-page-after-form-'.$this->args['opt_name']);
			
			echo '<div class="clear"></div><!--clearfix-->';	
		echo '</div><!--wrap-->';

	}//function
	
 	
	/**
	 * JS to display the errors on the page
	 *
	 * @since RADIUM_Options 2.0
	*/	
	function _errors_js(){
		
		if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('radium-opts-errors-'.$this->args['opt_name'])){
				$errors = get_transient('radium-opts-errors-'.$this->args['opt_name']);
				$section_errors = array();
				foreach($errors as $error){
					$section_errors[$error['section_id']] = (isset($section_errors[$error['section_id']]))?$section_errors[$error['section_id']]:0;
					$section_errors[$error['section_id']]++;
				}
				
				echo '<script type="text/javascript">';
					echo 'jQuery(document).ready(function(){';
						echo 'jQuery("#radium-opts-field-errors span").html("'.count($errors).'");';
						echo 'jQuery("#radium-opts-field-errors").show();';
						
						foreach($section_errors as $sectionkey => $section_error){
							echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"radium-opts-menu-error\">'.$section_error.'</span>");';
						}
						
						foreach($errors as $error){
							echo 'jQuery("#'.$error['id'].'").addClass("radium-opts-field-error");';
							echo 'jQuery("#'.$error['id'].'").closest("td").append("<span class=\"radium-opts-th-error\">'.$error['msg'].'</span>");';
						}
					echo '});';
				echo '</script>';
				delete_transient('radium-opts-errors-'.$this->args['opt_name']);
			}
		
	}//function
 	
	
	/**
	 * JS to display the warnings on the page
	 *
	 * @since RADIUM_Options 2.0
	*/	
	function _warnings_js(){
		
		if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('radium-opts-warnings-'.$this->args['opt_name'])){
				$warnings = get_transient('radium-opts-warnings-'.$this->args['opt_name']);
				$section_warnings = array();
				foreach($warnings as $warning){
					$section_warnings[$warning['section_id']] = (isset($section_warnings[$warning['section_id']]))?$section_warnings[$warning['section_id']]:0;
					$section_warnings[$warning['section_id']]++;
				}
				
				
				echo '<script type="text/javascript">';
					echo 'jQuery(document).ready(function(){';
						echo 'jQuery("#radium-opts-field-warnings span").html("'.count($warnings).'");';
						echo 'jQuery("#radium-opts-field-warnings").show();';
						
						foreach($section_warnings as $sectionkey => $section_warning){
							echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"radium-opts-menu-warning\">'.$section_warning.'</span>");';
						}
						
						foreach($warnings as $warning){
							echo 'jQuery("#'.$warning['id'].'").addClass("radium-opts-field-warning");';
							echo 'jQuery("#'.$warning['id'].'").closest("td").append("<span class=\"radium-opts-th-warning\">'.$warning['msg'].'</span>");';
						}
					echo '});';
				echo '</script>';
				delete_transient('radium-opts-warnings-'.$this->args['opt_name']);
			}
		
	}//function
	
   	
	/**
	 * Section HTML OUTPUT.
	 *
	 * @since RADIUM_Options 2.0
	*/	
	function _section_desc($section){
		
		$id = rtrim($section['id'], '_section');
		
		if(isset($this->sections[$id]['desc']) && !empty($this->sections[$id]['desc'])) {
			echo '<div class="radium-opts-section-desc">'.$this->sections[$id]['desc'].'</div>';
		}
		
	}//function
	
	
  	/**
	 * Field HTML OUTPUT.
	 *
	 * Gets option from options array, then calls the specific field type class - allows extending by other devs
	 *
	 * @since RADIUM_Options 2.0
	*/
	function _field_input($field){
		
		
		if(isset($field['callback']) && function_exists($field['callback'])){
			$value = (isset($this->options[$field['id']]))?$this->options[$field['id']]:'';
			do_action('radium-opts-before-field-'.$this->args['opt_name'], $field, $value);
			call_user_func($field['callback'], $field, $value);
			do_action('radium-opts-after-field-'.$this->args['opt_name'], $field, $value);
			return;
		}
		
		if(isset($field['type'])){
			
			$field_class = 'RADIUM_Options_'.$field['type'];
			
			if(class_exists($field_class)){
				require_once($this->dir.'fields/'.$field['type'].'/field_'.$field['type'].'.php');
			}//if
			
			if(class_exists($field_class)){
				$value = (isset($this->options[$field['id']]))?$this->options[$field['id']]:'';
				do_action('radium-opts-before-field-'.$this->args['opt_name'], $field, $value);
				$render = '';
				$render = new $field_class($field, $value, $this);
				$render->render();
				do_action('radium-opts-after-field-'.$this->args['opt_name'], $field, $value);
			}//if
			
		}//if $field['type']
		
	}//function

	
}//class
}//if
?>