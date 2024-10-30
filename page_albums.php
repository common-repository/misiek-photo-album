<div class="wrap">

<h2><?php _e('Albums', 'misiek') ?></h2>

<div class="col-wrap">
<h3>Album List</h3>
<?php if(!$albums):?> No albums yet. <?php else:?>
<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th>Name</th>
			<th>Description</th>
			<th>Edit</th>
			<th>Page/Post Link</th>
			<th>Syntax</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Name</th>
			<th>Description</th>
			<th>Edit</th>
			<th>Page/Post Link</th>
			<th>Syntax</th>
		</tr>
	</tfoot>
	<?php foreach($albums as $album):?>
	<?php
	if ($album['post_id']) {
		$post = &get_post($album['post_id']);
		$link = get_permalink($post->ID);	
	}
	
	?>
	<tr>
		<td><a href="<?php print MISIKE_ALBUM_URL . "&action=photos&id={$album['id']}"?>"><?php print $album['name']?></a></td>
		<td><?php print $album['description']?></td>
		<td><a href="<?php print MISIKE_ALBUM_URL . "&action=edit&id={$album['id']}"?>">Modify</a> | <a href="<?php print MISIKE_ALBUM_URL . "&action=delete&id={$album['id']}"?>">Delete</a></td>
		<td><?php if ($album['post_id']) { ?><a target='_blank' href='<?php print $link?>'>View</a><?php }?></td>
		<td>[mpa:id=<?php print $album['id'] ?>,header=false,desc=false]</td>
	</tr>
	<?php endforeach?>

</table>
	<?php endif;?></div>

</div>
