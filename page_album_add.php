<div class="wrap">

<h2><?php _e('Add New Album') ?></h2>

<div class="col-wrap">



<form method="post" action="<?php print MISIKE_ALBUM_URL ?>">

<div class="form-field form-required">
<h5><?php _e('Album Name') ?></h5>
<input id="album_name" type="text" aria-required="true" size="40" name="album_name" value="<?php print $album['name']?>" /> <?php print $id ?> <?php print $action ?></div>
<p><?php _e('Album name will appear as a header. If some reason you don\'t need this name use syntax to hide it. Example: [mpa:id=2,header=false]'); ?></p>

<div class="form-field form-required">
<h5><?php _e('Album Description') ?></h5>
<textarea id="album_desc" aria-required="true" size="40" value="" name="album_desc" /><?php print $album['description']?></textarea></div>
<p><?php _e('Description will appear on Album page or view. If you specify an album in existing post or page using syntax, you can specify to hide the description. Example [mpa:id=1,desc=false]'); ?></p>

<?php if (!$album): ?>
<div class="form-field form-required">
<h5><?php _e('Content Type') ?></h5>
<select name="album_content_type" id="album_content_type">
	<option value='none'><?php _e('None'); ?></option>
	<option value='page'><?php _e('Page'); ?></option>
	<option value='post'><?php _e('Post'); ?></option>
</select></div>
<p><?php _e('This value you can\'t modify later. Once an album is created it become page or post'); ?></p>


<div class="form-field form-required">
<h5><?php _e('Template') ?></h5>
<select name="album_template" id="album_template">
	<option value='default'><?php _e('Default Template'); ?></option>
	<?php page_template_dropdown($album['template_id']); ?>
</select></div>
<p><?php _e('Some themes have custom templates you can use for certain pages that might have additional features or custom layouts. If so, you&#8217;ll see them above. Skip this field if you set Content Type as Post'); ?></p>


<div class="form-field form-required">
<h5><?php _e('Category') ?></h5>
<select name="album_category" id="album_category">
<?php foreach($categories as $category): ?>
	<option value='<?php print $category->slug ?>'><?php print $category->cat_name?></option>

	<?php endforeach; ?>
</select></div>
<p><?php _e('If your choice of Content Type is Post please select one Category'); ?></p>
<?php endif; ?>

<p class='submit'><input type='submit' value='<?php print $submit ?>'></p>
</form>

</div>
</div>
