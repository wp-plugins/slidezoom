=== SlideZoom ===
Contributors: TatMiNG@HongKong
Donate link: http://blog.miawork.com/?page_id=1317
Tags: Upload,Images,Highslide,Gallery,Slideshow,Post,Page
Requires at least: 2.2
Tested up to: 2.71
stable tag: 1.1.1

SlideZoom is a lightweight plugin allow bulk upload images and generate a HighSlide JS gallery. 

== Description ==
SlideZoom is a lightweight plugin allow bulk upload images and generate a HighSlide JS gallery.
Automatic resize image,support JPG,PNG,GIF Format.  
Output result as HTML code or BBCode and offering embed the gallery by simple copy and paste to posts or pages.

== Installation ==
**New Installation :**
`1. Download & unzip to WordPress plugin directory.
2. Active the plugin.
3. A new sub-menu [SlideZoom] will be appear under [setting]  ,go to setting page if you want.
4. Under Media tab , click on sub-menu [SlideZoom] to upload images.`

**Upgrade  :**
`1. First deactivate SlideZoom.
2. Download the latest SlideZoom , unzip and over write the old files.
3. Activate SlideZoom.`

== Frequently Asked Questions == 
= Do I need to download HighSlide JS or do somethings on WordPress's theme ? =

SlideZoom is Plug & Play plugin. 
You don't have to do anything at HighSlide JS Script , SlideZoom already hook up the script. And nothing to do about WordPress's theme. 
After you copy & paste the HTML Code in the textbox and publish the posts, HighSlide's script will load automatically.

= What happen if same filename is exist in my images folder? Will it be overwrite? =

No, SlideZoom using WordPress upload engine, if filename is same, newer file will be append a number as new filename.

= Any foot print ? =

The only foot print is SlideZoom need to write one line to WP database's options table , this line named : [slidezoom_options].
If you are neat freak :) , simple delete that line after deactivate.

= What happen after i deactivate SlideZoom, the images will lost on post/page ? =

SlideZoom is 100% flexible! After you deactivate the plugin , the thumbs/images you generated will keep in post/page.
What you lost is HighSlide effect and a nice plugin :)

= Your english is too bad ? Are you having speech and language disorders ? =

No, because i come from HongKong :) I speak Chinese.

== Screenshots ==

Please visit my blog page for demonstration : http://blog.miawork.com/?page_id=1317
If you find any bugs or have any ideas, please leave a message on the page above.

1. The Options Screen
2. The Result - Highslide JS Gallery
3. Output as HTML
4. Output as BBCode

== Changelog ==


     Version Date       Changes
   1.1.1   2009/4/17 Changed : This version is maintenance release. I suggest everyone should update to this version.
   								                     Fixed "Stack overflow in IE6 / IE7 / IE8" Javascript error alert.
   								  Changed : Update HighSlide JS to 4.1.4, and reduce the size of highslide.jp from 50KB to 25KB.
   								  Changed : Remove the "Powered by..." label. It is legal action. See: http://highslide.com/forum/viewtopic.php?f=4&t=608
   
   1.1.0   2009/3/31 Added : Output result as BBCode. 
   								  Added : Support resize images as thumbs, different from v1.0.0. v1.0.0 only using html tag to reduce the size, but now, it is REAL resize. 
   								                 thumbnails image's file name will be insert "thumb_" as begin. 
   								  Changed : Update HighSlide JS to 4.1.2
   								  Changed : Shrink the HighSlide JS package from 90KB to 50KB now, and remove unuse JS file.
   								  Changed : Refine the option and upload page layout.
   												  
   1.0.0   2009/3/27 Initial Release

== Future Development ==

1. Support upload by zip file format. Similar to some image hosting, you can zip your images and upload , SlideZoom will extract them automatically.
2. Support another PHP image process library.
3. More HighSlide JS Style.
4. Support and other Gallery,SlideShow or any cool Image Effect.

Any Suggestion? Please go to my Blog : http://blog.miawork.com/?page_id=1317 and leave a suggestion.
 
