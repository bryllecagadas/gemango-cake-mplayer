<?php
$config = array(
	'MPlayer' => array(
		"input_pipe"  => "/home/brylle/controls",
		"music_directory" => "/home/brylle/Music",
	)
);

Configure::write('MPlayer', $config);