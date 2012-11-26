<?php
/*
Plugin Name: isEngine Cross References
Plugin URI: http://houseoflaudanum.com/wordpress/
Description: Add cross-references to posts
Version: 0.302
Author: Wintermoss Snow
Author URI: http://houseoflaudanum.com/wordpress/


-	store object <-> xref relationships in xrefs table
-	retrieve and return xref'd ids, permalinks, titles ( extracts and cats )
-	menu list in admin panel to easily populate
	-	add
	-	remove
	
	
REVISIONS
0.302	show xref'd pages on edit screen
0.301	remove arbitrary posts from new and existing records
		show pages as well as posts
0.300	rewrite admin as jquery

0.209	add _create_xref
0.208	several admin side bugs
0.207	edit button
0.206	remove selected bug
0.205	remove test code in category sorter
0.204	if there are no xrefs return '' ( fixes sql error in the logs )


TO DO
-	rewrite admin as jquery with combo box and filter search
-	rewrite - provide categories using category search instead of storing a term id
-	rewrite - convert from xref table to postmeta

v	bidirectional xrefs (backrefs)
x	should really be using taxonomy
v	when you delete a post you need to delete all xrefs
-	how should the xrefs be sorted ?
x	we need to be able to have multiple predefined kinds of xrefs . 
	eg if we're going to use this to define which people are in which projects, 
	then we need `people' xrefs as well as `institutions' xrefs . these need to 
	be limited by category. this may force taxonomy. we need to be able to define
	the kinds of xrefs by category
v	also, the backend should `know' about backreferences, so that you don't need
	to flip back and forth to see if its linked correctly . perhaps just a section
	that says `backreferences' that isn't editable (maybe you should be able to 
	delete backrefs from here).
v	radio button order by
v	checkbox for show ID


*/
	global $is_xrefs_db_version;
	$is_xrefs_db_version = "0.21";
	$table_name = $wpdb->prefix . "is_xrefs";

	
	if ( is_admin() ) {

	//	register_activation_hook(__FILE__,'is_xrefs_install');
	//	register doesnt work in 2.6 why ?
		global $is_xrefs_db_version;
	
		$installed_ver = get_option("is_xrefs_db_version");
		
//		print "Version $installed_ver";
		
		if( $installed_ver != $is_xrefs_db_version ) {
			_createTable();
		}

		if ( $installed_ver == '' ) {
			_migrateData();
		}

		add_action('admin_head', 'is_xrefs_admin_print_scripts');
//		add_filter('posts_orderby', 'is_posts_orderby');
		wp_enqueue_script("is_xrefs_jquery", get_bloginfo('wpurl') . "/wp-content/plugins/is_xreferences/js/jquery-autocomplete/jquery.autocomplete.pack.js", array("jquery"), 0.1);
		wp_enqueue_script("is_xrefs_init", get_bloginfo('wpurl') . "/wp-content/plugins/is_xreferences/js/functions.js", array("prototype","scriptaculous","scriptaculous-controls","scriptaculous-dragdrop"), 0.1);
		add_action('edit_form_advanced', 'is_xref_edit_form_advanced');
		add_action('edit_page_form', 'is_xref_edit_form_advanced');

//	add_action('edit_post', 'is_xref_save_post'');
		add_action('publish_post', 'is_xref_save_post');
		add_action('save_post', 'is_xref_save_post');
		add_action('delete_post', 'is_xref_delete_post');
	}
	

function widget_is_xreferences_init() {
	if ( function_exists('register_sidebar_widget') ) {
		register_sidebar_widget(array("Cross-references", 'widget_is_xreferences'), 'widget_is_xreferences');
        register_widget_control(array('Cross-references', 'widgets'), 'widget_is_xreferences_control');
	}
}


/* the main function to be called by templates */
function is_XRefs($id=null, $term_id=null) {
	print is_getXRefs($id, $term_id);
}


