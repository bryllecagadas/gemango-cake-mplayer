<?php 
$script = <<<JS
Photon.exec.songs = {
	init: function() {
		$(".song-item a").live("click", function(e) {
			e.preventDefault();
			var button = this;
			$.ajax({
				url: button.href
			});
			return false;
		});
	}
}
JS;

$this->Html->scriptBlock($script, array("inline" => false, "onDomReady" => false));
?>

<h2>Song List</h2>
<div id="songs-list">
	<?php if(empty($songs)) : ?>
		<h3>No songs added.</h3>
	<?php else : ?>
		<?php foreach($songs as $song): ?>
			<div class="song-item"><span><a href="<?php echo $file_base_path . $song["Song"]["id"]; ?>" class="song-action">Add to queue</a></span> <?php echo $song["Song"]["artist"]; ?> - <?php echo $song["Song"]["title"]; ?> [<strong><?php echo $song["Song"]["filename"]; ?></strong>]</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>