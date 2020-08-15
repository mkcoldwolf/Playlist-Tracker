# Playlist-Tracker
A PHP video tracker for multiple platform

Playlist Tracker is a program written in PHP and its for people those who dont want to lost any important item in a playlist due to the deletion of author, platform, or any other reason that the item is gone! This is done by adding a tracker program to server crontab and track those playlist or video in a period.

### Platform
  - Youtube
  - ~~BiliBili~~ (In process)
  - ~~SoundCloud~~ (In process)
  
### Feature
  - Tracking playlist (id, position, title, author, description, thumbnail)
  - ~~Backup/Download Items~~ (In process)
  - ~~Tracking single item~~ (In process)

### Installation
  - ```cd ~``` //Go to ur desktop
  - ```clone https://github.com/mkcoldwolf/Playlist-Tracker```
  - ```cd Playlist-Tracker```
  - ```composer install``` //install libs with composer, install [composer](https://getcomposer.org/download/) if u do not
  - Get a youtube v3 api key and place it on `/userdata/config.json`
  - ```php PlaylistTracker.php update``` //This will tracker all playlist and video defined in config.json
  - Add the command to crontab if you would like to track those playlist!

### License
  - [The MIT License (MIT)](LICENSE.txt)
