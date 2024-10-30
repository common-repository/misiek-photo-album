<div id='misiek_photo_album' >
<?php if ($images): ?>

<?php if ($header): ?>
	<h2 class="header" ><?php print ucwords($album['name']) ?></h2>
<?php endif; ?>

<?php if ($desc) :?>
	<?php print $album['description']?>
<?php endif;?>

<?php foreach((array)$images as $image): ?>
	<?php $src = wp_get_attachment_thumb_url($image['media_id']); ?>
	<?php $image = wp_get_attachment_image_src($image['media_id'], 'large') ?>
	<div class='image_wrap' >
		<a href='<?php print $image[0]; ?>'><img width='70' height='70' src='<?php print $src ?>'></a>
	</div>
<?php endforeach; ?>
	
<div style='clear:both;'></div>

<?php endif; ?>
</div>