<li><h2>
	<?php 
	$link = '';
	if ($album['album']['post_id']) {
		$post = &get_post($album['album']['post_id']);
		$link = get_permalink($post->ID);
		$href = 'href="'.$link.'"';
		print "<a target='_blank' {$href}>";
	}	
	
	if (strlen($album['album']['name']) > 16) {
			print substr($album['album']['name'],0,16) . ' ...';	
	} else {
		print $album['album']['name'];
	} 
	
	if ($album['album']['post_id']) {
		print "</a>";
	}
	?>
</h2>
<div id='misiek_photo_album' class='misiek_album_widget'>

<?php foreach((array)$album['images'] as $image):?>
	<?php $src = wp_get_attachment_thumb_url($image['media_id']); ?>
	
 	<?php $image = wp_get_attachment_image_src($image['media_id'], 'large') ?>

	<?php $href="href='{$image[0]}' rel='lightbox[210]'"; ?>

	<div class='image_wrap' >
		<a <?php print $href ?>  ><img width='40' height='40'src='<?php print $src ?>'></a>
	</div>

<?php endforeach; ?>

<div style='clear:both;'></div>
</div>
</li>
