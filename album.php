<?php
/*
 Plugin Name: Misiek Photo Album
 Version: 1.4.3
 Plugin URI: http://maugustyniak.corpface.com/misiek-photo-album/
 Description: Photo Album use WP Photo Library. Supports mysql and sqlite.
 Author: Michal Augustyniak
 Author URI: maugustyniak.corpface.com

 Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : maugustyniak@realnets.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

global $wpdb;
define('MISIEK_ALBUMS_TABLE', $wpdb->prefix . "misiek_albums");
define('MISIEK_IMAGES_TABLE', $wpdb->prefix . "misiek_albums_images");
define(MISIKE_ALBUM_URL,get_option('siteurl') . '/wp-admin/admin.php?page=misiek-photo-album/album.php');
add_action('wp_print_styles', 'misiek_albym_styles');
add_action('init', 'misiek_album_widget_init');

/*
 * misiek_album_active()
 * Active this plugin
 */
function misiek_album_active() {
	global $wpdb;

	$sql = "CREATE TABLE " . MISIEK_ALBUMS_TABLE . " (id int NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, description text NOT NULL, orders int not null default 0, post_id int not null, PRIMARY KEY (id))";
	$sql1 = "CREATE TABLE " . MISIEK_IMAGES_TABLE . " (id int NOT NULL AUTO_INCREMENT, album_id int not null, media_id int not null, PRIMARY KEY (id))";

	if($wpdb->get_var("SHOW TABLES LIKE '" . MISIEK_ALBUMS_TABLE . "'") != MISIEK_ALBUMS_TABLE) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	if($wpdb->get_var("SHOW TABLES LIKE '" . MISIEK_IMAGES_TABLE . "'") != MISIEK_IMAGES_TABLE) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql1);
	}

	wp_insert_category(array('cat_name' => "My Albums"));

}
/* register misiek_album_active() function, called on plugin activation */
register_activation_hook( __FILE__, 'misiek_album_active');

/*
 * misiek_album_deactive()
 * Deactive this plugin
 */
function misiek_album_deactive() {
	global $wpdb;

	$sql = "DROP TABLE " . MISIEK_ALBUMS_TABLE;
	$sql1 = "DROP TABLE " . MISIEK_IMAGES_TABLE;

	$results = $wpdb->query( $sql);
	$results = $wpdb->query( $sql1);

}
/* register deactive() function, called on plugin activation */
register_deactivation_hook( __FILE__, 'misiek_album_deactive' );

/* admin_menu()
 * Add Plugin menu
 */
function misiek_album_admin_menu() {
	$level = get_option('wppa-accesslevel');
	if (empty($level)) { $level = 'level_10'; }

	add_menu_page('misiek', 'Albums', $level, __FILE__);
	add_submenu_page(__FILE__, 'Edit', 'Edit', $level, __FILE__, 'misiek_album');
	add_submenu_page(__FILE__, 'Add New', 'Add New', $level, __FILE__ . '&action=add', 'misiek_album');

}

/* add admin_menu action */
add_action('admin_menu', 'misiek_album_admin_menu');

/* misiek_album_create_album_page()
 * Create Album Page
 */
function misiek_album() {
	global $wpdb;


	/* action on edit, shows data in add form */
	if($_GET['action'] == 'edit') {
		misiek_album_edit();
	}

	/* update the add/edit form */
	elseif($_POST['action'] == 'update') {
		misiek_album_update();
	}

	/* delete the album */
	elseif($_GET['action'] == 'delete') {
		misiek_album_delete();
	}

	/* list all photos for album */
	elseif ($_GET['action'] == 'photos') {
		misiek_photos();
	}

	/* show add form page or create the album */
	elseif ($_GET['action'] == 'add' || $_POST['action']) {
		misiek_album_add();
	}
	/* lists all albums */
	else {
		misiek_albums();
	}
}

function misiek_albums() {
	global $wpdb;
	$albums = $wpdb->get_results("SELECT * FROM " . MISIEK_ALBUMS_TABLE , 'ARRAY_A');
	include ABSPATH . 'wp-content/plugins/misiek-photo-album/page_albums.php';
}

function misiek_albums_find() {
	global $wpdb;
	return $wpdb->get_results("SELECT * FROM " . MISIEK_ALBUMS_TABLE , 'ARRAY_A');
}

function misiek_album_delete() {
	global $wpdb;
	$sql = "DELETE FROM " . MISIEK_ALBUMS_TABLE . " WHERE id = '{$_GET['id']}'";
	$results = $wpdb->query( $sql);
	print '<div id="message" class="updated fade"><p><strong>Album has beed deleted.</strong></p></div>';
	misiek_albums();
}

