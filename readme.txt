=== Plugin Name ===
Contributors: sroyalty
Donate link: http://www.premiumdigitalservices.net/blog
Tags: meta, meta fields, custom fields, fields, posts
Requires at least: 2.0.2
Tested up to: 3.0
Stable tag: 1.0.3

Allows adding custom form fields to posts, storing them in custom meta fields. Integrates NGG, WT, and WP-DM.

== Description ==

This plugin takes a simple concept of giving you fields to enter information that use the meta tags built into 
Wordpress and expands on it. Modifying conf.txt in the plugin directory will allow you to enter custom fields to 
the Posts pages to add textfields, textarea, dropdown selections, checkboxes, and radio selections.

On top of all this simplicity, it also integrates several popular Wordpress plugins. With gallery selection for 
NextGen Gallery you can select a gallery to save the id to a meta field for your post to pull in your theme. Support 
for WordTube video selection is also added. WP-Download Manager is also supported in this initial release to get ids 
for downloads as well.

Now while that may seem enough to make this a popular plugin, there is more. Meta Extensions allows you mark the 
fields to auto add tags based on field title or content (explained in the readme). It also allows you to set up 
checkboxes to add a post to/remove from a category automatically as wanted. All from the single conf.txt file included 
with the plugin.

One of the latest features is the ability to place conf.txt in your current theme's directory. By placing conf.txt in
the theme directories this allows you to run the plugin with multiple themes on your site and have custom conf.txt per
theme to only show the specific fields needed in that theme. If the file is not in the theme directory, it pulls from
the default conf.txt in the plugin's directory.

Related Links:

* <a href="http://www.premiumdigitalservices.net/blog/" title="Meta Extensions Plugin for WordPress">Plugin Homepage</a>

== Installation ==

1. Extract and upload the contents of the zip file to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Edit the conf.txt file in the plugin directory for the fields you want to set up.
4. Under Posts is where the new fields will populate.

== Frequently Asked Questions ==

Q: Is there any shortcode useage yet?
A: Not yet, possible in future versions.

== Screenshots ==

1. None

== Changelog ==

= 1.0 =
* Initial public release.

= 1.0.1 =
* Upgraded to conf.txt instead of conf.txt for editing via the Plugin Editor.

= 1.0.2 =
* Fixed linebreaks with subtitles in several types.

= 1.0.3 =
* Changed html layout to match admin panel boxes.
* Now uses current theme name in the title box.
* Checks for conf.txt in current theme's directory first. If exists, uses that one. If not, defaults to one in plugin directory.

== Upgrade Notice ==

1. Nothing special required.

== Arbitrary section ==

= CONFIGURATION =

Filed/Element Types:
    textfield - HTML Form Textfield
	textarea  - HTML Form Textarea
	checkbox  - HTML Form Checkbox
	radio     - HTML Form Radio selections
	select    - HTML Form Select box
	gallery   - HTML Form Select box to choose a NextGen Gallery
	video     - HTML Form Select box to choose a Wordtube Video
	download  - HTML Form Select box to choose a Wordpress Download Manager file

To specify the custom fields, edit the file conf.txt

