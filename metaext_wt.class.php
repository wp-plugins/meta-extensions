<?php

if (!class_exists('wordtube')) return;

define( 'metaext_WT', true);

class metaext_wt {

	function get_metaext_wtvideo($videoid) {
		global $WPwordTube;
		return wt_GetVideo($videoid);
	}

	function show_metaext_wtvideo($videoid) {
		global $WPwordTube;
		echo wt_GetVideo($videoid);
	}

/*

	Do not use the functions below this line directly. They are what handle the
	interaction for the plugin with the administrative features.
	
*/

	function get_metaext_videodropdown($currid = '') {
		return metaext_wt::get_wordtube_videodropdown($currid);
	}

	function get_wordtube_videodropdown($currid = '') {
		global $wpdb;
		if (!$wpdb->wordtube) return;

		$tables = $wpdb->get_results("SELECT * FROM $wpdb->wordtube ORDER BY 'vid' ASC ");
		$retVal = '';
		if($tables) {
			foreach($tables as $table) {
				$retVal .= '<option value="'.$table->vid.'" ';
				if ($table->vid == $currid) $retVal .= "selected='selected' ";
					$retVal .= '>'.$table->name.'</option>'."\n\t"; 
			}
		}
		return $retVal;
	}

}

?>

