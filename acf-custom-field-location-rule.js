	
	jQuery(document).ready(function($){
		if (typeof acf == 'undefined') { return; }
	
		var acfCustomFieldLocatioRule = acf.ajax.extend({
			events: {
				'change .acf-field-select select': '_custom_field_location_change_select',
				'ready .acf-field-select select': '_custom_field_location_change_select',
				'change .acf-field-radio input': '_custom_field_location_change_radio',
				'ready .acf-field-radio input': '_custom_field_location_change_radio',
				'change .acf-field-true-false input': '_custom_field_location_change_true_false',
				'ready .acf-field-true-false input': '_custom_field_location_change_true_false',
				'change .acf-field-checkbox input': '_custom_field_location_change_checkox',
				'ready .acf-field-checkbox input': '_custom_field_location_change_checkox',
			},
	
			_custom_field_location_change_select: function(e){
				//alert(typeof(e.$el));
				//for (i in e.$el) {
				//	alert(i+':'+e.$el[i]);
				//}
				var $container = e.$el.closest('.acf-field');
				var $field = $container.attr('data-key');
				var $value = e.$el.val();
				//alert($field+' : '+$value);
				this.update($field, $value).fetch();
			},
	
			_custom_field_location_change_radio: function(e){
				//alert(typeof(e.$el));
				//for (i in e.$el) {
				//	alert(i+':'+e.$el[i]);
				//}
				// this fires on every radio button
				// if there are 5 buttons in radio field it will fire 5 times
				var $container = e.$el.closest('.acf-field');
				var $field = $container.attr('data-key');
				var $ul = e.$el.closest('ul');
				var $inputs = $ul.find('input');
				var $value = [];
				for (i=0; i<$inputs.length; i++) {
					if ($inputs[i].checked) {
						$value.push($inputs[i].value);
					}
				}
				//alert($field+' : '+$value);
				this.update($field, $value).fetch();
			},
	
			_custom_field_location_change_true_false: function(e){
				//alert(typeof(e.$el));
				//for (i in e.$el) {
				//	alert(i+':'+e.$el[i]);
				//}
				var $container = e.$el.closest('.acf-field');
				var $field = $container.attr('data-key');
				var $value = '';
				if (e.$el.prop('checked')) {
					$value = 'checked';
				}
				//alert($field+' : '+$value);
				this.update($field, $value).fetch();
			},
	
			_custom_field_location_change_checkox: function(e){
				//alert(typeof(e.$el));
				//for (i in e.$el) {
				//	alert(i+':'+e.$el[i]);
				//}
				// this will fire for every checkbox
				// if there are 5 choices in a checkbox field it will fire 5 times
				var $container = e.$el.closest('.acf-field');
				var $field = $container.attr('data-key');
				var $ul = e.$el.closest('ul');
				var $inputs = $ul.find('input');
				var $value = [];
				for (i=0; i<$inputs.length; i++) {
					if ($inputs[i].checked) {
						$value.push($inputs[i].value);
					}
				}
				//alert($field+' : '+$value);
				this.update($field, $value).fetch();
			}
		});
		
		// these actions cuase acf to think the inputs have changed
		// this causes the "Are you sure you want leave" messages
		// to trigger even if no changes are made
		$('.acf-field-select select').trigger('ready');
		$('.acf-field-checkbox input').trigger('ready');
		$('.acf-field-radio input').trigger('ready');
		$('.acf-field-true-false input').trigger('ready');
	});
	