jQuery(document).ready(function() {

/*	set our search filter to autocomplete */
	var url = jQuery('#xref_filter').attr('rel');
	jQuery('#xref_filter').autocomplete( url ,
		{
			width: 520,
			max: 10,
			scroll: false,
			scrollHeight: 300,
//	check for missing results and optionally add the category label	
			formatItem: function(data, i, n, value) {
				if ( ! data[1] )
					return "<small>[Nothing found]</small>";
				else if ( data[2] )
					return value + ' <small>[' + data[2] + ']</small>';
				else
					return value;
			},
			junk: null
		}
	);
	

/*	called when the user selects an autocompleted option */
	jQuery('#xref_filter').result(function(event, data, formatted) {
		_addXref(data);
		jQuery('#xref_filter').attr('value', '');
		return false;
	});


//	create a new line item in the edit pages
//	save it to the database
	_addXref = function(data) {
		id = data[1];
		if ( ! id )
			return false;
			
		span = document.createElement('span');
		span.setAttribute('id', 'xref_' + id);

		txt = document.createTextNode('\u00a0' + data[0]);

		a = document.createElement('a');
		a.setAttribute('rel', data[1]);
		a.setAttribute('class', 'ntdelbutton');
		a.className = 'ntdelbutton';
		a_val = document.createTextNode('X');
		a.appendChild(a_val);
				
//	add a hidden field that stores the result for the save action
		hidden = document.createElement('input');
		hidden.setAttribute('type', 'hidden');
		hidden.setAttribute('name', 'is_xrefs[]');
		hidden.setAttribute('id', 'is_xrefs[]');
		hidden.setAttribute('value', id);

		span.appendChild(a);
		span.appendChild(txt);
		span.appendChild(hidden);

		jQuery('#is_xrefs_list').append(span);
		_update_events();

		return false;
	}
	
//	call to add click handlers to xrefs
	_update_events = function() {
		jQuery('#is_xrefs_list span a.ntdelbutton').click(
			function() {
				id = jQuery(this).attr('rel');
				jQuery(this).parent('span').remove();
			}
		);
	}
	
	_update_events();
});