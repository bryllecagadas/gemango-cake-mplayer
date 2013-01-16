<?php

App::uses('Component', 'Controller');
App::import('Vendor', 'getid3/getid3');

class SongListComponent extends Component {
	public function files($search = "") {
		$music_directory 	= Configure::read("MPlayer.music_directory");
		$folder 			= new Folder($music_directory);
		$paths 				= $folder->read(true, true, true);
	
		$files = $this->list_files($paths, $search);
		return $files;
	}
	
	private function list_files($paths = array(), $search) {
		$music_directory 	= Configure::read("MPlayer.music_directory");
		$files 				= array();
	
		foreach($paths as $path) {
			if(is_array($path)) {
				$files = array_merge($files, $this->list_files($path, $search));
			} else {
				if(is_dir($path)) {
					$dir = new Folder($path);
					$files = array_merge($files, $this->list_files($dir->read(true, true, true), $search));
				} else {
					$clean_path = str_replace($music_directory, "", $path);
					if($search && strpos($path, $search) !== false) {
						$files[] = $clean_path;
					} elseif(!$search) {
						$files[] = $clean_path;
					}
				}
			}
		}
	
		return $files;
	}

	public function get_id3($path) {
		if(is_file($path)) {
			$id3 = new getID3();
			$details = $this->id3_array_map($id3->analyze($path));
			$details["path"] = $path;
			return $details;
		}
		
		return array();
	}
	
	private function id3_array_map($details) {
		$id3_array = array();
		
		if(isset($details["filesize"])) {
			$id3_array["filesize"] = $details["filesize"];
		}
		
		if(isset($details["filename"])) {
			$id3_array["filename"] = $details["filename"];
		}
		
		if(isset($details["fileformat"])) {
			$id3_array["format"] = $details["fileformat"];
		}
			
		if(isset($details["audio"])) {
			if(isset($details["audio"]["bitrate"])) {
				$id3_array["bitrate"] = floor($details["audio"]["bitrate"] / 1000);
			}
		}
		
		$id3_array = array_merge($id3_array, $this->get_song_info($details));
		
		return $id3_array;
	}
	
	private function get_song_info($details) {
		if(!isset($details["tags"])) {
			return array();
		}
		
		$info = array();
		
		$artist = $title = "";
		
		if(
			isset($details["tags"]["id3v1"]) && 
			!empty($details["tags"]["id3v1"]["artist"])
		) {
			$artist = $details["tags"]["id3v1"]["artist"][0];
		}
		
		if(
			empty($artist) &&
			isset($details["tags"]["id3v2"]) &&
			!empty($details["tags"]["id3v2"]["artist"])
		) {
			$artist = $details["tags"]["id3v2"]["artist"][0];
		}
		
		if(
			isset($details["tags"]["id3v1"]) &&
			!empty($details["tags"]["id3v1"]["title"])
		) {
			$title = $details["tags"]["id3v1"]["title"][0];
		}
		
		if(
			empty($title) &&
			isset($details["tags"]["id3v2"]) &&
			!empty($details["tags"]["id3v2"]["title"])
		) {
			$title = $details["tags"]["id3v2"]["title"][0];
		}
		
		$info["artist"] = $artist;
		$info["title"] = $title;
		
		return $info;
	}
}