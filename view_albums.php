<?php foreach($albums as $album ): ?>

<?php $album = misiek_album_widget_find($album['id']) ?>

<div id='misiek_albums' >
	<?php include ABSPATH . 'wp-content/plugins/misiek-photo-album/misiek_album_widget.php';?>
</div>

<?php endforeach;?>


<div style='clear:both;'></div>