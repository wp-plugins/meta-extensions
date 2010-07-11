<?php
/*

*/
class metaext {

	/*
		Description: Builds a list of posts with the specified field name given.
		$limit: the max amount of posts to return.
	*/
	function get_single_metaext($meta_name, $limit = 5, $sort = 'random') {
		$limit = (int) $limit;
		// FIXME : Find a nice way to randomize when I am not too lazy to look at wordpress more.
		return query_posts('tag='.$meta_name.'&posts_per_page='.$limit.'');
	}

	/*
		Description: Builds a list of posts with the specified field names given.
		$meta_names: An array of field names to include.
		$limit: the max amount of posts to return.
	*/
	function get_multi_metaext($meta_names, $limit = 5, $sort = 'random') {
		$limit = (int) $limit;
		$query_string = '';
		foreach ($meta_names as $index => $tag)
		{
			$query_string .= 'tag='.$tag.'&';
		}
		$query_string .= 'posts_per_page='.$limit.'';
		
		// FIXME : Find a nice way to randomize when I am not too lazy to look at wordpress more.
		return query_posts($query_string);
	}
	
	/*
		Description: Returns the value of a field. Null if does not exist
		$the_ID: The Post ID to check
		$name: Field name to get the value for
	*/
	function get_value_metaext($the_ID, $name, $single = true) {
		$retVal = get_post_meta($the_ID, $name, $single);
		if (!$retVal || $retVal == '')
			$retVal = null;
		return $retVal;
	}
	
	
/*

	Do not use the functions below this line directly. They are what handle the
	interaction for the plugin with the administrative features.
	
*/
	function sanitize_name( $name ) {
		$name = sanitize_title( $name ); // taken from WP's wp-includes/functions-formatting.php
		$name = str_replace( '-', '_', $name );

		return $name;
	}
  
	function load_conf_file() {
		$file = get_template_directory() . '/conf.txt';
		if ( !file_exists( $file ) )
		{
			$file = dirname( __FILE__ ) . '/conf.txt';
		}
		if ( !file_exists( $file ) )
			return null;
			
		$custom_fields = parse_ini_file( $file, true );
		return $custom_fields;
	}
  
  function make_textfield( $name, $size = 25, $subtitle = '' ) {
    $title = $name;
    $name = 'metaext_' . metaext::sanitize_name( $name );
    
    if( isset( $_REQUEST[ 'post' ] ) ) {
      $value = get_post_meta( $_REQUEST[ 'post' ], $title );
      $value = $value[ 0 ];
    }

	$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';
    
    $out = 
      '<tr>' .
      '<th scope="row">' . $title . ' </th>' .
      '<td> <input id="' . $name . '" name="' . $name . '" value="' . attribute_escape($value) . '" type="textfield" size="' . $size . '" />'.$subtitle.'</td>' .
      '</tr>';
    return $out;
  }
  
  function make_checkbox( $name, $default, $subtitle = '' ) {
    $title = $name;
    $name = 'metaext_' . metaext::sanitize_name( $name );
    
    if( isset( $_REQUEST[ 'post' ] ) ) {
      $checked = get_post_meta( $_REQUEST[ 'post' ], $title );
      $checked = $checked ? 'checked="checked"' : '';
    }
    else {
      if ( isset( $default ) && trim( $default ) == 'checked' ) {
        $checked = 'checked="checked"';
      }    
    }

	$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';
    
    $out =
      '<tr>' .
      '<th scope="row" valign="top">' . $title. ' </th>' .
      '<td>';
      
    $out .=  
      '<input class="checkbox" name="' . $name . '" value="true" id="' . $name . '" "' . $checked . '" type="checkbox" />'.$subtitle;
       
    $out .= '</td>';
    
    return $out;
  }
  
  function make_radio( $name, $values, $default, $subtitle = '' ) {
    $title = $name;
    $name = 'metaext_' . metaext::sanitize_name( $name );
    
    if( isset( $_REQUEST[ 'post' ] ) ) {
      $selected = get_post_meta( $_REQUEST[ 'post' ], $title );
      $selected = $selected[ 0 ];
    }
    else {
      $selected = $default;
    }

	$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';
	
    $out =
      '<tr>' .
      '<th scope="row" valign="top">' . $title . ' </th>' .
      '<td>';
    
    foreach( $values as $val ) {
      $id = $name . '_' . metaext::sanitize_name( $val );
      
      $checked = ( trim( $val ) == trim( $selected ) ) ? 'checked="checked"' : '';
      
      $out .=  
        '<label for="' . $id . '" class="selectit"><input id="' . $id . '" name="' . $name . '" value="' . $val . '" "' . $checked . '" type="radio"> ' . $val . '</label><br>';
    }   
    $out .= $subtitle.'</td>';
    
    return $out;      
  }
  
