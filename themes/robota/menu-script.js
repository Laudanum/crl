$(document).ready(function(){
	
	$('#nav li > ul.non-current-ul').each(function(i) {
		// Find this list's parent list item.
		var parent_li = $(this).parent('li');
		var toggled = false;
		// Temporarily remove the list from the
		// parent list item, wrap the remaining
		// text in an anchor, then reattach it.
		var sub_ul = $(this).remove();
		
		parent_li.wrapInner('<a/>').find('a').click(function() {
			// Make the anchor toggle the leaf display.
			sub_ul.toggle();
			if (toggled) {
				toggled = false;
				$(this).parent('li.nav-second-li').css('background-color','#FFFFFF');
				$(this).parent('li.nav-second-li').css('font-weight','normal');
			} else {
				toggled = true;
				$(this).parent('li.nav-second-li').css('background-color','#E8F7FF');
				$(this).parent('li.nav-second-li').css('font-weight','bold');
			}

		});
		parent_li.append(sub_ul);
	});


	$('#nav li > ul.current-ul').each(function(i) {
		// Find this list's parent list item.
		var parent_li = $(this).parent('li');
		var toggled = true;
		// Temporarily remove the list from the
		// parent list item, wrap the remaining
		// text in an anchor, then reattach it.
		var sub_ul = $(this).remove();
		
		parent_li.wrapInner('<a/>').find('a').click(function() {
			// Make the anchor toggle the leaf display.
			sub_ul.toggle();
			if (toggled) {
				toggled = false;
				$(this).parent('li.nav-second-li').css('background-color','#FFFFFF');
				$(this).parent('li.nav-second-li').css('font-weight','normal');
			} else {
				toggled = true;
				$(this).parent('li.nav-second-li').css('background-color','#E8F7FF');
				$(this).parent('li.nav-second-li').css('font-weight','bold');
			}

		});
		parent_li.append(sub_ul);
	});

	$('ul ul').hide();
	$('.current-nav').show();
	$('span.nav-top-li').css('background-image','url(/images/layout/color-gradient.gif)');
	$('span.nav-top-li').css('background-position','bottom');
	$('span.nav-top-li').css('background-repeat','no-repeat');

	$('.current-ul').show();	
	$('.current-ul').parent('li').css('background-color','#E8F7FF');
	$('.current-ul').parent('li').css('font-weight','bold');
	var currChildren = 0;
	$('.current-ul').children().each(function(i) {
		if ($(this).attr('class')=='current-child') {
			currChildren++;
		}
		
	});
	if (currChildren == 0) {
		$('.current-ul li:first-child').addClass('current-child')
	}
	
});