=== YouTube Embed ===
Contributors: dartiss
Donate link: https://artiss.blog/donate
Tags: embed, insert, video, youtube
Requires at least: 4.6
Tested up to: 5.4
Requires PHP: 5.3
Stable tag: 5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An incredibly fast, simple, yet powerful, method of embedding YouTube videos into your WordPress site.

== Description ==

YouTube Embed is an incredibly fast, simple, yet powerful, method of embedding YouTube videos into your WordPress site.

Key features include...

* Build your own playlists and play them back however you want
* Automatically generate playlists based on user name or search text
* Create multiple profiles - use them for different videos to get the exact style that you want
* Dynamic video sizing for responsive sites
* Housekept caching keeps the code generation nimble and your database tables slimline
* Google compatible metadata is added to the video output based on data provided by the YouTube API - great for SEO!
* Support for Do Not Track
* Compatible with [Video SEO for WordPress](http://yoast.com/wordpress/video-seo/ "Video SEO for WordPress"), [a3 lazy load](https://wordpress.org/plugins/a3-lazy-load/ "a3 lazy load") and [WordPress Video Overlay Ads](https://wordpress.org/plugins/video-overlay-ads/ "WordPress Video Overlay Ads") and many more. In the case of Video SEO and WordPress Video Overlay Ads, their options will even appear under the YouTube Embed menu for total simplicity!
* Use [Turn Off The Lights](https://www.turnoffthelights.com/ "Turn Off The Lights")? This plugin works with it beautifully.
* [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer "iFrame Resizer") and [FitVids.js](https://github.com/davatron5000/FitVids.js "FitVids.js") supported to improve content resizing
* Works "out of the box" with 4K, 60FPS and Chromecast - stream your embedded videos to your TV!
* And much, much more!

Please visit the [Github page](https://github.com/dartiss/youtube-embed "Github") for the latest code development, planned enhancements and known issues.

== 🚦 Getting Started ==

How easy is it to use? The fine people at [Webucator](https://www.webucator.com "Webucator") have put together an excellent video showing you how to get started with it..

https://www.youtube.com/watch?v=Wc7cvpQS-xQ

To add a video to a post or page simply use the shortcode `[[youtube]video[/youtube]]`, where `video` is the ID or URL of the YouTube video. Alternatively, you can add one (or more) widgets to your sidebar.

If you're not sure what the video ID is, please head to the FAQ section where it's explained in greater detail!

Within the administration area, click on the Settings menu option and then YouTube Embed to view and edit the generic settings for the plugin. Also under the YouTube Embed menu (see screenshot 1) you can click on the Profiles sub-menu to set the default options which define the output of your videos. Any videos you display (unless overridden by parameters - more on that later) will use the settings from the Profiles screen.

Although this document contains a lot of information more is provided on the various administration pages. Whilst on the administration pages, click on the "Help" button in the top right for some useful tips and links. If anything isn't covered and you're unsure of what it does please ask [on the forum](https://wordpress.org/support/plugin/youtube-embed "WordPress Plugins Forum").

== 🔑 Creating an API Key ==

At the top of the `YouTube Embed Settings` administration screen is an option to specify an API key. This is optional but many of the features of this plugin - including accurate video information being added to the metadata - will not be available without it. Thankfully an API key is easy to get and is free.

1. Head to the [YouTube Developers Console](https://console.developers.google.com/cloud-resource-manager "Developers Console")
2. Click on CREATE PROJECT
3. Once created, head into it
4. In the APIs box, click on "Go to APIs overview"
5. Click on "ENABLE APIS AND SERVICES" at the top of the screen
6. You can now choose your API - click on YouTube Data API v3
7. Click the "ENABLE" button
8. Click on "CREATE CREDENTIALS"
9. On the drop-downs, choose the API we selected before, "Web browser and "Public data"
10. You will now be presented with your API key

The API key can now be pasted into the settings in WP Admin.

== Further embedding options ==

A basic shortcode will embed your video using your default profile settings. However, you may wish to override some of these options on a video-by-video basis - this is done via parameters added to the shortcode.

e.g. `[[youtube width=300 height=200]Z_sCoHGIpU0[/youtube]]`

Which options are available depends upon the users's set-up (for example, whether they have Flash installed or not). You can specify any of the parameters but they may be ignored. Please see the Profile screen in Administration for further details on any restrictions which may exist.

* **annotation** - yes or no, this determines if annotations are shown
* **autoplay** - yes or no, should the video automatically start playing?
* **cc** - yes or no, decided whether closed captions are displayed. If you don't specify anything then the user's default will be used.
* **cc_lang** - Closed captions language. Select a [ISO 639-1 two-letter language code](http://www.loc.gov/standards/iso639-2/php/code_list.php") or leave blank for the default
* **color** - white or red, the color of the progress bar (see the FAQ about having a white progress bar with the light theme)
* **controls** - 0, 1 or 2, this decides whether the controls should display and when the Flash will load. A value of 0 will not show the controls but 1 or 2 will. A value of 2 will load Flash once the user initiates playback - otherwise it's loaded straight away.
* **disablekb** - yes or no, disable keyboard controls
* **fullscreen** - yes or no, this will add the fullscreen button to the toolbar
* **height** - the video height, in pixels
* **language** - The interface language. The parameter value is an [ISO 639-1 two-letter language code](http://www.loc.gov/standards/iso639-2/php/code_list.php") or a fully specified locale. Leave blank for the default
* **list** - if you've specified your own list, use this to select the way the videos should be output. Should be `random` (display videos in a random order), `single` (show just one video, randomly picked from the list) or `order` (show each video in the original order - this is the default)
* **loop** - yes or no, whether to start the video again once it ends
* **modest** - reduce the branding on the video
* **playsinline** - whether videos play inline or fullscreen in an HTML5 player on iOS.
* **profile** - specify a different default profile (see section on Profiles for further details)
* **ratio** - allows you to define a window ratio - specify just a height or width and the ratio will calculate the missing dimension. Uses the format x:x, e.g. 4:3, 16:9
* **related** - yes or no, show related videos
* **responsive** - whether to use responsive output or not. When switched on the video will resize when your site does (i.e. responsive). If a video width is supplied this will be the maximum width, otherwise full width will be assumed. Height is ignored and will be worked out automatically.
* **search** - yes or no, create a playlist based on a search word. The search word should be specified instead of a video ID. See "Automatically Generate Playlists" option for more details
* **start** - a number of seconds from where to start the video playing
* **stop** - this stops the video at a specific time, given in seconds
* **style** - apply CSS elements directly to the video output
* **template** - specify a template (see section on Templates for further details)
* **user** - yes or no, create a playlist based on a user's uploads. The search word should be specified instead of a video ID. See "Automatically Generate Playlists" option for more details
* **width** - the video width, in pixels

== Alternative Shortcodes ==

Within Administration, selecting Settings -> YouTube Embed will provide a list of generic options. One option is named `Alternative Shortcode` and allows you to specify another shortcode that will work exactly the same as the standard shortcode of `[[youtube]]`.

There are 2 reasons why you might want to do this...

1. If migrating from another plugin, it may use a different shortcode
2. If another plugin uses the same shortcode (e.g. Jetpack) this will allow you to specify and use an alternative

The new shortcode can also have its own default profile assigned to it (see the Profiles section for more details on this).

== Widgets ==

Widgets can be easily added. In Administration simply click on the Widgets option under the Appearance menu. YouTube Embed will be one of the listed widgets. Drag it to the appropriate sidebar on the right hand side and then choose your video options - any that aren't specified are taken from your supplied profile. It's best to have a profile set-up specifically for widgets!

If you wish to display an automatically generated playlist based on user name or search term, simply change the "ID Type" appropriately and then specify the name or search word(s) where the video ID would normally be entered.

And that's it! You can use unlimited widgets, so you can add different videos to different sidebars.

== Playlists ==

YouTube allows users to create their own playlists - collections of videos that can be played in sequence. These are embedded in the same way, by supplying the playlist ID.

e.g. `[[youtube]PLVTLbc6i-h_iuhdwUfuPDLFLXG2QQnz-x[/youtube]]`

An alternative to the YouTube build playlists is the build-in lists function - see the Lists section for further details.

== Templates ==

Both in the profile and as a parameter you can specify a template. This allows you to define any CSS that you wish to "wrap" around the YouTube output.

The template consists simply of your choice of HTML but with `%video%` where you wish the video to appear.

e.g. `<div align="center">%video%</div>`

For reasons of security, only specific HTML tags are valid - these are a, br, div, img, p span and strong. If there are any others that you'd like to have added, please let me know via the forum.

== Profiles ==

You've probably already had a look at the default profile, accessible by selecting Profiles from the YouTube Embed Administration menu option. Here you can specify the default option which will apply to any embedded video.

However, in the top right hand corner is a drop-down box and a button marked Change profile. Simply select an alternative profile and click the button and you can then edit the options for this alternative profile. You can even name it as well.

To use this profile, simply use the parameter `profile=` followed by the profile name or number. The options for this profile will then be used.

This could be useful, for instance, for having a separate profile for different parts of your site - posts, sidebar, etc - or for different video types (e.g. widescreen).

By default you have 5 extra profiles - if you wish to have more (or less) this number can be changed from the YouTube Embed settings screen.

== 📝 Lists ==

Although this plugin will play standard YouTube playlists their playback options are limited. Instead you can create your own video lists. Under the YouTube Embed administration menu is a sub-menu named Lists. Select this and you will be shown a screen where you can type in a list of video IDs (or URLS). You can also provide a name for the list.

When saving the list each video is validated.

As with profiles you can select the list from a drop down in the top right-hand corner. You can also change the number of lists from the Options sub-menu too.

To use a list, simply specify the list name or number instead of a video ID, as well as a parameter to specify how you wish the list to be played back.

e.g. `[[youtube list='order']List 1[/youtube]]`

The list parameter allows to to either play each in turn, play them randomly, or have just one played (but picked randomly).

An option within the general options screen allows you to change whether this parameter MUST be used. If switched on, you will gain a performance increase, otherwise the plugin has no way of knowing if you're asking for a playlist so must verify the ID you've specified against all your lists.

== Automatically Generated Playlists ==

YouTube includes options to automatically generate playlists based upon a user name or a search name.

To use, simply use the `user` or `search` parameter to switch the appropriate option on. Then, instead of a video ID or URL, you should specify either the user name or search word(s).

== 📏 Third Party Resizing Scripts ==

Within the YouTube Embed settings screen there is an option to set a third party resizing script - either [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer "iFrame Resizer") or [FitVids.js](https://github.com/davatron5000/FitVids.js "FitVids.js"). These work alongside the existing IFRAME but try and improve the output, particularly with regard to how the output is sized.

There are no guarantees with these and no support of their specific functionality is provided. However, if the video output is wrong then it's worth trying these.

== 🏙 Thumbnails ==

YouTube Embed also has the ability to return a thumbnail of a video (sorry, this doesn't work with playlists).

To use the shortcode method, insert `[youtube_thumb]id[/youtube_thumb]` into a post or page to create a thumbnail of the relevant video ID which, once clicked, will open up the appropriate YouTube page.

You can specify a number of parameters...

* **alt** - specify some `ALT` text for the thumbnail image
* **rel** - specify a REL override, e.g. rel="nofollow"
* **target** - specify a TARGET override, e.g. target="_blank"
* **width** - this specifies the width of the thumbnail image
* **height** - this specifies the height of the thumbnail image
* **nolink** - if set to `true`, will output the thumbnail without a link to the YouTube video, allowing you to add your own
* **version** - which version of the thumbnail to use. This can be `default` (120x90), `medium` (320x180), `high` (480x360), `standard` (640x480) or `maxres`
* **version** - which version of the thumbnail to use. This can be `default` (120x90), `medium` (320x180), `high` (480x360), `standard` (640x480) or `maxres`

e.g. `[youtube_thumb target="_blank" alt="Demo video"]id[/youtube_thumb]`

This overrides the `TARGET` and `ALT` elements of the thumbnail.

== 🗜 Shortened URL ==

You may return a short URL for any YouTube video by simply inserting `[youtube_url id=xx]` anywhere within a post. `xx` is the ID of the video.

== ⬇️ Downloading Videos ==

If you wish your users to be able to download a YouTube video or playlist then you can do this automatically.

In the Profiles screen within administration there is an option to automatically show a download link. You can specify some text or HTML to display as well as CSS. If you'd prefer to do this manually then you can use the shortcode `download_video`. The content to link is specified between the open and close shortcode tags and there are 3 parameters...

* **id** - The ID of the video or playlist. This is required.
* **target** - The target of the link (e.g. `_blank`). This is optional.
* **nofollow** - yes or no, use this to specify whether a `nofollow` tag should be added to the link. This is optional and by default it will be included.

e.g. `[download_video id="Z_sCoHGIpU0" target="_blank"]Download the video[/download_video]`

== ℹ️ Video Information ==

The shortcode of `vinfo` can be used to output useful video information. Simple pass the video ID using the parameter of `id` and then add any text between the opening and closing shortcode. If this text contains any of the following tags then they will be replaced with the relevant video information...

* %title% - the title of the video
* %description% - the video description
* %url% - a link to the video on YouTube
* %shorturl% - a shorturl of the video
* %download% - a link to a site where the video can be downloaded
* %thumb_default% - URL of a thumbnail image 120x90 pixels in size
* %thumb_medium% - URL of a thumbnail image 320x180 pixels in size
* %thumb_high% - URL of a thumbnail image 480x360 pixels in size
* %thumb_standard% - URL of a thumbnail image 640x480 pixels in size
* %thumb_maxres% - URL of a thumbnail image the biggest it can be, based on the original video size

These tags can be included in URLs as well. For example, if you added the following in the HTML view...

`[vinfo id="Z_sCoHGIpU0"]<a href="%url%"><img src="%thumb_default%"></a>[/vinfo]`

This would display a 120x90 pixel thumbnail with a clickable link to the original video.

== Other Settings ==

Under the Settings menu s a sub-menu named YouTube Embed. Select this and find the section named Embedding. There are 2 options here that have not been covered already...

1. Add Metadata - by default, RDFa metadata is added to video output. This can be switched on or off as required (see the FAQs for more information about metadata usage).
2. Feed - videos will not appear in feeds so use this option to decide whether you want them to be converted to links and/or thumbnails.

== ♥️ Reviews & Mentions ==

* [Your YouTube Plugin is fantastic-it just saved my life on this site. Thank you!](https://twitter.com/AaronWatters/status/237957701605404672?uid=16257815&iid=am-130280753913455685118891763&nid=4+248 "Twitter - Aaron Watters") - Sonic Clamp
* [New Technology Finds The Most Buzzed-About Parts Of Videos](http://www.socialtimes.com/2011/03/new-technology-finds-the-most-buzzed-about-parts-of-videos-interview/ "New Technology Finds The Most Buzzed-About Parts Of Videos") - SocialTimes
* [Andesch tips on WordPress plugins!](http://andershagstrom.se/andesch-tipsar-om-wordpress-plugins/ "Andesch tipsar om WordPress-plugins!") - Anders
* [Critical Mass](http://www.bikinginmemphis.com/2011/03/26/critical-mass/ "Critical Mass") - Biking in Memphis

== Installation ==

YouTube Embed can be found and installed via the Plugin menu within WordPress administration (Plugins -> Add New). Alternatively, it can be downloaded from WordPress.org and installed manually...

1. Upload the entire `youtube-embed` folder to your `wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress administration.

Voila! It's ready to go.

== Frequently Asked Questions ==

= I've upgraded to version 4 (or above) from an earlier version and I was using the widget feature to display videos =

I previously allowed some, although not all, parameters to be specified within the widget. However, as you can simply create your own profile for widgets I have removed this and, without leaving lots of redundant code behind, it was difficult to keep this backwards compatible.

Therefore, if you're upgrading you may find your widgets don't now display correctly. The best thing to do, beforehand if you can, is to create a profile just for the widgets and assign that to each. You'll probably find the video size is the bit most likely to cause issues. Apologies for this.

= How do I find the ID of a YouTube video? =

If you play a YouTube video, look at the URL - it will probably look something like this - `http://www.youtube.com/watch?v=L5Y4qzc_JTg`.

The video ID is the list of letters and numbers after `v=`, in this case `L5Y4qzc_JTg`.

= The video output is overlapping or stuttering =

If you go into the Profile screen in Administration there is a "Window Mode" option. This defines how Flash output interacts with any other around it. "Window" is the default and gives good performance but may cause overlapping. If overlapping is causing an issue try "Opaque".

= I'm getting an error saying that "an alternative plugin is using the [[youtube]] shortcode"  =

As this suggests another plugin that you have installed is using the same shortcode as YouTube Embed. That means that use of the `youtube` shortcode won't work. You have 2 possible actions to take...

1. Disable the conflicting plugin. If it's Jetpack then you can disable the Shortcode Embeds module.
2. Leave it as it is and use the option to use a second shortcode with this plugin. Head to the YouTube Embed settings screen and you can set up a secondary shortcode. You can also switch off the nag message too from here.

= The "autostart" feature is not working in iOS =

Unfortunately, this is a restriction that has been put in place by Apple.

= I can't get the video to play at a specific resolution by default =

There is no way to specify this - YouTube makes the decision on which version to play depending on a number of factors.

= There are black borders on top/underneath my video =

This is usually due to using a different ratio than the video was designed for. If you're not sure which ratio to use head to its page on YouTube, click on Share and then Embed and then Show More. A video size will be shown, which you can use to work out the correct ration for the video.

= The controls under the video don't display properly when using Firefox =

This is a bug in Firefox. Short term, switch on SSL in the Profiles screen and it will work. Longer term, I've [raised a bug report with Mozilla](https://bugzilla.mozilla.org/show_bug.cgi?id=1223515 "Bug 1223515 - Broken images in YouTube embedded player when not using SSL").

= The generated code does not cleanly validate =

No, by default it doesn't but it works absolutely fine as it. However, if you really must cleanly validate against HTML5 or transitional XHTML then head into the YouTube Embed settings screen and you'll find an option to "Improve Validation". Tick this and Save and it should validate.... UNLESS you have the metadata option switched on, in which case it won't validate still (sorry, I'm a slave to Google on this one!). Switch that off though and you're 100%.

One thing to note - by switching on "Improve Validation" you'll extend the length of the URL line that's passed to YouTube. Please see the next FAQ to understand this better.

= I'm getting the error "The maximum URL length has been exceeded" when trying to view a video =

When embedding a video a URL for YouTube is generated - this contains all the non-default parameters that you've specified and, if you've created a manual playlist, all of the video IDs. Unfortunately, URLs can only be 2000 characters in size. If this is exceeded you'll get the above error message when you try and view the video./Users/davidartiss/Documents/GitHub/youtube-embed/readme.txt

The solution is to reduce this down by reducing down your playlist or, if you have the "Improve Validation" settings switched on, switching that back off.

= I have another issue or a request =

Before reporting it please bear in mind that this plugin uses the standard YouTube API. Adding extra functionality to the player itself is not possible and there are [known issues](https://code.google.com/p/gdata-issues/issues/list?q=label:API-YouTube "YouTube API Known Issues") with it. I would also recommend performing a Google search for your issue too first, as this will often resolve a lot of queries.

== Screenshots ==

1. YouTube Embed in the administration menu
2. The main options screen
3. The profiles screen
4. The lists screen - videos have been added and validated. The drop-down help is also shown
5. The default widget options
6. The default visual editor options with the YouTube Embed button
7. The third party plugins menu

== Changelog ==

🔢 [Learn more about my version numbering methodology](https://artiss.blog/2016/09/wordpress-plugin-versioning/ "WordPress Plugin Versioning")

= 5.2 =
* Enhancement: What have I been doing during the pandemic? Picking through this code and resolving coding standards. Sigh. There's a LOT. Not all are done but will be in future updates but, for now, the majority are. Based on the amount of code I've had to change, I'm worried I've broken something. But, you know, every day's a school day and this has taught me a LOT
* Enhancement: Look, I know I keep moving it but the plugin settings really shouldn't be anywhere other than under the Settings menu. So I've moved it back there
* Enhancement: Added native lazy loading to the videos. I'll maybe look to add it to the thumbnails at a later time - does anybody want that?
* Enhancement: I've improved the cache key generation. But, what this does mean, is that updating will cause all your YouTube Embed caches to clear. All your cache are belong to us 
* Enhancement: Clarified the API sign-up process in the README

= 5.1.1 =
* Enhancement: Added extra plugin meta
* Bug: The URL separator was not always correct - it still seemed to work but it was sloppy
* Bug: Reduced the top padding on videos
* Bug: Single-video looping was not working due a change in how YouTube nows processes them (they now have to be single-video playlists for them to loop. Yeah, I know)

= 5.1 =
* Enhancement: Now supports specifying the language for the closed captions
* Enhancement: Moved interface language option from the settings screen to profiles
* Enhancement: You can now head to the settings screen and specify your own videos to be used on the profile screen
* Enhancement: Added option to allow the Closed Caption language to be specified
* Enhancement: Removed option in profile page to view an example 3D video. That just left a standard video and a playlist so, instead of switching between them, both examples are now set out on the page (so no need to switch now)
* Maintenance: Removed deprecated window mode, auto-hide, HTML 5 and theme options
* Maintenance: Removed the plugins menu
* Bug: The demo videos weren't working, so they've been updated
* Bug: Fixed an issue with the video cache, which was expiring too quickly

= 5.0.6 =
* Bug: It would appear that some of the code for the editor button was still lurking in dark corners of the plugin. That's caused those not rocking Gutenberg to see errors or, worst still, the dreaded "white screen of death". Apologies. I've now hoovered out all of the muck
* Bug: Whilst fixing the above, I noticed the title of one of the settings was wrong, so I've fixed that oo

= 5.0.5 =
* Maintenance: The `rel` parameter has changed and the `showinfo` has been deprecated. Have updated the plugin for these changes
* Maintenance: I've removed the editor button. It doesn't work after WP 5.0 and I don't wish to maintain it
* Maintenance: I'm now using a time constant when caching
* Maintenance: Added Github links to plugin meta
* Maintenance: Using the `checked` function for screen output
* Maintenance: A minor language tweak, where I was specifying something that should, ideally, be captured by translations instead

= 5.0.4 =
* Maintenance: Updated this README for better presentation in the new plugin directory. Funky.
* Maintenance: The minimum requirements for this plugin is now WordPress 4.6 so language files could be got rid of and various other bits of change. Smooth.
* Maintenance: Links to artiss.blog have been fixed. Smashing.

= 5.0.3 =
* Enhancement: Now allow the align parameter in the profile HTML

= 5.0.2 =
* Enhancement: I now include the shortcodes code whether you're in admin or not, as there is no performance improvement by doing otherwise
* Enhancement: After WordPress 4.6 you don't need to load the text domain. So I don't!
* Enhancement: Fixed XSS vulnerability by restricting which HTML tags can be used in the template field. Thanks to Tristan Madani for reporting this

= 5.0.1 =
* Maintenance: Now suppressing errors when fetching of API data fails
* Maintenance: Updated the drop-down of video sizes in the profiles screen, based upon latest YouTube recommendations
* Maintenance: Two sections of the README had the same name so wasn't displaying correctly on wordpress.org - now corrected!
* Maintenance: Updated the screenshots - something I should have done for 5.0 but forgot (mumbling apologies)
* Bug: Okay, I admit it, I was caching the videos when they were random user-generated playlists, which meant the random order was the same each time <sigh>. Now I suppress caching if this is the case
* Bug: For reasons I've yet to ascertain, some people appear to have an old value left over as the API key. I've therefore added a check for this and will clear it down if needs be
* Bug: Got rid of a number of PHP errors, most of which would have caused issues with the code as well
* Bug: No-one spotted this one <cue diabolical laughter> but I did - the list and profile counts weren't always correct. Also, the profiles count was getting a bit confused with the lists count and the list count had really no idea what it was doing generally. Basically, it's sorted
* Bug: To make things easier for myself I changed the array names that held the profile and list names. But, yeah, this means they weren't transferred from previous setups. Arse. Okay, that's now sorted
* Bug: In trying to resolve that last issue I found that the code that's supposed to run during a plugin update wasn't. Some jiggery-pokery later, it's been re-written and works like a dream!

= 5.0 =
* Enhancement: The API is back <fist pump>! The plugin can now fetch video information to add to the metadata
* Enhancement: Caching is back <double fist pump>! Now that I'm happy with the speed of the main code, I'm now happy to cache it
* Enhancement: Added housekeeping for the caching, because WordPress is rubbish at doing it itself <grumble, don't get me started>
* Enhancement: A new shortcode has been added to allow you to easily output video information to your post or page using a simple templating system
* Enhancement: Added the new API data to the metadata but also added more elements
* Enhancement: Improved the video ID validation if the API is not used
* Enhancement: WordPress has a nasty habit of modifying the video IDs that are passed to the plugin. Most of these we're already able to undo, but it's now been enhanced further to work even better
* Enhancement: The thumbnails shortcode now uses the API and, as a result, is slimmer and works with playlists too!
* Enhancement: Made some minor changes towards better accessibility. It's been pretty shoddy up until now but the next release should complete this work
* Maintenance: Data is passed around between functions via arrays rather than LOTS of variables, which is a much better of doing it
* Maintenance: Removed the old video validation process as this is now handled by the API function
* Maintenance: Widget changes to support the customizer in WP 4.5
* Maintenance: Changed the branding back. Because it's me you want - I understand that now
* Maintenance: Tested for WP 4.6 and PHP 7 compatibility because, you know, it's the future
* Maintenance: Re-wrote this bad boy README
* Bug: Fixed metadata output issues with playlists
* Bug: Fixed bug where lists would not display if the new performance option was switched on
* Bug: Fixed issue with the uninstaller

= 4.3.4 =
* Bug: Fixed issue with 3 hyphens in a video ID being converted to &#8212;

= 4.3.3 =
* Maintenance: One of the URLs in the README is being reported as being infected by malware. I have therefore removed this URL but have changed the release number, although no code has changed, to force the new version to download to existing users

= 4.3.2 =
* Bug: Correct validation of list field, which was preventing more than one video being specified
* Bug: Fixed the URL for the settings link in the plugin meta

= 4.3.1 =
* Bug: Had removed a function (by accident, you understand) that converted the old alternative shortcode settings to a new format. The result is that the conversion didn't take place and an array was passed as a shortcode name to WordPress Core, generating an error. The function has been added back in

= 4.3 =
* Enhancement: Added new "Third Party Plugins" screen, which lists third party plugins that work alongside YouTube Embed, adding extra functionality. You can view, install and see the status of these plugins from this screen
* Maintenance: Moved the settings back to the YouTube Embed menu
* Maintenance: Renamed menus to be more specific
* Maintenance: Updated the function names, which were still using the out-dated "vye" prefix

= 4.2.1 =
* Bug fix: Quotes were not being dealt with correctly in the template field. Now corrected.

= 4.2 =
* Enhancement: Re-written the core embedding code. Redundant code removed and, for the remaining code, re-imagined to improve performance
* Enhancement: Massively changed how the plugin retrieves saved options and, in particular, how I populate default values. The result? Even better performance
* Enhancement: Reviewed and reduced the calls to fetch the options
* Enhancement: Added a new general option to allow you to force a list type to be specified before a list can be used. The reason? By doing this the code doesn't have to check if the ID it a list name each time and it improves performance. For those upgrading this will be switched off by default to maintain backwards compatibility
* Enhancement: Admin bar options only appear if viewing the admin bar on the site (showing it whilst in the admin screens seemed rather pointless)
* Enhancement: If you use [WordPress Video Overlay Ads](https://wordpress.org/plugins/video-overlay-ads/ "WordPress Video Overlay Ads") or [Video SEO for WordPress](http://yoast.com/wordpress/video-seo/ "Video SEO for WordPress") then the settings menus will appear under the YouTube Embed menu
* Enhancement: SSL options removed as it's now used by default, including with thumbnails and schema.org links
* Enhancement: Improved the RSS feed output for search or user upload requests
* Maintenance: Removed some deprecated elements in the code
* Maintenance: Updated includes so that plugin folders were not hardcoded
* Maintenance: Updated which functions I was using to retrieve folder names so that they are fully SSL compatible
* Maintenance: Removed a load of redundant functions
* Maintenance: Replaced a function with a PHP command but this means that the plugin is no compatible with PHP versions below 5.1
* Maintenance: Tidied up the list screen further
* Maintenance: Replaced the 'dynamic' parameter with 'responsive' which, as a name, is far more accurate. 'dynamic' will still work, though.
* Maintenance: Improved admin screen validation and sanitization

= 4.1.1 =
* Bug fix: Corrected settings URL in plugin meta.
* Bug fix: If plugin has never run before, ensure a default options array is generated.
* Maintenance: Removed donation options and updated branding.

= 4.1 =
* Enhancement: Added 'Plays inline' option for iOS devices.
* Enhancement: Added extra Closed Caption option to better reflect how YouTube works - either on, off, or user default.
* Enhancement: Added new option to force HTML5 playback (if available). This is an undocumented feature and, as such, may not actually work, depending on YouTube's current mood. If YouTube aren't going to support it then I'm certainly not going to so please don't shout if it doesn't work.
* Enhancement: Lovely new icons for the lists page.
* Enhancement: Improved the layout of the demo video on the profiles page.
* Enhancement: Added a count to the profiles and lists screen to indicate how many are set up. Also showed on drop-down which of the profiles/lists are not defined.
* Enhancement: Changes made to add compatibility with a3 lazy load.
* Enhancement: Removed the caching - it created the output up to 3 times quicker but when that's 0.005 seconds it's not worth the hassle that comes with it. I started making performance improvements to compensate then realized it was such a big change that it would be best spun off as it's own separate update - version 4.2. Look out for it.
* Enhancement: Removed limitation of only being able to display up to 30 lists or profiles.
* Enhancement: When you can select a profile from another screen (e.g. defining a profile to an alternative shortcode) then only defined profiles will be listed.
* Enhancement: Added general option to specify the YouTube interface language as well as to suppress debug output in the resulting code.
* Enhancement: Fixed validation errors against thumbnails.
* Enhancement: Changed the "Frameborder" option to a more general "Improve Validation" which does, well, just that. It improves the generated markup so that it will validate better (if that's your bag).
* Enhancement: Added option to use a third party script to handle content resizing. Implemented [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer "iFrame Resizer") v3.5.1 and [FitVids.js](https://github.com/davatron5000/FitVids.js "FitVids.js") 1.1.
* Enhancement: If another plugin is using the same shortcode as this I now output a prompt in the admin area. Also added a setting to turn off this prompt just in case you're using the secondary shortcode and are happy for this.
* Enhancement: Re-written code for help screens, including adding more information and additional tabs.
* Maintenance: Moved the settings page to, well, the Settings menu option. It makes sense.
* Maintenance: Removed the enable of the JS API, which is now deprecated.
* Maintenance: Improved the admin screen output code - had made it more WordPress standard in version 4 but got some of the code wrong.

= 4.0.2 =
* Maintenance: Not really a bug, but in some circumstances I wasn't initializing a variable used when generating the embed code. It worked fine but wasn't best practice so fixed. Sloppy.
* Maintenance: Modified the default parameters so new user videos should appear with the same options as on YouTube itself. Consistent.
* Maintenance: I ABSOLUTELY refuse to call this a bug. But I was calling get_the_excerpt() to build some of the video meta data. For some reason, still unknown to me, other plugins were crashing as a result of it. I've removed it for now but am investigating the cause. Frustrating.
* Enhancement: WMODE is now only added to the embed URL if it's anything other than the default. Shrinkage.

= 4.0.1 =
* Maintenance: Left some debug code in by mistake. Doh. Sometimes I'd forget my own head if it wasn't screwed on.... Apologies to those affected.

= 4.0 =
* Maintenance: Removed a number of redundant/broken features. [Learn more](https://artiss.blog/youtube-embed-removed-features/ "Removed Features").
* Maintenance: Updated download link to use KeepVid.
* Maintenance: Re-written admin screen to use WordPress standard method of displaying settings. Oh, and the widget settings too.
* Maintenance: ...speaking of which, revised the options available to widgets.
* Maintenance: Merged many of the files where there wasn't a huge amount of content.
* Maintenance: Renamed menu slugs as they were too generic and may cause clashes with other plugins or themes that are silly enough to do the same thing.
* Enhancement: Revised profile screen to make it clearer, via the art of the icon, which parameters are compatible with which embed type.
* Enhancement: If you go a bit "ape" with the parameters and manual playlists, it's possible to exceed the URL size limit. I've now put a check in place to report this, if it occurs.
* Enhancement: Added modest branding as a parameter (before was only selectable via the profile screen).
* Enhancement: Improved the meta data.
* Bugs: Many of them. Fixed. Hoorah.

= 3.3.5 =
* Maintenance: Added missing text domain, ready for automatic translation.

= 3.3.4 =
* Maintenance: Updated admin screen headings for compatibility with 4.3.
* Maintenance: Updated demo video on profile page. Just because.
* Bug: Fixed (I hope) the problem with the editor button not appearing for some users. Thanks to Mark Aarhus for getting to the bottom of this for me.
* Enhancement: Added donation link to plugin meta. Because I'm worth it.

= 3.3.3 =
* Maintenance: Now working with newer playlist IDs (README instructions changed to reflect how to do this)
* Maintenance: Resolved widget issues with version 4.3 of WordPress
* Maintenance: Eliminated XSS problem in admin profile screen

= 3.3.2 =
* Bug: One of the files was corrupt in the previous release - this is now fixed. Sorry :(

= 3.3.1 =
* Maintenance: Remove reference to Google API, as videos are now not displaying as a result of v2 being retired. Will update the plugin more fully in future release

= 3.3 =
* Maintenance: Ding, dong Applian has gone. Removed Vixy branding, updated README and language files to match
* Maintenance: Removed those Vixy download links and restored the old method - will enhance this in a future release
* Maintenance: Plugin had too much baggage so it could support old versions of WordPress. Why? Updated it to only support more recent versions but have removed lots of un-needed guff as well. The result - this version is 15% slimmer than the previous version. Win!
* Maintenance: Spruced up the admin screens to match the new WordPress styling
* Bug: Resolved a number of bugs as reported by users and spotted by myself. Thanks all! More fixes to come

= 3.2.1 =
* Bug: Fixed issue where playlist was appearing for single videos
* Maintenance: Improved Metadata standard

= 3.2 =
* Bug: Prevented download bar SPAN from appearing even when switched off
* Bug: Fixed issue that caused playlists to not appear
* Enhancement: Added new shortcode for displaying video comments
* Enhancement: IFRAME output now includes metadata
* Enhancement: Editor button will now work with MCE4 editor (WP 3.9+)
* Enhancement: SVG icon used in admin menu for WP 3.8+

= 3.1 =
* Enhancement: Allow user to specify video resolution required (experimental)
* Enhancement: Different languages can be specified for transcripts, other than the English default
* Enhancement: API enabled on scripts by default, allowing for third-party modification
* Enhancement: Can now add a link to YouTube under a video
* Maintenance: Removed adverts from administration screen
* Maintenance: Changed download bar default to be opt-in and re-worded option text

= 3.0.1 =
* Bug: Fixed menu options shown in admin bar
* Maintenance: Updated links to point to instructions on Vixy.net website
* Enhancement: Validate download bar code to ensure it's secure
* Enhancement: Passing blog language to language bar for i18n

= 3.0 =
* Maintenance: Changed name, updated adverts, removed donation and sponsorship requests
* Maintenance: Renamed function to match new name and also removed prefix from files, which were not required
* Maintenance: Checked and updated all help URLs
* Maintenance: Removed about and instruction pages which were felt were no longer needed
* Maintenance: Updated icons
* Enhancement: Updated download links to use code from Vixy. This is now switched on by default
* Enhancement: Added option to provide an affiliate ID for use with the download bar - blog owners can make 30% from sales generated
* Enhancement: Simplified the menu access rules which has resulted in resolving a number of existing issues
* Bug: Fixed PHP error when allowing shortcodes in widgets

== Upgrade Notice ==

= 5.2 =
* Native lazy loading is a go! And lots of code quality tweaks that you won't notice unless you crack open the source code...