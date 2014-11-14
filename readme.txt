=== IX Show Latest YouTube ===
Contributors: ixiter, djosephdesign
Donate link: http://ixiter.com/ix-show-latest-youtube/
Tags: YouTube, video player, Hangout on Air, YouTube Live, Shortcode, Template Tag, live-streaming, Google+, YouTube channels, 
Requires at least: 3.4
Tested up to: 4.0
Stable tag: 2.4.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a shortcode and a template tag to embed the latest video or current live stream of one or more YouTube channels.

== Description ==

The plugin provides a shortcode and a template tag to embed the latest video or current live stream of one or more YouTube channels.
It comes with an options page to let you set the default options for YouTube ID(s), width, height, autoplay and count of latest videos to embed. You can customize these parameters with the shortcode and template tag if needed.

This also works great with YouTube Live, by following this fallback progression.

1. If you're currently live, display the live video.
2. If you're not currently live, display the next upcoming live event.
3. If there is no upcoming live event, display the text from Settings > Ixiter Show Latest YT > Show message if not broadcasting.
4. If there is no "not broadcasting" message, show the most recent YouTube video(s) from your channel(s).

This plugin is now frequently maintained by [Daniel J. Lewis](http://danieljlewis.net) from [The Audacity to Podcast](http://TheAudacitytoPodcast.com/).

== Installation ==

1. Upload the complete `ix-show-latest-yt` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. GoTo the plugins options page and setup your default settings
6. Done

== Frequently Asked Questions ==

= Is it possible to have the last 3 or so videos showing? =

Yes. You can set a default option for "count of latest videos to embed" and use a parameter/attribute in Template tag and shortcode as well.

= Is it possible to set the video start playing automatically? =

Yes. You can set a default option for "autoplay" and use a parameter/attribute in Template tag and shortcode as well. If you set "count of latest videos" higher than 1, only the first video will autoplay. If you manually insert more than one video, for the love of all mankind, please don't make them all autoplay.

= Can I use more than one YouTube channel? =

Yes. You can specify a YouTube channel for each shortcode and template tag instance. But you can also combine multiple YouTube channels by typing them as a comma-separated list (for example, `moritzhangouttv,theaudacitytopodcast,oncepodcast`) in the default settings, or for each shortcode and template tag instance.

= Can the player automatically refresh between events? =

Live-refresh is something we're working on and is coming soon.

= Can this display videos from a playlist? =

Not yet.

== Changelog ==

= 2.4.2 =
* November 14, 2014
* Fixed combining live/pending and non-live/non-pending channels.
* Made video size default to 640 × 360 (360p widescreen).

= 2.4.1 =
* October 13, 2014
* Minor tweak to remove some debug timecode. Sorry about that!

= 2.4 =
* October 10, 2014
* Enhancement: You can now embed more than one YouTube channel in the same player! Use a comma-separated list, like `moritzhangouttv,theaudacitytopodcast,oncepodcast` in the YouTube ID(s) field. The plugin will blend these channels together and display whichever is most recent or upcoming. This works for live, pending, and uploaded videos.
* Tweak: Revised how pending live events are ordered and displayed, after discovering a weird bug with some schedules.

= 2.3.2 =
* August 27, 2014
* Fix: Live events displaying in creation order instead of chronological (thanks, reachingnexus!)

= 2.3.1 =
* June 19, 2014
* Fix: Default settings for "autoplay" being ignored when embedding a single video
* Fix: Incorrect URL parameters in embed code on non-live instances
* New: "Show related" toggle in the settings with the default being disabled
* New: "related" option for shortcode and template tags

= 2.3 =
* May 16, 2014
* Removed pixel references in embed, to allow for auto, %, em, or px
* Updated documentation
* Hide related videos from embed
* Updated feed method to more compatible WordPress method
* Changed active to use pending as fallback

= 2.2 =
* September, 10th 2013
* No live message feature added

= 2.1.1 = 
* September, 6th 2013
* Fixed get_options bug

= 2.1 =
* April, 15th 2013
* Fixed a bug in channel setup for shortcode and template tag
* Fixed a bug in requesting the live video ID


= 2.0 =
* April, 12th 2013
* Major version change!
* removed the javascript and query YouTube from server side now
* added option for autoplay - suggested by [Angus Todman](http://wordpress.org/support/profile/angus-todman)
* added option for count of latest videos to embed - suggested by [hameiria](http://wordpress.org/support/profile/hameiria)


= 1.0 =
* November, 13th 2012
* First public version


== Upgrade Notice ==

= 2.4.2 =
Fixed combining live/pending and non-live/non-pending channels.

= 2.4.1 =
Minor tweak to remove some debug code timestamps.

= 2.4 =
Added support for combining multiple YouTube channels.

= 2.1 =
Fixed a bug in channel setup for shortcode and template tag.
Fixed a bug in requesting the live video ID

= 2.0 =
Javascript removed. "autoplay" and "count of latest videos to embed" options added.


== Credits ==
* [Moritz Tolxdorff](https://plus.google.com/u/0/+MoritzTolxdorff/about) - for the original javascript
* [Angus Todman](http://wordpress.org/support/profile/angus-todman) - for suggesting count of latest videos option
* [hameiria](http://wordpress.org/support/profile/hameiria) - for suggesting autoplay option and bug reports
* My Mum - for everything
* [Daniel J. Lewis](http://danieljlewis.net) - for adding more YouTube Live support and maintaining the plugin