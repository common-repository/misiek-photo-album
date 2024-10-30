=== Misiek Photo Album ===
Contributors: Michal Augustyniak
Donate link: http://maugustyniak.com
Tags:album, photo, images, gallery, widget
Requires at least:2.7.1
Tested up to:2.7.1
Stable tag:1.4.3

Plugin allows you to add photo albums to your site using media library from your wordpress.

== Description ==

Plugin allows you to add photo albums to your site using media library from your wordpress.

Once album is created from administration menu it creates page or post using template or category.
It means each album has own page or post depend what you will select, also it means that you can specify your own template.

Possible to add multiple albums on existing page or post content using syntax example:
	[mpa:id=?] where '?' is id of the album.

Possible to add all albums as widgets

Supports mysql and sqlite.


BUG FIXING:

FOR 1.4.3 - - 5/25/2009

* fixed link to medium image for widgets

FOR 1.4.2 - -4/17/2009:

* fixed widget formating to better fit in wordpress css

FOR 1.4.1 - -2/20/2009:

* made all albums available as widgets
* fix formating 

FOR 1.4 - -2/20/2009: 

* Fixed some formating
* Added more style to images
* Modified albums list to generate syntax for you
* added screenshots here

FOR 1.3 - 02/20/2009: Fixed bug during displaying multiple albums on the same post/page

FOR 1.2 - 02/13/2009: Sorry for everybody I copied wrong old files into, so below problems should be fixed.

* problem to set correctly a plugin path
* problems with adding correctly tables
* saving a data
* displaying information
* fixed bug in syntax with whitespaces

== Installation ==

1. Upload `misiek_photo_album` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Manage albums from administration menu..

*	Each album has own page or post, depend what you selected on Create Album form.

*	Use syntax to add Albums to existing pages or posts:
    [mpa:id=?] where '?' is album ID, you can find the IDs on albums lists page.

*	Use syntax to hide album header or description.
    [mpa:id=?,header=false,desc=false]

Next Coming features:

* 	Images pagination on album page
* 	Add 'number' in syntax to limit images in album

== Screenshots ==

1. Add New Album Page
2. Albums List Page
3. Manage Albums Page
4. Gallery on Page/Post 
5. Widgets 
