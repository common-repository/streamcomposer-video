=== Plugin Name ===
Contributors: Jarl Gjessing
Tags: video, encoding, streaming, embed
Requires at least: 3.5
Tested up to: 4.7
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

StreamComposer Video plugin makes it possible for the user to upload videos through WordPress, the videos are then
automatically encoded for all devices, thumbnails are automatically created, and embedding directly from
WordPress to StreamComposer is then possible, making embedding videos an ease.
Using StreamComposer instead of many other video hosting services is unique in many aspects, please go to:
https://www.streamcomposer.com to read more.

In short, this plugin will make it possible for you to:<br>
* Upload videos directly to StreamComposer where they will be encoded once upload is done<br>
* Monitor all video encoding processes directly from the WordPress administration interface<br>
* Browse videos in StreamComposer directly from WordPress backend.<br>

Useful for all people that want videos on their website, but do NOT want users to be distracted from videos after the
video finishes like with Youtube.
StreamComposer includes CTA forms and advanced analytics tools that marketing departments will find very useful

== Frequently Asked Questions ==

= How can I obtain support for this product? =
Full support is completely free, and we will at all times try to help you the best we can.
Possibilities for support is:<br>
* Mail: support@streamcomposer.com<br>
* Report issues here: https://bitbucket.org/jegjessing/streamcomposer/issues/new

= How and why can I purchase the Pro or Premium version? =

You can purchase a license here:
[StreamComposer - Select the right plan for you](https://www.streamcomposer.com/Pricing/)
Reason for buying would be to achieve the possibility to use integration to other products in StreamComposer,
from within StreamComposer. You are in no way required to buy a license in order to use this plugin with StreamComposer.

= What are the system requirements? =

*  PHP 5.2.x or higher with Curl and JSON extensions
*  WordPress 3.3 or above

To use the StreamComposer API for other than Wordpress, you will need a Pro or Premium account at StreamComposer

== Installation ==

StreamComposer Plugin:

1. If you have not already, register at StreamComposer. This is required in order to store and encode your videos.<br>
2. In StreamComposer go to Profile->Advanced and copy the API key, or generate a new and copy that.<br>
3. Go to your WordPress admin control panel's plugin page.<br>
4. Search for 'StreamComposer Video'.<br>
5. Click Install.<br>
6. Activate on the plugin.<br>
7. You will now see a StreamComposer item in Wordpress administration, click on that. You can now paste in the API key, and press save.<br>

== Shortcodes ==

Attribute options for [streamcomposer-code...] shortcode

= src (required) =

The url pointing to the video at StreamComposer

= width/height =

Dimensions of the iframe to be ebedded.

= align =

How should the iframe be aligned

Please get in touch if you would like to make suggestions for further CSS configurability - email info@streamcomposer.com

== Screenshots ==

1. Copy your API key from app.streamcomposer.com
2. Paste it into the Wordpress plugin.
3. Upload a video.
4. You can track the encoding status.
5. You can list all your videos at StreamComposer, preview it and copy the link for your site, directly from within Wordpress.
6. Add the video directly from within the WYSIWYG editor.

== Changelog ==

= 1.0.1a =
Updated installation steps and other readme texts

= 1.0.1 =
Fixed issue with resumable not being loaded

= 1.0 =
Initial stable version

= 0.9.2 =
Initial version (Beta)