    function make_gallery_select( $name, $subtitle = '' ) {
	if (!metaext_NGG)
		return;
    $title = $name;
    $name = 'metaext_' . metaext::sanitize_name( $name );
    $selected = '';
	
    if( isset( $_REQUEST[ 'post' ] ) ) {
      $selected = get_post_meta( $_REQUEST[ 'post' ], $title );
      $selected = $selected[ 0 ];
    }

	$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';
    
    $out =
      '<tr>' .
      '<th scope="row">' . $title . ' </th>' .
      '<td>' .
      '<select name="' . $name . '">' .
      '<option value="">Select Gallery</option>';
    
	$out .= metaext_ngg::get_metaext_gallerydropdown($selected);

    $out .= '</select>'.$subtitle.'</td>';
    
    return $out;
  }

	function make_video_select( $name, $subtitle = '' ) {
		if (!metaext_WT)
			return;
		$title = $name;
		$name = 'metaext_' . metaext::sanitize_name( $name );
		$selected = '';
		
		if( isset( $_REQUEST[ 'post' ] ) ) {
		  $selected = get_post_meta( $_REQUEST[ 'post' ], $title );
		  $selected = $selected[ 0 ];
		}
		
		$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';
	
		$out =
		  '<tr>' .
		  '<th scope="row">' . $title . ' </th>' .
		  '<td>' .
		  '<select name="' . $name . '">' .
		  '<option value="">Select Video</option>';
		
		$out .= metaext_wt::get_metaext_videodropdown($selected);

		$out .= '</select>'.$subtitle.'</td>';
		
		return $out;
	}

	function make_download_select( $name, $subtitle = '' ) {
		if (!metaext_WPDM)
			return;
		$title = $name;
		$name = 'metaext_' . metaext::sanitize_name( $name );
		$selected = '';
		
		if( isset( $_REQUEST[ 'post' ] ) ) {
		  $selected = get_post_meta( $_REQUEST[ 'post' ], $title );
		  $selected = $selected[ 0 ];
		}
		
		$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';

		$out =
		  '<tr>' .
		  '<th scope="row">' . $title . ' </th>' .
		  '<td>' .
		  '<select name="' . $name . '">' .
		  '<option value="">Select File For Download</option>';
		
		$out .= metaext_wpdm::get_metaext_downloaddropdown($selected);

		$out .= '</select>'.$subtitle.'</td>';
		
		return $out;
	}
	
	function make_select( $name, $values, $default, $subtitle = '' ) {
		$title = $name;
		$name = 'metaext_' . metaext::sanitize_name( $name );
    
		if( isset( $_REQUEST[ 'post' ] ) ) {
			$selected = get_post_meta( $_REQUEST[ 'post' ], $title );
			$selected = $selected[ 0 ];
		} else {
			$selected = $default;
		}
    
		$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';

		$out =
			'<tr>' .
			'<th scope="row">' . $title . ' </th>' .
			'<td>' .
			'<select name="' . $name . '">' .
			'<option value="">Select</option>';
      
		foreach( $values as $val ) {
			$checked = ( trim( $val ) == trim( $selected ) ) ? 'selected="selected"' : '';
    
			$out .=
				'<option value="' . $val . '" ' . $checked . ' >' . $val. '</option>'; 
		}
		
		$out .= '</select>'.$subtitle.'</td>';
		return $out;
	}
  
  function make_textarea( $name, $rows, $cols, $subtitle = '' ) {
    $title = $name;
    $name = 'metaext_' . metaext::sanitize_name( $name );
    
    if( isset( $_REQUEST[ 'post' ] ) ) {
      $value = get_post_meta( $_REQUEST[ 'post' ], $title );
      $value = $value[ 0 ];
    }
    
	$subtitle = '<br /><span class="metaext-span">'.$subtitle.'</span>';

    $out = 
      '<tr>' .
      '<th scope="row" valign="top">' . $title . ' </th>' .
      '<td> <textarea id="' . $name . '" name="' . $name . '" type="textfield" rows="' .$rows. '" cols="' .$cols. '">' .attribute_escape($value). '</textarea>'.$subtitle.'</td>' .
      '</tr>';
    return $out;
  }