function is_getXRefs($id=null, $term_ids=null) {
	global $wpdb;

	if ( ! isset($id) ) {
		global $post;
		$id = $post->ID;
	}
		
	$xrefs = is_xref_get_list($id, null, false);
	
	if ( $xrefs == '' )
		return '';
		
	$query = "SELECT ID,post_title
		FROM $wpdb->posts
		WHERE ID IN($xrefs)
	";

	$results = $wpdb->get_results($query);
	$return = '';
	
	if ( is_array($term_ids) )
		$terms = $term_ids;
	else
		$terms = split(",", $term_ids);
	if ( count($terms) < 2 ) {
		foreach ( $results as $r ) {
			$return .= "<li><a href='" . get_permalink($r->ID) . "'>$r->post_title</a></li>";
		}
	} else {
//	loop through the posts, assigning them to the term array
		$the_terms = array();
		foreach ( $results as $r ) {
//	the cats this post is in
			$the_cats = get_the_category($r->ID);

			foreach ( $the_cats as $c ) {
//	see if the post is in the cats we are interested in
				if ( in_array($c->term_id, $terms) ) {
//	create a container object for this category
					if ( ! $the_terms[$c->term_id] ) {
						$the_terms[$c->term_id] = new stdClass;
						
						$the_terms[$c->term_id]->name = $c->cat_name;
						$the_terms[$c->term_id]->slug = $c->category_nicename;
						$the_terms[$c->term_id]->posts = array();
					}
//	add this post to this category's array					
					array_push($the_terms[$c->term_id]->posts, $r);
				}
			}
		}
		
		
//	loop through the terms creating an UL for each term

		foreach ( $terms as $term ) {
			$t = $the_terms[$term];
			
			if ( ! $t || ! is_array($t->posts) ) {
				continue;
			}

			$loop = '';
			foreach ( $t->posts as $p ) {
				$loop .= "<li><a href='" . get_permalink($p->ID) . "'>$p->post_title</a></li>";
			}
			
			if ( $t->name != 'Unknown' )
				$return .= "<li class='xref xref-container'><h3>$t->name</h3><ul>$loop</ul></li>";
			else 
				$return .= $loop;
		}
	}
	
	return $return;
}


function is_xrefs_add() {
	$id = $_REQUEST['id'];
}


function is_xrefs_remove() {
	$id = $_REQUEST['id'];
}


/* called via ajax from the edit form while looking for xrefs to add */
function is_xrefs_filter() {
	$string = $_REQUEST['q'];
	
	if ( empty($string) )
		return;
		
//	print "OI " . $_REQUEST['string'];
	
	global $wpdb;
	
	$query = "
		SELECT ID, post_title, post_type, post_parent
			FROM $wpdb->posts
			WHERE post_type IN ('post', 'page')
			AND post_title LIKE '%$string%'
			ORDER BY post_title
			LIMIT 10";
	
//	print $query;
	
	$results = $wpdb->get_results($query);
	
//	print_r($results);
	
	$response = array();
	
	foreach ( $results as $r ) {
		$item = array();
		$item['id'] = $r->ID;
		$item['title'] = apply_filters('post_title', $r->post_title);
		$item['categories'] = array();
		
		if ( $r->post_type == 'post' ) {
			$terms = get_the_category($r->ID);
			$item['categories'] = array();
			foreach ( $terms as $t ) {
				array_push($item['categories'], $t->name);
			}
		} else if ( $r->post_type == 'page' ) {
			if ( $r->post_parent > 0 ) {
//				print "parent $r-<post_parent||\n";
				$parent = get_page($r->post_parent);
				$item['categories'][0] = ' &#155; ' . $parent->post_title;
			} else {
				$item['categories'][0] = 'Page';
			}
		}
//		print $r->post_title;
//		print json_encode(item);
//		array_push($response, $item);

//	need to encode title to preserve comma's
		print "$r->post_title|$r->ID|" . $item['categories'][0] . "\n";

	}
	
//	print '{' . json_encode($response) . '}';

	return;
}

	add_action('wp_ajax_is_xref_search', 'is_xrefs_filter'); 
	add_action('wp_ajax_is_xref_add', 'is_xrefs_add'); 
	add_action('wp_ajax_is_xref_remove', 'is_xrefs_remove'); 


