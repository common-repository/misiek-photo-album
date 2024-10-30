<div class="wrap">

<h2><?php _e('Manage photos for ' . ucwords($album['name']) . ' Album', 'misiek') ?></h2>

<form method='post' action='<?php print $url?>'>
<input type='hidden' name='del_photos' value='true'>

<?php if ($images): ?>
	<p class='submit'>
	<input type='submit' value='Remove Photos from <?php print ucwords($album['name']) ?> Album'>
	</p>
<?php endif; ?>

<?php foreach((array)$images as $image): ?>
	<?php $src = wp_get_attachment_thumb_url($image['media_id']); ?>
	<span style='float:left;padding:10px;position:relative'>
	<input style='position:absolute;left:0:top:0;' type='checkbox' name='media[]' value='<?php print $image['media_id']?>'>
	<img src='<?php print $src?>'>
	</span>
<?php endforeach; ?>

<?php if (!$images): ?>
	No images yet.
<?php endif ?>

<div style='clear:both;'></div>

<?php if ($images): ?>
	<p class='submit'>
	<input type='submit' value='Remove Photos from <?php print ucwords($album['name']) ?> Album'>
	</p>
<?php endif; ?>

</form>

<h2><?php _e('Media Library','Album', 'misiek') ?></h2>
<form method='post' action='<?php print $url ?>'>
<input type='hidden' name='add_photos' value='true'>


<p class='submit'>
	<input type='submit' value='Add Photos to <?php print ucwords($album['name'])?> Album'>
</p>

<?php foreach($posts as $post): ?>
	<?php if(!in_array($post->ID,$media_ids)): ?>
	<?php $src = wp_get_attachment_thumb_url($post->ID); ?>
	<span style='float:left;padding:10px;position:relative'>
	<input style='position:absolute;left:0:top:0;' type='checkbox' name='media[]' value='<?php print $post->ID ?>'>
	<img src='<?php print $src?>'>
	</span>
	<?php endif; ?>
<?php endforeach; ?>

<div style='clear:both;'></div>

<p class='submit'>
	<input type='submit' value='Add Photos to <?php print ucwords($album['name']) ?> Album'>
</p>
</form>

</div>
