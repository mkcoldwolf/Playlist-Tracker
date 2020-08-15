<?php

	abstract class Platform {
		
		public $prefix;
		
		public abstract function infoVideo($Id);
		public abstract function trackVideo($Id);
		public abstract function trackPlaylist($Id);
		
		public function log($message) {
			echo '['.$this->prefix.']'.' '.$message;
		}
		
	}

?>