	function insert_gui() {
		$fields = metaext::load_conf_file();

		if( $fields == null)
			return;

		$out = '<div id="metaext-container" class="postbox" >';
		$out .= '<div class="handlediv" title="Click to toggle"><br /></div><h3 class="hndle"><span>' . get_template() . ' Custom Meta Extension Fields</span></h3>';
		$out .= '<div class="inside">';
		$out .= '<input type="hidden" name="metaext-verify-key" id="metaext-verify-key" value="' . wp_create_nonce('metaext') . '" />';
		$out .= '<table class="metaext-editform">';
/*		$out = '<div id="metaext-container"><h2>Custom Meta Extension Fields</h2>';
		$out .= '<input type="hidden" name="metaext-verify-key" id="metaext-verify-key" value="' . wp_create_nonce('metaext') . '" />';
		$out .= '<table class="metaext-editform">';
*/
		foreach( $fields as $title => $data ) {
			switch($data[ 'type' ])
			{
			case 'textfield' : $out .= metaext::make_textfield( $title, $data[ 'size' ], $data[ 'subtitle' ] ); break;
			case 'checkbox'  : $out .= metaext::make_checkbox( $title, $data[ 'default' ], $data[ 'subtitle' ] ); break;
			case 'radio'     : $out .= metaext::make_radio( $title, explode( '#', $data[ 'value' ] ), $data[ 'default' ], $data[ 'subtitle' ] ); break;
			case 'select'    : $out .= metaext::make_select( $title, explode( '#', $data[ 'value' ] ), $data[ 'default' ], $data[ 'subtitle' ] ); break;
			case 'textarea'  : $out .= metaext::make_textarea( $title, $data[ 'rows' ], $data[ 'cols' ], $data[ 'subtitle' ] ); break;
			case 'gallery'   : $out .= metaext::make_gallery_select( $title, $data[ 'subtitle' ] ); break;
			case 'download'  : $out .= metaext::make_download_select( $title, $data[ 'subtitle' ] ); break;
			case 'video'     : $out .= metaext::make_video_select( $title, $data[ 'subtitle' ] ); break;
			}
		}

		$out .= '</table></div></div>';
		echo $out;
	}
  
  function edit_meta_value( $id ) {
    global $wpdb;
        
    if( !isset( $id ) )
      $id = $_REQUEST[ 'post_ID' ];
    
    
    if( !current_user_can('edit_post', $id) )
        return $id;
        
    if( !wp_verify_nonce($_REQUEST['metaext-verify-key'], 'metaext') )
        return $id;
    
    $fields = metaext::load_conf_file();
    
    if ( $fields == null )
    	return;
    
	$taglist = array();
	
    foreach( $fields as $title  => $data) {
      $name = 'metaext_' . metaext::sanitize_name( $title );
      $title = $wpdb->escape(stripslashes(trim($title)));
      
      $meta_value = stripslashes(trim($_REQUEST[ "$name" ]));
      if( isset( $meta_value ) && !empty( $meta_value ) ) {
        delete_post_meta( $id, $title );
        
        if( $data[ 'type' ] == 'textfield' || 
            $data[ 'type' ] == 'radio'  ||
            $data[ 'type' ] == 'select' || 
            $data[ 'type' ] == 'textarea' || 
            $data[ 'type' ] == 'video' || 
            $data[ 'type' ] == 'download' || 
			$data[ 'type' ] == 'gallery') {
          add_post_meta( $id, $title, $meta_value );
        }
        else if( $data[ 'type' ] == 'checkbox' )
		{
			add_post_meta( $id, $title, 'true' );
			if ( $data['category_id'] && $data['category_name'] )
			{
				if ( category_exists($data['category_name']) )
				{
					$existing_cats = wp_get_post_categories( $id );
					$cat_list = array_merge($existing_cats, array($data['category_id']));
					wp_set_post_categories( $id, $cat_list );
				}
			}
		}
		
		if ( !$data['tag'] )
			continue;
		switch ($data['type'])
		{
			case 'textfield' : $taglist[] = $title; break;
			case 'checkbox'  : $taglist[] = $title; break;
			case 'radio'     : $taglist[] = $meta_value; break;
			case 'select'    : $taglist[] = $meta_value; break;
			case 'textarea'  : $taglist[] = $title; break;
		}
      }
      else {
        delete_post_meta( $id, $title );
      }
    }
	
	if (count($taglist) > 0) {
		wp_set_post_tags($id, $taglist);
	} else {
		wp_set_post_tags($id, array());
	}
  }
  
}

?>