/* the main function called by the edit actions */
function is_xref_edit_form_advanced() {
	global $post;
	$xrefs_list = array();
	
//	get any existing xrefs
	if ( $post->ID ) {
		$xrefs_list = is_xref_get_list($post->ID, 0, false);
	}
	
//	print_r($xrefs_list);

	_dbx_header("Cross-references");
	
	
?>

	<form onsubmit='return false;'>
		<div id='is_xrefs_list' class='tagchecklist' style="margin-bottom:40px">
<?php

	if ( $xrefs_list ) {
		$args = array(
			'include'	=>	$xrefs_list,
			'orderby'	=>	'title',
			'order'		=>	'ASC'
		);
	
		$posts = array_merge(get_posts($args), get_pages($args));
	
		foreach ( $posts as $p ) {
//	show existing xrefs with remove buttons	
			$title = apply_filters('post_title', $p->post_title);
			print "
				<span>
					<a title='Remove " . htmlspecialchars($title). "' class='ntdelbutton' rel='$p->ID'>X</a>&nbsp;$title
					<input type='hidden' name='is_xrefs[]' id='is_xrefs[]' value='$p->ID' />
				</span>"
			;
		}
	}
?>

<!-- the xref search field -->
		</div>
		<input type='text' id='xref_filter' name='xref_filter' value='' rel="<?php echo admin_url('admin-ajax.php?action=is_xref_search') ?>" />
		<label for='xref_filter'><em>Begin typing here to add a cross-reference</em></label>
	</form>
	
<?php
	_dbx_footer();
}


function widget_is_xreferences($args) {

		if ( ! is_single() && ! is_page() )
			return;
		
		$options = get_option('is_xrefs_widget');
		$title = $options['title'];
		$categorize = $options['categorize'];
		$terms = $options['terms'];

		$xrefs = is_getXRefs(null, $terms);
//	no references ? don't print the widget
		if ( $xrefs == '' ) {
			print "<!-- no xrefs terms $terms -->";
			return;
		}

		extract($args);

//		$title = "Cross-references";

		$this_script = '';
	   
		echo $before_widget;
		if ( $title != '' ) {
			echo $before_title
				. $title
				. $after_title;
		}

//	do stuff

		print "
		<style type='text/css'></style>
		<ul id='is_xrefs' class='xrefs'>
		";
		
		print $xrefs;
		
		print "
		</ul>
		";

		echo $after_widget;
}

	function widget_is_xreferences_control() {
		$options = $newoptions = get_option('is_xrefs_widget');
		if ( $_POST['is_xrefs-submit'] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST['is_xrefs-title']));
			$newoptions['terms'] = strip_tags(stripslashes($_POST['is_xrefs-terms']));
		}
//		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('is_xrefs_widget', $options);
	//	}
                       
                // For the show name option
		$tshowname = $options['tshowname'] ? 'checked="checked"' : '';

?>

<div style="text-align:right">
     <label for="is_xrefs-title" style="line-height:32px;display:block;"><?php _e('Widget title:', 'widgets'); ?> <input type="text" id="is_xrefs-title" name="is_xrefs-title" value="<?php echo wp_specialchars($options['title'], true); ?>" /></label>    
     <label for="is_xrefs-terms" style="line-height:32px;display:block;"><?php _e('Widget categories:', 'widgets'); ?> <input type="text" id="is_xrefs-terms" name="is_xrefs-terms" value="<?php echo wp_specialchars($options['terms'], true); ?>" /></label>
     <input type="hidden" name="is_xrefs-submit" id="is_xrefs-submit" value="1" />
     </div>

<?php
	}



function is_xref_save_post_new($id) {
	if ( wp_is_post_revision($id) || wp_is_post_autosave($id) )
		return;

	$xrefs = $_REQUEST['is_xrefs'];
	$old_xrefs = get_post_meta($id, '_xref');

	$to_add = array_diff($xrefs, $old_xrefs);
	$to_remove = array_diff($old_xrefs, $xrefs);

	foreach ( $to_add as $x ) {
		add_post_meta($id, '_xref', $x);
		add_post_meta($x, '_xref', $id);
	}
	
	foreach ( $to_remove as $x ) {
		delete_post_meta($id, '_xref', $x);
		delete_post_meta($x, '_xref', $id);
	}
}


