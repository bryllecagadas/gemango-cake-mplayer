<?php

define("SONG_LENGTH", "ANS_LENGTH");
define("SONG_POSITION", "ANS_TIME_POSITION");
define("SONG_ARTIST", "ANS_META_ARTIST");
define("SONG_TITLE", "ANS_META_TITLE");

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('Sanitize', 'Utility');

class PlayersController extends AppController {
	public $name = 'Players';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout = "ajax";
		$this->autoRender = false;
		$this->Json = $this->Components->load('Json');
	}
	
	public function pause() {
		$pipe = Configure::read("MPlayer.input_pipe");
		exec("echo \"pause\" > '$pipe'");
		
		echo $this->Json->response("success");
	}
	
	public function stop() {
		$pipe = Configure::read("MPlayer.input_pipe");
		exec("echo \"stop\" > '$pipe'");
		
		echo $this->Json->response("success");
	}
	
	public function enqueue() {
		$music_directory	= Configure::read("MPlayer.music_directory");
		$pipe 				= Configure::read("MPlayer.input_pipe");
		$file 			 	= isset($this->params["named"]) && isset($this->params["named"]["file"]) ? $this->params["named"]["file"] : "";

		if(!empty($file)) {
			$this->loadModel("Song");
			$song = $this->Song->find("first", array("conditions" => array("id" => $file)));
			if(empty($song)) {
				echo $this->Json->response("error");
				return;
			}
			
			$path = $music_directory . $song["Song"]["path"];
			if(file_exists($path)) {
				$path = addslashes($path);
				$this->loadModel("Queue");
				$this->Queue->create();
				$this->Queue->save(array(
					"Queue" => array(
						"song_id" => $song["Song"]["id"],
					)
				));
				exec("echo \"loadfile '$path' 1\" > '$pipe'");
			}
		}
		
		echo $this->Json->response("success");
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
		
		echo $this->Json->response("success", "", $songs);
	}
	
	public function song_info() {
		$start = microtime(true);
		
		if(!($position = $this->get_song_info(SONG_POSITION, "get_time_pos"))) {
			return $this->Json->response("error");
		}
		
		if(!($length = $this->get_song_info(SONG_LENGTH, "get_time_length"))) {
			return $this->Json->response("error");
		}
		
		if(!($artist = $this->get_song_info(SONG_ARTIST, "get_meta_artist"))) {
			return $this->Json->response("error");
		}
		
		if(!($title = $this->get_song_info(SONG_TITLE, "get_meta_title"))) {
			return $this->Json->response("error");
		}
		
		$diff = microtime(true) - $start;
		
		$details = array(
			"length" => $length, 
			"position" => $position,
			"artist" => trim($artist,"'\""),
			"title" => trim($title,"'\""),
			"diff" => $diff,
		);
		
		echo $this->Json->response("success", "", $details);
	}
	
	private function get_song_info($type, $command) {
		$input 	= Configure::read("MPlayer.input_pipe");
		$output = Configure::read("MPlayer.output_pipe");
		$values = array();
		
		exec("echo \"$command\" > '$input'");
		usleep(200000);
		exec("tail -n 1 $output", $values);
		if(isset($values[0])) {
			parse_str($values[0], $response);
			if(!isset($response[$type])) {
				return false;
			}
			return $response[$type];
		}
		return false; 
	}
}
