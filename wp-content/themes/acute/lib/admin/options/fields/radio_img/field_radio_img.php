<?php
class RADIUM_Options_radio_img extends RADIUM_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since RADIUM_Options 1.0
	*/
	function __construct($field = array(), $value = '', $parent = ''){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since RADIUM_Options 1.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))?'class="'.$this->field['class'].'" ':'';
		
		echo '<fieldset>';
			
			foreach($this->field['options'] as $k => $v){

				$selected = (checked($this->value, $k, false) != '')?' radium-radio-img-selected':'';

				echo '<label class="radium-radio-img'.$selected.' radium-radio-img-'.$this->field['id'].'" for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				
				echo '<input type="radio" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.' value="'.$k.'" '.checked($this->value, $k, false).'/>';
				
				echo '<img src="'.$v['img'].'" alt="'.$v['title'].'" title="'.$v['title'].'" onclick="jQuery:radium_radio_img_select(\''.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'\', \''.$this->field['id'].'\');" />';
				
				echo '<br/><span>'.$v['title'].'</span>';
				echo '</label>';
				
			}//foreach

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<br/><span class="description">'.$this->field['desc'].'</span>':'';
		
		echo '</fieldset>';
		
	}//function
	
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since RADIUM_Options 1.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'radium-opts-field-radio_img-js', 
			RADIUM_OPTIONS_URL.'fields/radio_img/field_radio_img.js', 
			array('jquery'),
			time(),
			true
		);
		
	}//function
	
}//class
?>