function is_xref_save_post($id) {
	global $wpdb, $table_name;

	if ( wp_is_post_revision($id) || wp_is_post_autosave($id) )
		return;

	$xrefs = $_REQUEST['is_xrefs'];	
	$terms = $_REQUEST['is_xrefs_cat'];	
	
	//Added on 16-06-2009 - Krishan
	//Values do not get passed to autosave through ajax
	if ( is_array($xrefs) ) {
	//	print_r($xrefs);
	//	print_r($terms);
	//	exit;
	
	//	remove all existing xrefs	
		is_xref_delete_post($id);
	
		$query = "INSERT INTO $table_name (object_id, xref_id, term_id) VALUES ";
		$arr = array();
		$i = 0;
		 
		foreach ( $xrefs as $x ) {
			if ( $x != '' && $x = $x + 0 ) {
				$t = $terms[$i];
				if ( ! $t ) $t = 0;
				if ( $id < $x )
					array_push($arr, "($id, $x, $t)");
				else
					array_push($arr, "($x, $id, $t)");
			}
			$i++;
		}
		$query .= implode($arr, ",");
		
	//	print $query;
	//	exit;
		$results = $wpdb->get_results($query);
	} else {
//		print "no xrefs";
		is_xref_delete_post($id);
	}
}


function is_createXRef($object_id, $xref_id, $term_id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "is_xrefs";
		$query = "
			INSERT INTO $table_name
			(object_id, xref_id, term_id) 
			VALUES ($object_id, $xref_id, $term_id)
		";
		$results = $wpdb->get_results($query);
		return $results;
}


function is_xref_delete_post($id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "is_xrefs";
	
	$query = "DELETE
		FROM $table_name
		WHERE object_id = $id
		OR xref_id = $id
	";

	$results = $wpdb->get_results($query);
}


function is_xref_get_list($id, $term_id=null, $obj=false) {
	global $wpdb;
	$table_name = $wpdb->prefix . "is_xrefs";
	
/*
	$query = "SELECT object_id, xref_id
		FROM $table_name
		WHERE object_id = $id
		OR xref_id = $id
	";
*/

	if ( $term_id ) {
		$and = "AND term_id IN ($term_id)";
	}

	$query = "SELECT object_id, term_id
		FROM $table_name
		WHERE xref_id = $id
		$and
	UNION
		SELECT xref_id, term_id
		FROM $table_name
		WHERE object_id = $id
		$and
	";
	
//	print $obj;
		
//	return expects a comma separated list of integers
	$results = $wpdb->get_results($query);

//	we just want the object, so return now.
	if ( $obj ) {	
//		print 'Object';
		return $results;
	}
	
//	we want a comma separated list
	$arr = array();
	foreach ( $results as $r ) {
		if ( $r->object_id )
			array_push($arr, $r->object_id);
		else
			array_push($arr, $r->xref_id);
	}
	
	return implode($arr, ",");
	
}


function is_xref_get_list_new($id, $term_id=null, $obj=false) {
	global $wpdb;
	
	$xrefs = get_post_meta($id, '_xref');
	
//	legacy - get the old xrefs and convert them to the new style
	if ( empty($xrefs) ) {
	
		$table_name = $wpdb->prefix . "is_xrefs";
	

		if ( $term_id ) {
			$and = "AND term_id IN ($term_id)";
		}

		$query = "SELECT object_id, term_id
			FROM $table_name
			WHERE xref_id = $id
			$and
		UNION
			SELECT xref_id, term_id
			FROM $table_name
			WHERE object_id = $id
			$and
		";
	
		
//	return expects a comma separated list of integers
		$results = $wpdb->get_results($query);

		foreach ( $results as $r ) {
			if ( $r->object_id == $id ) {
				add_post_meta($id, '_xref', $r->xref_id);
				add_post_meta($r->xref_id, '_xref', $id);
			} else {
				add_post_meta($id, '_xref', $r->object_id);
				add_post_meta($r->object_id, '_xref', $id);
			}
		}
		
		$xrefs = get_post_meta($id, '_xref');
	}
	
//	create an object array and fill it with xrefs (in the category if required)
	$arr = array();
	if ( ! is_array($xrefs) ) {
		return null;
	}
	
	foreach ( $xrefs as $x ) {
		$t = get_the_category($x);
//	if we've been given a key then find it in the array of categories
		unset($t_key);
		if ( $term_id ) {
			$t_key = array_search($term_id, $t);
		}
		if ( $t_key )
			$t = $t[$t_key];
		else
			$t = $t[0]->term_id;

//	if a term has been specified and this is not it do not add it to the array		
		if ( $term_id && $term_id != $t )
			next;
		
		$r = new stdclass;
		$r->object_id = $x;
		$r->term_id = $t;
		array_push($arr, $r);
	}
	
//	we just want an object array so return that now.
	if ( $obj ) {	
//		print 'Object';
		return $arr;
	} else {
		$list = array();
		foreach ( $arr as $x ) {
			array_push($list, $x->object_id);
		}
		return implode(',', array_unique($list));
	}
	
}


