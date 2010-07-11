<?php
/*
Plugin Name: Meta Extension
Plugin URI: http://www.premiumdigitalservices.net
Description: Plugin system for adding new fields to the edit post page including integration for NextGen Gallery, Wordtube and other popular content plugins.
Author: Scott E. Royalty
Version: 1.0.3
Author URI: http://www.premiumdigitalservices.net
*/ 

/*
Licensed under the MIT License
Copyright (c)  2010 Scott E. Royalty

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the
Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to
do so, subject to the following conditions:

The above copyright notice and this permission notice shall
be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

include_once( 'metaext.class.php' );
include_once( 'metaext_ngg.class.php' );
include_once( 'metaext_wt.class.php' );
include_once( 'metaext_wpdm.class.php' );

define( 'MEXTHOMEURL', WP_CONTENT_URL .'/plugins/' .  dirname(plugin_basename(__FILE__) ) . '/');


function metaext_add_adminhead( ) {
	echo "\n".'<style type="text/css" media="screen">@import "'. MEXTHOMEURL .'admin.css";</style>'."\n";
}
add_action ('admin_head','metaext_add_adminhead');

add_action( 'simple_edit_form', array( 'metaext', 'insert_gui' ) );
add_action( 'edit_form_advanced', array( 'metaext', 'insert_gui' ) );
add_action( 'edit_post', array( 'metaext', 'edit_meta_value' ) );
add_action( 'save_post', array( 'metaext', 'edit_meta_value' ) );
add_action( 'publish_post', array( 'metaext', 'edit_meta_value' ) );

?>