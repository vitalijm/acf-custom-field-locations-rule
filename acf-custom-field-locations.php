<?php 

	/*
		Plugin Name: Advanced Custom Fields: Custom Field Location Rule
		Plugin URI: https://github.com/Hube2/acf-custom-field-locations-rule
		Description: Set location of field groups base on choice fields in other groups
		Author: John A. Huebner II
		Author URI: https://github.com/Hube2
		Version: 0.0.1
	*/
	
	// If this file is called directly, abort.
	if (!defined('WPINC')) {die;}
	
	new acf_custom_field_location_rules();
	
	class acf_custom_field_location_rules {
		
		// field choices for location rules
		private $fields = array(
			/* 
				field key => array(
					'field_name' => 'field_name'
					'field_label' => 'field_label'
					'field_type' => 'field_type'
					'group_title =>
					'group_key' =>
				)
			*/);
		// all fields for matching
		
		private $current_group = '';
		
		public function __construct() {
			add_filter('acf/location/rule_types', array($this, 'rule_types'));
			//add_action('init', array($this, 'init'));
			// [action] => acf/field_group/render_location_value
			// [action] => acf/post/get_field_groups
			if (defined('DOING_AJAX') && 
					DOING_AJAX && 
					isset($_POST['action']) && 
					($_POST['action'] == 'acf/field_group/render_location_value' || 
						$_POST['action'] == 'acf/post/get_field_groups')) {
				//$this->write_to_file('doing ajax');
				//$this->write_to_file('DOING AJAX');
				//$this->write_to_file($_POST);
				add_action('init', array($this, 'init'));
			}
			//add_action('admin_enqueue_scripts', array($this, 'enqueue_script'));
			add_action('acf/input/admin_enqueue_scripts', array($this, 'enqueue_script'));
			//add_action('wp_enqueue_script', array($this, 'enqueue_script'));
		} // end public function __construct
		
		public function rule_types($choices) {
			$this->init();
			//$this->write_to_file('RULE TYPE CHOICES BEFORE:'); $this->write_to_file($choices);
			if (!count($this->fields)) {
				return $choices;
			}
			//echo '<pre>'; print_r($this->fields); echo '</pre>';
			//echo $this->current_group;
			$field_choices = array();
			foreach ($this->fields as $key => $field) {
				if ($field['group_key'] != $this->current_group) {
					$field_choices[$key] = 
							$field['group_title'].' ['.$field['group_key'].'] '.$field['field_label'].' ['.
							$field['field_type'].' - '.$field['field_name'].' - '.$key.']';
				}
			}
			if (!count($field_choices)) {
				return $choices;
			}
			if (!isset($choices['Custom Fields'])) {
				$choices['Custom Fields'] = $field_choices;
			}
			//$this->write_to_file('RULE TYPE CHOICES AFTER:'); $this->write_to_file($choices);
			return $choices;
		} // end public function rule_types
		
		public function rule_values($choices) {
			$this->init();
			if (!count($this->fields)) {
				return $choices;
			}
			$filter = current_filter();
			$key = substr($filter, strrpos($filter, '/')+1);
			//$this->write_to_file('DO FIELD: '.$key);
			if (!isset($this->fields[$key])) {
				return $choices;
			}
			return $this->fields[$key]['choices'];
		} // end public function rule_values
		
		public function rule_match($match, $rule, $options) {
			$this->init();
			//$this->write_to_file('RULE: ');
			//$this->write_to_file($rule);
			//$this->write_to_file('OPTIONS: ');
			//$this->write_to_file($options);
			$field = $rule['param'];
			if (!isset($this->fields[$field]) || !isset($options[$field])) {
				return $match;
			}
			if ($this->fields[$field]['field_type'] != 'true_false') {
				$selected = $options[$field];
				if (!is_array($selected)) {
					$selected = explode(',', $selected);
				}
				if ($rule['operator'] == '==') {
					$match = (in_array($rule['value'], $selected));
				} else {
					$match = !(in_array($rule['value'], $selected));
				}
			} else {
				if ($rule['operator'] == '==') {
					$match = ($options[$field] == 'checked');
				} else {
					$match = !($options[$field] == 'checked');
				}
			}
			return $match;
		} // end public function rule_match
		
		public function init() {
			if (count($this->fields)) {
				return;
			}
			global $post;
			$this_group = 0;
			if (isset($post->ID) && !is_a($post, 'WP_User') && get_post_type($post->ID) == 'acf-field-group') {
				$this_group = $post->ID;
			}
			$groups = acf_get_field_groups();
			//echo '<pre>'; print_r($groups); echo '</pre>';//
			if (!count($groups)) {
				return;
			}
			$valid_types = array('select', 'checkbox', 'radio', 'true_false');
			foreach ($groups as $group) {
				$fields = acf_get_fields($group['key']);
				//echo '<pre>'; print_r($fields); echo '</pre>';//
				if (!count($fields)) {
					continue;
				}
				if ($group['ID'] == $this_group) {
					$this->current_group = $group['key'];
				}
				foreach ($fields as $field) {
					if (!in_array($field['type'], $valid_types)) {
						continue;
					}
					$this->fields[$field['key']] = array(
						'field_name' => $field['name'],
						'field_label' => $field['label'],
						'field_type' => $field['type'],
						'group_title' => $group['title'],
						'group_key' => $group['key']
					);
					if (isset($field['choices'])) {
						$this->fields[$field['key']]['choices'] = $field['choices'];
					} else {
						$this->fields[$field['key']]['choices'] = array(
							'checked' => 'checked'
						);
					}
					// add hook for this field key
					add_filter('acf/location/rule_values/'.$field['key'], array($this, 'rule_values'));
					// add rule match filter for field key
					add_filter('acf/location/rule_match/'.$field['key'], array($this, 'rule_match'), 10, 3);
					//$this->write_to_file('added filter: '.'acf/location/rule_values/'.$field['key']);
				} // end foreadh $field
			} // end foreach $groups
			
		} // end public function init
		
		public function enqueue_script() {
			$handle = 'acf-custom-field-location-rule';
			$src = plugin_dir_url(__FILE__).'acf-custom-field-location-rule.js';
			$depends = array('acf-input');
			$version = '0.0.1';
			$in_footer = false;
			wp_enqueue_script($handle, $src, $depends, $version, $in_footer);
		} // end public function enqueue_script
		
		private function write_to_file($value) {
			$file = dirname(__FILE__).'/_acf-data.txt';
			$handle = fopen($file, 'a');
			ob_start();
			if (is_array($value) || is_object($value)) {
				print_r($value);
			} else {
				echo $value;
			}
			echo "\r\n";
			fwrite($handle, ob_get_clean());
			fclose($handle);
		} // end private function write_to_file
		
	} // end class acf_custom_field_location_rules
	
	
	if (!function_exists('write_to_file')) {	
		function write_to_file($value) {
			$file = dirname(__FILE__).'/_acf-data.txt';
			$handle = fopen($file, 'a');
			ob_start();
			if (is_array($value) || is_object($value)) {
				print_r($value);
			} else {
				echo $value;
			}
			echo "\r\n";
			fwrite($handle, ob_get_clean());
			fclose($handle);
		}
	}
?>