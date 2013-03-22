<?php

if (Configure::read('debug') == 0):
	throw new NotFoundException();
endif;

$this->Html->script("photon-homepage", array("inline" => false));

?>
<h2>Add songs to queue</h2>
<?php echo $this->Form->create(null, array("url" => array("controller" => "players", "action" => "files"), "id" => "search-form")); ?>
<?php echo $this->Form->input("name", array("label" => false, "id" => "song-name", "placeholder" => "Artist/Song/Filename")); ?>
<?php echo $this->Form->end(); ?>
<div id="songs-list"></div>