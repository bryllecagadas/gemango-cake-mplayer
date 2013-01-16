<?php
$config = array(
	'MPlayer' => array(
		"input_pipe"  => "/etc/mplayer/controls",
		"music_directory" => "/home/brylle/Music",
	)
);

Configure::write('MPlayer', $config);