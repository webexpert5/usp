=== Woo File Dropzone ===
Contributors: gmbhurgri
Donate link: https://www.paypal.me/murtaza853
Tags: woocommerce, woocommerce upload file, woocommerce file upload, woocommerce file uploader, upload files, gmbhurgri, dropzone, file upload
Requires at least: 4.1
Tested up to: 4.8
Stable tag: 1.1.6

Enables customer to send files directly when they purchase items, during product detail page, cart page or checkout page.

== Description ==

**Woo File Dropzone | WooCommerce upload file**

= Important =
* For Testing; Please log in Wp admin and front store in separate browser, because due to some WooCommerce Session Conflict, there was some issues with WP admin logged in and testing file Upload in same browser.
* For Documentation read this post <http://gmbhurgri.com/woo-file-dropzone-woocommerce-plugin-user-guide/>

= Features =
* This plugin will able you to get files from customers on following basis:
	* Product Detail Page
	* Cart Page
	* Checkout Page

* You can also get details/feedback from customer in small text box (textfield) and/or in a large textbox (textarea).
* Full Control over file management for store admin.
* Provide facility to customers for File Preview, Error Notices & Remove file before uploading if they want.
* Extensive file cleanup process on many stages if user left sites or removed items from cart.
* Wordpress built-in scheduling (cron job) for files which were uploaded by users but no proceeding to complete order (abandoned order and did nothing after adding into cart and uploading files).
* Very strong security check with wordpress built in wp_nonce fields.
* File validation checking on both client and server side.
* Store admin will receive files directly in order details page.
* Plugin uses standard woocommerce hooks to perfom file handling.
* Popover positioning and all labels customization.
* Strong coding practices of file auto-loading and clean code for customization.
* Compatible with all themes that follow WooCommerce Standard
* Error Log maintain.
* Can be used for any type of files. Images, PDF,Docx, xls etc
* Standard wordpress shrotcodes that can be used in woocommerce files.

**Plugin Uses**

1. Bootstrap 3
2. DropzoneJs Javascript Library
4. Formvalidator JQuery Plugin



== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings-> Woo File Dropzone screen to configure the plugin
4. Make sure you have configure plugin correctly.


== Screenshots ==

1. File Dropbox Detail page.
2. Configuration Screen
3. Order Detail page

== Changelog ==

= 1.1.6 =
Fixed: Minor Bug

= 1.1.5 =
Fixed: previously 1.1.4 changes were not affected

= 1.1.4 =
Fixed: Issue on cart and checkout page after file upload send button not showing.
Tested: Compatibility for WordPress 4.8

= 1.1.3 =
Fixed: Guest upload before add to cart was lost due to session issue.


= 1.1.2 =
* Document update nothing on logic level

= 1.1.1 =
* Fix Minor bug fixes

= 1.1.0 =
* Improved Admin settings User Interface.
* Added Support to enable Mandatory upload. User must have to upload before adding item into cart
* Reload of uploaded files in box if user want to edit files.
* Various Code Performance.
* File box UI improvement so more space for uploading files.
* Disable front User file upload if Admin is logged in. Sometimes WooCommerce session is problematic.

= 1.0.7 =
* Woocommerce session conflict when admin changes order status
* (Note for testing plugin): As now in front a check has been added, that plugin should process when admin is not logged. so upload file in another browser.

= 1.0.6 =
* Small bug due to 1.0.5 feature implementation

= 1.0.5 =
* Added Product and variation title in admin panel.

= 1.0.2 =
* Fixed bug in product detail page file box, showing send button after file sent
* Updated configuration page
* Success Message on settings save
* Included detailed documentation in pdf file

= 1.0.1 =
* Various UI Improvements in file dropbox
* CSS minification