function misiek_album_add() {
	global $wpdb;

	$action = "<input type='hidden' name='action' value='add' />";
	$title = "Create New Album";
	$submit = "Create";

	if (!empty($_POST['album_name'])) {
		$sql = "SELECT MAX(id) as id from " . MISIEK_ALBUMS_TABLE;
		$last_row = $wpdb->get_row($sql);
		if ($last_row->id) {
			$last_album_id = $last_row->id + 1;
		} else {
			$last_album_id = 1;
		}

		$post_id = misiek_album_create($_POST['album_content_type'],$last_album_id, $_POST['album_name'], $_POST['album_template'], $_POST['album_category']);

		$sql = "insert into " . MISIEK_ALBUMS_TABLE . " (id, name, description, post_id) values ({$last_album_id},'{$_POST['album_name']}','{$_POST['album_desc']}','{$post_id}')";
		$results = $wpdb->query($sql);

		print '<div id="message" class="updated fade"><p><strong>Album has beed added.</strong></p></div>';

		misiek_album_edit($last_album_id);
	} else {
		$categories = get_categories(array('type' => 'post',
            'child_of' => 0,
            'orderby' => 'name',
            'order' => 'ASC',
			'hide_empty' => 0, 
            'hierarchical' => 1, 
            'pad_counts' => false));
		include ABSPATH . 'wp-content/plugins/misiek-photo-album/page_album_add.php';
	}
}

function misiek_album_edit($album_id = false) {
	global $wpdb;
	if ($album_id) {
		$_GET['id'] = $album_id;
	}
	$categories = get_categories(array('type' => 'post',
            'child_of' => 0,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true,
            'include_last_update_time' => false,
            'hierarchical' => 1, 
            'pad_counts' => false));

	$action = "<input type='hidden' name='action' value='update' />";
	$title = "Edit Album";
	$submit = "Update";
	$id = "<input type='hidden' name='id' value='{$_GET['id']}' />";
	$album = $wpdb->get_row("SELECT * FROM " . MISIEK_ALBUMS_TABLE . " WHERE id = '{$_GET['id']}' ", 'ARRAY_A');
	include ABSPATH . 'wp-content/plugins/misiek-photo-album/page_album_add.php';
}

function misiek_album_update() {
	global $wpdb;
	$sql = "UPDATE " . MISIEK_ALBUMS_TABLE . " set name = '{$_POST['album_name']}', description = '{$_POST['album_desc']}'  WHERE id = '{$_POST['id']}'";
	$results = $wpdb->query( $sql);
	print '<div id="message" class="updated fade"><p><strong>Album has beed modified.</strong></p></div>';
	misiek_album_edit($_POST['id']);
}

function misiek_album_create($type, $id, $name, $template, $category) {
	if ($type == 'none') {
		return false;
	}
	// Create post object
	$my_post = array();
	$my_post['post_title'] = 'Album ' . $name;
	$my_post['post_content'] = '[mpa:id=' . $id . ',header=false,desc=true]';
	$my_post['post_status'] = 'publish';
	$my_post['post_author'] = 1;

	if ($type == 'page') {
		$my_post['post_type'] = 'page';
		$my_post['page_template'] = $template;

	} elseif ($type == 'post') {
		$my_post['post_type'] = 'post';
		$idObj = get_category_by_slug($category);
		$id = $idObj->term_id;
		$my_post['post_category'] = array($id);

	}

	// Insert the post into the database
	return wp_insert_post($my_post);

}

