<?php

if (! (class_exists('nggallery') || class_exists('nggGallery')) ) return;

define( 'metaext_NGG', true);

class metaext_ngg {

	function get_metaext_firstpic($galleryid) {
		return metaext_ngg::nextgengallery_getfirstpic($galleryid);
	}

	function show_metaext_firstpic($galleryid, $class = '') {
		return metaext_ngg::nextgengallery_showfirstpic($galleryid,$class);
	}

	function show_metaext_gallery($galleryid) {
		if (!$galleryid) return;
		echo nggShowGallery($galleryid);
	}
 
/*

	Do not use the functions below this line directly. They are what handle the
	interaction for the plugin with the administrative features.
	
*/

	function get_metaext_gallerydropdown($currid = '') {
		return metaext_ngg::get_nextgengallery_dropdown($currid);
	}

	function get_nextgengallery_dropdown($currid = '') {
		global $wpdb;
		if (!$wpdb->nggallery) return;

		$tables = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY 'name' ASC ");
		$retVal = '';
		if($tables) {
			foreach($tables as $table) {
				$retVal .= '<option value="'.$table->gid.'" ';
				if ($table->gid == $currid) $retVal .= "selected='selected' ";
			    $retVal .= '>'.$table->name.'</option>'."\n\t"; 
			}
		}
		return $retVal;
	}

	function nextgengallery_getfirstpic($galleryid) {
		global $wpdb;
		global $ngg_options;
		if (!$galleryid) return;

		if (!$wpdb->nggallery) return;

		if (! $ngg_options) {
			$ngg_options = get_option('ngg_options');
		}

		$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] LIMIT 1");
		if ($picturelist) { 
			$pid = $picturelist[0]->pid;
			if (is_callable(array('nggGallery','get_image_url'))) {
				// new NextGen 1.0+
				return nggGallery::get_image_url($pid);
			} else {
				// backwards compatibility - NextGen below 1.0
				return nggallery::get_image_url($pid);
			}
		}
	}
	
	function nextgengallery_showfirstpic($galleryid, $class = '') {
		global $wpdb;
		global $ngg_options;
		if (!$galleryid) return;

		if (!$wpdb->nggallery) return;

		if (! $ngg_options) {
			$ngg_options = get_option('ngg_options');
		}

		$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] LIMIT 1");
		if ($class) $myclass = ' class="'.$class.'" ';
		if ($picturelist) { 
			$pid = $picturelist[0]->pid;
			if (is_callable(array('nggGallery','get_thumbnail_url'))) {
				// new NextGen 1.0+
				$out = '<img alt="' . __('property photo') . '" src="' . nggGallery::get_thumbnail_url($pid) . '" ' . $myclass . ' />';
			} else {
				// backwards compatibility - NextGen below 1.0
				$out = '<img alt="' . __('property photo') . '" src="' . nggallery::get_thumbnail_url($pid) . '" ' . $myclass . ' />';
			}
			return $out;
		}
	}

	// for RSS feeds
	function nextgengallery_picturelist($galleryid) {
		if (!$galleryid) return;

		global $wpdb;
		global $ngg_options;
		if (!$wpdb->nggallery) return;

		$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] ");
		if ($picturelist) { 
			return $picturelist;
		}
	}
}

?>