function _getPostTitles() {
	global $wpdb;
	
	$query = "SELECT ID,post_title
		FROM $wpdb->posts
		WHERE post_status = 'publish'
		ORDER BY post_title";
	
	$results = $wpdb->get_results($query);
//	print_r($results);
	
	return $results;
}


function _dbx_header($title) {
	if (substr($GLOBALS['wp_version'], 0, 3) >= '2.5') { 
		echo "
		<div id='post_xrefs' class='postbox open'>
		<h3>$title</h3>
		<div class='inside'>
		<div id='postaiosp'>
		";
	} else {
		echo "
		<div class='dbx-b-ox-wrapper'>
		<fieldset id='seodiv' class='dbx-box'>
		<div class='dbx-h-andle-wrapper'>
		<h3 class='dbx-handle'>Cross References</h3>
		</div>
		<div class='dbx-c-ontent-wrapper'>
		<div class='dbx-content'>
		";
	}
}


function _dbx_footer() {
	if (substr($GLOBALS['wp_version'], 0, 3) >= '2.5') { 
		echo '
		</div></div></div>
		';
	} else {
		echo '	
		</div>
		</fieldset>
		</div>
		';
	}
}


function is_xrefs_install() {
	global $is_xrefs_db_version;

	print "installing";
//	exit;
	
	$installed_ver = get_option("is_xrefs_db_version");
	if( $installed_ver != $is_xrefs_db_version ) {
		_createTable();
	}

	if ( $installed_ver == '' ) {
		_migrateData();
	}

}


function _createTable() {
	global $wpdb, $is_xrefs_db_version;

	$table_name = $wpdb->prefix . "is_xrefs";
	if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			object_id mediumint(9) DEFAULT '0' NOT NULL,
			xref_id mediumint(9) DEFAULT '0' NOT NULL,
			term_id mediumint(9) DEFAULT '0',
			menu_order mediumint(9) DEFAULT '0',
			UNIQUE KEY id (id)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		$result = dbDelta($sql);		
	} else {

	}
	update_option("is_xrefs_db_version", $is_xrefs_db_version);
}


function _migrateData() {
	global $wpdb;

//	get all xref data from postmeta
	$query = "SELECT * FROM $wpdb->postmeta
		WHERE meta_key = '_is_xrefs'
	";

	$meta = $wpdb->get_results($query);

//	add it to the new table
	$table_name = $wpdb->prefix . "is_xrefs";
	
	$query = "INSERT INTO $table_name (object_id, xref_id) VALUES ";
	
	$arr = array();
	
	foreach ( $meta as $m ) {
//	print $m->post_id;
		if ( $m->meta_value != '' ) {
			if ( $m->post_id < $m->meta_value )
				array_push($arr, "($m->post_id, $m->meta_value)");
			else
				array_push($arr, "($m->meta_value, $m->post_id)");
		}
	}

	$query .= implode($arr, ",");
	
	$results = $wpdb->get_results($query);
	
//	remove it from postmeta


}


function debug($msg) {
	if ( $_REQUEST['debug'] ) 
		echo "$msg<br />\n";
}


	function is_xrefs_admin_print_scripts() {
		
		if ( ! is_admin() ) 
			return;
			
		print "
		<script type='text/javascript'>
//	path to ajax url
			var ajaxurl = '" . admin_url('admin-ajax.php') . "';
		</script>
		
		<style type='text/css'>
		</style>
		";			
	}


	add_action('widgets_init', 'widget_is_xreferences_init');

?>