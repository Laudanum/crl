$(document).ready(function(){	$('ul#menu-project-menu > li > a').click(function(){		$('.sub-menu', $(this).parents('li')).slideToggle('fast');		return false;	});});