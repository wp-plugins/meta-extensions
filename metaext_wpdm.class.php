<?php
if (!function_exists('download_shortcode')) return;

define( 'metaext_WPDM', true);

class metaext_wpdm {

	function get_metaext_downloadlink($id) {
		return metaext_wpdm::downloadmanager_showdownloadlink($id);
	}

	function show_metaext_downloadlink($id) {
		echo metaext_wpdm::downloadmanager_showdownloadlink($id);
	}

/*

	Do not use the functions below this line directly. They are what handle the
	interaction for the plugin with the administrative features.
	
*/

	function get_metaext_downloaddropdown($currid = '', $cat = '') {
		// generate option list for edit dialog
		return metaext_wpdm::get_downloadmanager_dropdown($currid, $cat);
	}

	function get_downloadmanager_brochurecatid() {
		$dl_cats = get_option('download_categories');
		$dl_catids = array_flip($dl_cats);
		return $dl_catids['Brochure'];
	}

	function _dlcat($id = '') {
		$dl_cats = get_option('download_categories');
		return $dl_cats[intval($id)];
	}

	function get_downloadmanager_dropdown($currid = '',$cat = '') {
		global $wpdb;
		$currids = explode(',',$currid);
		$cat_sel = "";
		if ($cat) {
			$cat_sel = " AND file_category = '" . intval($cat) . "' ";
		}
		$files = $wpdb->get_results("SELECT * FROM $wpdb->downloads WHERE file_permission != -2 ${cat_sel} ORDER BY 'file_name' ASC ");
		$retVal = '';
		if($files) {
			foreach($files as $file) {
				$retVal .= '<option value="'.$file->file_id.'" ';
				if (in_array($file->file_id, $currids)) $retVal .= "selected='selected' ";
					$retVal .=  '>'.$file->file_name. ' -- ' . metaext_wpdm::_dlcat($file->file_category) . '</option>'."\n\t"; 
			}
		}
		return $retVal;
	}

	function downloadmanager_showdownloadlink($id = '0') {
		if ($id == '0') return;
		return download_shortcode( array('id' => $id, 'display' => 'both') );
	}
}
?>