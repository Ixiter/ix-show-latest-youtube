# IX Show Latest YouTube

Contributors: ixiter, Daniel J. Lewis
Donate link: http://ixiter.com/ix-show-latest-youtube/
Tags: YouTube, Videoplayer, Hangout on Air, YouTube Live, Shortcode, Template Tag
Requires at least: 3.4
Tested up to: 3.9
Stable tag: 2.2.djl.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Provides a shortcode and a template tag to embed the latest video or current live stream of a specified YouTube channel.

## Description

The plugin provides a shortcode and a template tag to embed the latest video or current live stream of a specified YouTube channel.
It comes with an options page to let you set the default options for YouTube ID, width, height, autoplay and count of latest videos to embed. You can customize these parameters with the shortcode and template tag if needed.

This also works great with YouTube Live, by following this fallback progression.

1. If you're currently live, display the live video.
2. If you're not currently live, display the next upcoming live event.
3. If there is no upcoming live event, display the text from Settings > Ixiter Show Latest YT > Show message if not broadcasting.
4. If there is no "not broadcasting" message, show the most recent YouTube video from your channel.

## Installation

1. Upload the complete `ix-show-latest-youtube` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings > IX Show Latest YT and setup your default settings.
4. Done

## Frequently Asked Questions

### Is it possible to have the last 3 or so videos showing? 

Yes. You can set a default option for "count of latest videos to embed" and use a parameter/attribute in Template tag and shortcode as well.

### Is it possible to set the video start playing automatically? 

Yes. You can set a default option for "autoplay" and use a parameter/attribute in Template tag and shortcode as well.

## Changelog

### 2.2.djl.4
* Removed pixel references in embed, to allow for auto and %
* Updated documentation

### 2.2.djl.3
* Removed related videos

### 2.2.djl.2
* Updated feed method to more compatible WordPress method

### 2.2.djl.1
* Changed active to use pending as fallback

### 2.2 
* September, 10th 2013
* No live message feature added

### 2.1.1  
* September, 6th 2013
* Fixed get_options bug

### 2.1 
* April, 15th 2013
* Fixed a bug in channel setup for shortcode and template tag
* Fixed a bug in requesting the live video ID


### 2.0 
* April, 12th 2013
* Major version change!
* removed the javascript and query YouTube from server side now
* added option for autoplay - suggested by [Angus Todman](http://wordpress.org/support/profile/angus-todman)
* added option for count of latest videos to embed - suggested by [hameiria](http://wordpress.org/support/profile/hameiria)


### 1.0 
* November, 13th 2012
* First public version


## Upgrade Notice

### 2.1 
Fixed a bug in channel setup for shortcode and template tag.
Fixed a bug in requesting the live video ID

### 2.0 
Javascript removed. "autoplay" and "count of latest videos to embed" options added.


## Credits
* [Moritz Tolxdorff](https://plus.google.com/u/0/+MoritzTolxdorff/about) - for the original javascript
* [Angus Todman](http://wordpress.org/support/profile/angus-todman) - for suggesting count of latest videos option
* [hameiria](http://wordpress.org/support/profile/hameiria) - for suggesting autoplay option and bug reports
* My Mum - for everything