Each entry begins with the subject inside square brackets. The second 
line specifies its type. The third line, which only applies to type
"radio" and "select," enumerates the available options. Each option
has to be separated by a hash mark (#). Each entry for standard HTML
form entities you can also include a default which tells it which to
mark as selected or checked. You can also include the tag option which
will tell the plugin to add the selection as a post tag automatically.
Tags are explained further down. Checkbox also has unique entries called
category_id and category_name. These are filled based on existing categories
in your Wordpress site. This can be used to auto add/remove the post to the
entered category id (and it's matching name for error checks) when used.

Ex.

[Plan]
type = textfield
subtitle = Enter the generic name of your plan here.

[Favorite Post]
type = checkbox
category_id = 4
category_name = Project Plans

[Miles Walked]
type = radio
value = 0-9#10-19#20+
default = 0-9

[Temper Level]
type = select
value = High#Medium#Low

[Hidden Thought]
type = textarea
rows = 4
cols = 40

[Picture Gallery]
type = gallery
tag = 0

[The Video]
type = video
tag = 0

[Attachment]
type = download
tag = 0

Tags can be very useful. Below is how each special type generates the
tag for it.

Tag Design:
    textfield - Title of the entry when filled
	textarea  - Title of the entry when filled
	checkbox  - Title of the entry when checked
	radio     - The name of the selection
	select    - The name of the selection
	gallery   - Not handled
	video     - Not handled

= Useage =
Once you build your conf.txt file, you are ready to start making posts
using the custom fields. In order to call these fields you can either
use the built in get methods for post meta, or you can use the ones that
I have added to the metaext class to keep your code clean and know what
is what. Below is the current list of functions you can use in your theme
to call the meta information easily.

Meta Extensions Class:
	metaext::get_single_metaext($meta_name, $limit)
		Description: Creates a post object containing all the Posts with the meta_name passed being set in them.
			$meta_name: the name in brackets for the field you want to pull the list of posts using this field.
			$limit: how many of these posts to pull. Defaults to 5 if nothing is passed.
	metaext::get_multi_metaext($meta_names, $limit)
		Description: Creates a post object containing all the Posts the all the meta_names passed (meta_names is an array).
			$meta_names: the names in brackets in an array for the fields you want to pull the list of posts using this field.
			$limit: how many of these posts to pull. Defaults to 5 if nothing is passed.
	metaext:: get_value_metaext($the_ID, $name)
		Description: Gets the value for the passed name from the post id passed.
			$the_ID: the post ID you are wanting to pull the custom field's value from.
			$name: The field name of the entry you want to pull the value for. The name is what is in [] and is case sensitive.
	
Meta Extensions Wordpress Download Manager Class:
	metaext_wpdm::get_metaext_downloadlink($id)
		Description: Returns the download link for WPDM ID passed.
			$id: The download ID (can use metaext::get_value_metaext() to get the ID) you want the link for.
	metaext_wpdm::show_metaext_downloadlink($id)
		Description: Echos the download link for WPDM ID passed.
			$id: The download ID (can use metaext::get_value_metaext() to get the ID) you want the link for.
			
Meta Extensions NextGen Gallery Class:
	metaext_ngg::get_metaext_firstpic($galleryid)
		Description: Pulls the first image's src link for the gallery id passed to it.
			$galleryid: The gallery ID that you want to pull the first pic's image url for.
	metaext_ngg::show_metaext_firstpic($galleryid, $class = '')
		Description: Echos the first image's src link for the gallery id passed to it using the optional class name passed.
			$galleryid: The gallery ID that you want to pull the first pic's image url for.
			$class: The name of the class you want to use, optional.
	metaext_ngg::show_metaext_gallery($galleryid)
		Description: Echos the Gallery ID passed, the same as using the NGG show gallery shortcode.
			$galleryid: The gallery ID that you want to show the gallery for.

Meta Extensions WordTube Class:
	metaext_wt::get_metaext_wtvideo($videoid)
		Description: Returns the Video code to show on a page for the video id passed.
			$videoid: The video id to get the code for.
	metaext_wt::show_metaext_wtvideo($videoid)
		Description: Echos the Video code to show on a page for the video id passed.
			$videoid: The video id to get the code for.


= Code Examples =
`
<div id="meta-ext-test">
	<?php if ( metaext::get_value_metaext(get_the_ID(), 'the_gallery') ) : ?>
		<h2>Test for Galleries using Meta Extensions with NextGen Gallery</h2>
		<?php metaext_ngg::show_metaext_gallery(metaext::get_value_metaext(get_the_ID(), 'the_gallery')) ?>
	<?php endif; ?>
	<br /><br />
	<?php if ( metaext::get_value_metaext(get_the_ID(), 'the_video') ) : ?>
		<h2>Test for Videos using Meta Extensions with WordTube</h2>
		<?php metaext_wt::show_metaext_wtvideo(metaext::get_value_metaext(get_the_ID(), 'the_video')) ?>
	<?php endif; ?>
	<br /><br />
	<?php if ( metaext::get_value_metaext(get_the_ID(), 'the_download') ) : ?>
		<h2>Test for Downloads using Meta Extensions with WP Download Manager</h2>
		<?php metaext_wpdm::show_metaext_downloadlink(metaext::get_value_metaext(get_the_ID(), 'the_download')) ?>
	<?php endif; ?>
</div>
`