<?php
$config = array(
	'MPlayer' => array(
		"input_pipe"  => "/etc/mplayer/controls",
		"output_pipe"  => "/etc/mplayer/output",
		"music_directory" => "/home/brylle/Music",
	)
);

Configure::write('MPlayer', $config);