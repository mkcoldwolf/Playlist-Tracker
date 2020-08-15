<?php
	
	require_once './app/platform/Platform.php';
	class Youtube extends Platform {
		
		private $debug;
		private $apiKey;
		
		private $client;
		private $youtube;
		public function __construct($debug, $apiKey) {
			$this->debug = $debug;
			$this->apiKey = $apiKey;
			
			//init Youtube API
			$this->prefix = "Youtube";
			$this->client = new Google_Client();
			$this->client->setDeveloperKey($this->apiKey);
			$this->youtube = new Google_Service_YouTube($this->client);
		}
		
		public function infoVideo($Id) {
			
			$video = new stdClass();
			
			$videoItemResponse = $this->youtube->videos->listVideos('snippet, recordingDetails', array(
				'id' => $Id));
				
			return $videoItemResponse;
			
		}
		
		public function trackVideo($Id) {
		}
		
		public function trackPlaylist($Id) {
			
			//Getting title by html with DOM
			$html = file_get_html("https://www.youtube.com/playlist?list=".$Id);
			
			$playlistTitle = $html->find('title')[0]->innertext;
			$this->log('Now getting: '.$playlistTitle."\n");
			
			//Getting videos in playlist with Youtube API
			$nextPageToken = '';
			do {
				$playlistItemsResponse = $this->youtube->playlistItems->listPlaylistItems('snippet', array(
				'playlistId' => $Id,
				'maxResults' => 50,
				'pageToken' => $nextPageToken));

				foreach ($playlistItemsResponse['items'] as $playlistItem) {
					
					$playlistId = $playlistItem['snippet']['playlistId'];
					
					$video = new stdClass();
					
					$video->Position = $playlistItem['snippet']['position'];
					$video->Id = $playlistItem['snippet']['resourceId']['videoId'];
					$video->Title = $playlistItem['snippet']['title'];
					
					$thumnailTypeList = array('maxres', 'standard', 'high', 'medium', 'default');
					foreach($thumnailTypeList as $type) {
						if(isset($playlistItem['snippet']['thumbnails'][$type])) {
							$video->ThumbnailType = $type;
							$video->ThumbnailUrl = $playlistItem['snippet']['thumbnails'][$type]['url'];
							break;
						}
					}
					
					//Sinces playlist video channelId return playlist owner, we have to search for correct video owner again
					$videoInfo = json_decode(file_get_contents('https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v='.$playlistItem['snippet']['resourceId']['videoId'].'&format=json'), true);
					
					$video->ChannelTitle = $videoInfo['author_name'];
					$video->ChannelUrl = str_replace('https://www.youtube.com', '', $videoInfo['author_url']);
					
					if($this->debug) {
						$this->log($video->Position.'.'.sprintf('%s (%s)', $video->Title, $video->Id)."\n");
						$this->log('Thumbnail: ' . $video->ThumbnailUrl . ' (' . $video->ThumbnailType . ')'."\n");
						$this->log('Owner: ' . $video->ChannelTitle . ' (' . $video->ChannelUrl . ')'."\n");
					}
					
					$videoStoragePath = './data/'.$playlistTitle.'-'.$playlistId.'/'.str_pad($video->Position, 4, '0', STR_PAD_LEFT).'.'.$video->Title.'-'.$video->Id;
					if(!file_exists($videoStoragePath)) {
						mkdir($videoStoragePath, 0700, true);
					}
					file_put_contents($videoStoragePath.'/'.'info.json', json_encode($video, JSON_UNESCAPED_UNICODE));
					
					//Download thumbnails
					file_put_contents($videoStoragePath.'/'.'thumbnail.jpg', fopen($video->ThumbnailUrl, 'r'));
				}

				$nextPageToken = $playlistItemsResponse['nextPageToken'];
			} while ($nextPageToken <> '');
				
		}
		
	}

?>