	
	jQuery(document).ready(function($){
		if (typeof acf == 'undefined') { return; }
	
		var acfCustomFieldLocatioRule = acf.ajax.extend({
			events: {
				'change .acf-field-select select': '_custom_field_location_change_select',
				'change .acf-field-radio input': '_custom_field_location_change_radio',
				'change .acf-field-true-false input': '_custom_field_location_change_true_false',
				'change .acf-field-checkbox input': '_custom_field_location_change_checkox',
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
	
		$('.acf-field-select select').trigger('change');
		$('.acf-field-checkbox input').trigger('change');
		$('.acf-field-radio input').trigger('change');
		$('.acf-field-true-false input').trigger('change');
	});
	