<?php

	require_once './vendor/autoload.php';
	
	include_once('./lib/simple_html_dom.php');
	
	$config = json_decode(file_get_contents('./userdata/config.json'));
	
	//Create data folder
	if(!file_exists('./data')) {
		mkdir('./data', 0700, true);
	}
	
	if(count($argv) <= 1) {
		echo "Args:"."\n";
		echo "update - update all the playlist defined in config.yml"."\n";
		die();
	}
	
	if($argv[1] == "update") {
		
		$Youtube_Playlist = $config->Youtube->Tracking->Playlist;
		if(count($Youtube_Playlist) > 0) {
			
			include_once('./app/platform/youtube/Youtube.php');
			$Platform = new Youtube($config->Debug, $config->Youtube->Youtube_v3_api_key);
			foreach($Youtube_Playlist as $i) {
				$Platform->trackPlaylist($i);
			}
			
		}
		
	}
	
	if($argv[1] == "info") {
	
		$platformStr = $argv[2];
		
		if($platformStr == "youtube") {
			
			include_once('./app/platform/youtube/Youtube.php');
			$Platform = new Youtube($config->Debug, $config->Youtube->Youtube_v3_api_key);
			
			if($argv[3] == "video") {
				$video = $Platform->infoVideo($argv[4]);
				var_dump($video);
			}
			
		}
	
	}
	
?>