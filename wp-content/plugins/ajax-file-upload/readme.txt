=== AJAX File Upload ===
Contributors: elhardoum
Tags: ajax, forms, shortcode, file, files, image, attachement, media, front-end, button, javascript, sharing, uploader, contact form, form, file upload, ajax upload
Requires at least: 3.6
Tested up to: 4.5.2
Stable tag: 0.1.1.1
Donate link: http://samelh.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fast and easy front-end WordPress file uploader with shortcodes fully extensible

== Description ==

This plugin will help you add file upload feature to your site, set maximum upload size, allowed file extensions, and much more through a simple shortcode or a custom function.

Totally AJAX, your uploads will be processed faster and an elegant way. All you need to do is to add the shortcode to your content, or call the plugin's custom function whithin your code and that's it.

You can either use <code>[ajax-file-upload /*settings as attributes*/]</code> shortcode to display the quick media upload buttons in the front-end, or use <code>do_shortcode('[ajax-file-upload ..]')</code> in your PHP templates, or the built-in function <code>ajax_file_upload( $args )</code> for which you should set the settings as an array in the 1 function parameter (those act like shortcode attributes, in case of confusion).

<strong>Some featues:<strong>


- Upload any type of media as long as your settings allow, nice and easy

- Set file extensions to let limit the uploads to only custom extensions, like for instance images (jpg,png,gif,bmp..)

- Set maximum upload size and when a user tries to upload a larger file, they will get a notice (which you can totally customize)

- Custom permission to upload, you can choose to allow uploads to certain user role, or logged-in users only, or everyone!

- Fully extensible, and creates custom JavaScript events which you can hook into to get the upload data settings, response, file, and much more (view docs)

You can always switch between settings from a shortcode to another, you are not obliged to use the same settings, but when a shortcode's settings are empty or the unique identifier attribute is not set then in this case, the default settings (you can change them in the admin) will be used.

Also, supports child theme. You can copy the entire plugin folder to your child theme and there modify the JavaScript, CSS, and even the shortcode template and other files. Basically any file except the main loader file.

This is totally free and open source plugin. You can contribute to it, fork it on Github, include it in your project and much more and always feel free to do so. Licensed under GNU GPL, just like major WordPress plugins and WordPress itself.

If you liked it, please leave us a useful review here on WordPress, share around the social media and star the Github repository. Thank you in advance!

More useful documentation can be found on Github https://github.com/elhardoum/AJAX-File-Upload and you can contact me anytime from this contact form: <a href=http://samelh.com/contact/>http://samelh.com/contact/</a>

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'AJAX File Upload', select this plugin and install and active.
3. Assuming you're done, you should be redirected to the 'about' page initially on first install.

== Screenshots ==

1. An example use in WpChats 3.0 where used to upload the profile cover photo and process it
2. Testing the uploader in my localhost
3. Plugin admin settings screen
4. Dumping the response data while listening to a custom JavaScript event by this plugin

= Extra =

1. Visit 'Settings > AJAX file upload' to set the default settings.
2. Visit 'index.php?page=afu-about' for more documentation and how to use and extend.

== Changelog ==

= 0.1 =
* Initial release