function misiek_photos() {
	global $wpdb;

	if($_POST['add_photos']) {
		if ($_POST['media']) {
			foreach($_POST['media'] as $image_id) {
				$image = $wpdb->get_row("select * from " . MISIEK_IMAGES_TABLE  . " where media_id = '{$image_id}' and album_id = {$_GET['id']}");
				if (!$image) {
					$wpdb->query("insert into " . MISIEK_IMAGES_TABLE  . " (album_id, media_id) values ({$_GET['id']}, {$image_id})");
				}

			}
			print '<div id="message" class="updated fade"><p><strong>Image/s has been added</strong></p></div>';
		}

	} elseif ($_POST['del_photos']) {
		if ($_POST['media']) {
			foreach($_POST['media'] as $image_id) {
				$wpdb->query("delete from " . MISIEK_IMAGES_TABLE  . " where album_id = {$_GET['id']} and media_id= {$image_id}");
			}
			print '<div id="message" class="updated fade"><p><strong>Image/s has been deleted</strong></p></div>';
		}
	}

	$album = $wpdb->get_row("SELECT * FROM " . MISIEK_ALBUMS_TABLE . " WHERE id = '{$_GET['id']}' ", 'ARRAY_A');

	$images = $wpdb->get_results("SELECT * FROM " . MISIEK_IMAGES_TABLE . " WHERE album_id = '{$_GET['id']}' ", 'ARRAY_A');

	$media_ids = array();
	foreach((array)$images as $image) {
		$media_ids[] = $image['media_id'];
	}

	$posts = get_posts(array(
			"showposts"=>-1,
			"what_to_show"=>"posts",
			"post_status"=>"inherit",
			"post_type"=>"attachment",
			"orderby"=>"menu_order ASC, ID ASC",
			"post_mime_type"=>"image/jpeg,image/gif,image/jpg,image/png"));

	$url = get_option('siteurl') . '/wp-admin/admin.php?page=misiek-photo-album/album.php&action=photos&id=' . $_GET['id'];

	include ABSPATH . 'wp-content/plugins/misiek-photo-album/page_album_photos.php';
}

function view_photos($album_id = false, $desc = true, $header = true) {
	if ($album_id) {
		global $wpdb;

		$album = misiek_album_find($album_id);
		$images = misiek_album_images_find($album_id);

		ob_start();
		include ABSPATH . 'wp-content/plugins/misiek-photo-album/view_album_photos.php';
		$content = ob_get_contents();
		ob_get_clean();
		return $content;
	}

	return "";
}

function view_albums() {
	global $wpdb;
	$albums = $album = misiek_albums_find();

	ob_start();
	include ABSPATH . 'wp-content/plugins/misiek-photo-album/view_albums.php';
	$content = ob_get_contents();
	ob_get_clean();
	return $content;
}

function misiek_album_find($album_id) {
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM " . MISIEK_ALBUMS_TABLE . " WHERE id = '{$album_id}' ", 'ARRAY_A');
}

function misiek_album_widget_find($album_id) {
	global $wpdb;
	$album['album'] = misiek_album_find($album_id);
	$album['images'] = misiek_album_images_find($album_id, 'order by rand()', 'limit 4');
	return $album;
}

function misiek_album_images_find($album_id, $order = '',$limit = '') {
	global $wpdb;
	return $wpdb->get_results("SELECT * FROM " . MISIEK_IMAGES_TABLE . " WHERE album_id = '{$album_id}' {$order} {$limit}", 'ARRAY_A');
}

add_filter('the_content', 'view_photos_filter', 1);
function view_photos_filter($post) {
	/* look for pattern */
	if (preg_match_all("/\[mpa:(.*)\]/", $post, $matches)) {
		/* loop options in mattern */
		foreach($matches[1] as $options) {
			$options = explode(',',$options);
			/* loop options and split in values */
			foreach($options as $option) {
				$option = str_replace(' ', '',$option);
				$option = explode('=',$option);
				if (strtolower($option[1]) == 'false') {
					$option[1] = false;
				} elseif(strtolower($option[1]) == 'true') {
					$option[1] = true;
				}
				$$option[0] = $option[1];
			}
			
			if ($list) {
				/* find all album and place in content */
				$content = view_albums();
				/* remove entire pattern from post */
				$post = preg_replace("/\[mpa:list=(.*)\]/",$content,$post);
				$list = false;
			} else {
				/* find and return album as content */
				$content = view_photos($id, $desc, $header);
				/* remove entire pattern from post */
				$post = preg_replace("/\[mpa:id=".$id."(.*)\]/",$content,$post);
			}

				
		}
	}
	return $post;
}

function misiek_album_widget($args) {
	$id = str_replace('misiek_album_widget_','', $args['widget_id']);
	$album = misiek_album_widget_find($id);
	include ABSPATH . 'wp-content/plugins/misiek-photo-album/misiek_album_widget.php';
}

function misiek_album_widget_init() {
	$albums = misiek_albums_find();
	foreach((array)$albums as $album) {
		$widget_options = array('classname' => 'misiek_paypal_button', 'description' => __($album['name']) );
		wp_register_sidebar_widget('misiek_album_widget_' . $album['id'], __("Misiek Album"), 'misiek_album_widget' , $widget_options);
	}
}

function misiek_albym_styles() {
	include ABSPATH . 'wp-content/plugins/misiek-photo-album/misiek_album.css';
}
?>