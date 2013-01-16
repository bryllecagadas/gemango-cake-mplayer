<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('Sanitize', 'Utility');

class PlayersController extends AppController {
	public $name = 'Players';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout = "ajax";
		$this->autoRender = false;
	}
	
	public function pause() {
		$pipe = Configure::read("MPlayer.input_pipe");
		exec("echo \"pause\" > '$pipe'");
	}
	
	public function stop() {
		$pipe = Configure::read("MPlayer.input_pipe");
		exec("echo \"stop\" > '$pipe'");
	}
	
	public function enqueue() {
		$music_directory	= Configure::read("MPlayer.music_directory");
		$pipe 				= Configure::read("MPlayer.input_pipe");
		$file 			 	= isset($this->params["named"]) && isset($this->params["named"]["file"]) ? $this->params["named"]["file"] : "";

		if(!empty($file)) {
			$this->loadModel("Song");
			$song = $this->Song->find("first", array("conditions" => array("id" => $file)));
			if(empty($song)) {
				return;
			}
			
			$path = $music_directory . $song["Song"]["path"];
			if(file_exists($path)) {
				$path = addslashes($path);
				exec("echo \"loadfile '$path' 1\" > '$pipe'");
			}
		}
	}
	
	public function files($search = "") {
		$songs = array();
		$this->loadModel("Song");
		if($search) {
			$search = Sanitize::escape($search);
			$songs = $this->Song->find("all", array(
				"conditions" => array(
					"or" => array(
						"filename LIKE \"%$search%\"",
						"path LIKE \"%$search%\"",
						"artist LIKE \"%$search%\"",
						"title LIKE \"%$search%\"",
					)
				)
			));
		} else {
			$songs = $this->Song->find("all");			
		}
		echo json_encode($songs);
	}
}
