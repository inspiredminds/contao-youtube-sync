[![](https://img.shields.io/maintenance/yes/2020.svg)](https://github.com/inspiredminds/contao-youtube-sync)
[![](https://img.shields.io/packagist/v/inspiredminds/contao-youtube-sync.svg)](https://packagist.org/packages/inspiredminds/contao-youtube-sync)
[![](https://img.shields.io/packagist/dt/inspiredminds/contao-youtube-sync.svg)](https://packagist.org/packages/inspiredminds/contao-youtube-sync)

Contao YouTube Sync
===================

This extension allows you to automatically import all videos of a YouTube playlist as news entries in Contao.

## Configuration

First you need to obtain a Google API key.

1. Go to [console.developers.google.com](https://console.developers.google.com/) and create a project, or use an existing one.
2. Go to the [API Library](https://console.developers.google.com/apis/library) and search for _YouTube Data API v3_.
3. Enable the _YouTube Data API v3_ for your project.
4. Go to the [Credentials](https://console.developers.google.com/apis/credentials) of your project and choose _Create Credentials_ » _API key_. You can also use an existing key.
5. Copy the API key for later use.

After obtaining the API key it can be configured for the extension:

```yaml
# config/config.yml
contao_youtube_sync:
    developer_key: <YOUR-API-KEY>
```

Once this extension is installed, you will have additional options in the settings of your Contao news archives:

<img src="https://raw.githubusercontent.com/inspiredminds/contao-youtube-sync/master/screenshot.png" width="735" alt="YouTube synchronisation settings">

Enable the synchronisation and set the YouTube playlist ID from which to import videos as news entries. You also need to define a default author for the synchronised entries and a target directory for the downloaded thumbnail images. Optionally you can define whether new entries should be published by default or not and if already synchronised entries should always be updated (this will not update the alias or author).

## Synchronisation

Synchronisation can be triggered in three ways:

* **Cronjob**: exectued hourly.
* **Command**: `contao_youtube_sync:sync`
* **Back end**: in the article overview, use the _YouTube sync_ link in the global operations.

## Events

Whenever a YouTube video is imported as a news article (or whenever a news article is updated), the following event will be dispatched:

```
InspiredMinds\ContaoYouTubeSync\Event\NewsYouTubeSyncEvent
```

This event lets you retrieve the `\Contao\NewsModel` to be saved to the database, as well as the `\Google_Service_YouTube_PlaylistItem` instance of the YouTube video. It also allows you to _discard_ the news article (or the changes added to it) by setting `$event->setDiscard(true)`.

## Templates

There will be two additional variables available within a news template:

* `$this->youtube_id`: This will contain the video ID of the YouTube video, if the news article was imported from a YouTube playlist.
* `$this->youtube_data`: This is an object containing the original data of the video from the Google YouTube API.

The extension also provides a basic `news_youtube` template, which can be used instead of the `news_full` template for the newsreader of YouTube videos. The template contains markup to show the YouTube video embedded in an iframe.
