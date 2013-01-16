<?php

App::uses('AppController', 'Controller');

class SongsController extends AppController {
	public function index() {
		$this->set("songs", $this->Song->find("all"));
	}
	
	public function scan() {
		$this->SongList = $this->Components->load('SongList');
		App::uses('Folder', 'Utility');
		
		$files = $this->SongList->files();
		foreach($files as $file) {
			if(!$this->Song->find("first", array("conditions" => array("path" => $file)))) {
				$this->Song->create();
				$music_directory = Configure::read("MPlayer.music_directory");
				$path 			 = $music_directory . $file;
				
				$item = array(
					"Song" => $this->SongList->get_id3($path)
				);
				
				if(isset($item["Song"]["path"])) {
					$item["Song"]["path"] = str_replace($music_directory, "", $item["Song"]["path"]);
				}
				
				$this->Song->save($item);
			}
		}
		
		$this->redirect("/");
	}
}