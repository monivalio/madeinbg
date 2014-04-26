=== Plugin Name ===
Contributors: Rustaurius 
Tags: product catalogue, product catalog, restaurant menu, responsive, customizable CSS, SEO friendly, affiliate links
Requires at least: 3.5.0
Tested up to: 3.9
License: GPLv3
License URI:http://www.gnu.org/licenses/gpl-3.0.html

Displays a product catalog(ue) or menu for your store, restaurant, group, etc. Has three default responsive layouts and can accept custom CSS.

== Description ==

Displays a product catalogue or menu for your store, restaurant, group, etc. Has three default responsive layouts and can accept custom CSS.

You can use categories, sub-categories and tags to make your products easy to sort through for your visitors.
You can also use categories and sub-categories in your catalogue(s) to make it easy to keep them up to date. 

[youtube http://www.youtube.com/watch?v=z6XL7whjY1Q]

Key Features:

* 3 default layout formats, users can tab between them
* Fully customizable via CSS
* SEO friendly single product pages
* UTF8 support
* Drag-and-drop to re-order your catalogues
* Upload products from a spreadsheet
* Change starting layout by setting the "starting_layout" attribute
* Exclude one or more layouts by using the "excluded_layouts" attribute (accepts a comma-separated list)
* Options page lets you customize a number of a options

To get the most out of the Ultimate Product Catalogue Plugin, FancyBox for WordPress is required (http://wordpress.org/plugins/fancybox-for-wordpress/).
If FancyBox for WordPress isn't installed, individual products will be displayed on their own pages.

Tutorial videos available in the FAQ section.

Additional Languages:
- Brazilian Portugese (thanks to <a href='http://wordpress.org/support/profile/tito_cadallora'>Tito_Cadallora</a>);
- Lithuanian (thanks to <a href='http://wordpress.org/support/profile/adart'>AdArt</a>);

== Installation ==

1. Upload the `ultimate-product-catalogue` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place "[product-catalogue id='X']" on the page where you want your catalogue to display, where X is the ID of the catalogue to display
4. *The page that displays the product catalogue needs to be a full-width type page for the catalogue to display correctly*

--------------------------------------------------------------

To get started using the plugin, you can:
- Go to the "Products" tab in the Ultimate Product Catalogue Plugin (UPCP) admin menu, add products one at a time or by uploading a spreadsheet
- Create categories in the "Categories" tab, then create sub-categories in the "Sub-Categories" tab and assign your sub-categories to the correct category
- Create a catalogue in the "Catalogues" tab, then add products, categories, or sub-categories to that catalogue
- Add additional images to a product by clicking on it and then adding them from the "Product Details" screen
- Create tags in the "Tags" tab, then assign as many tags as necessary to each product from the "Product Details" screen
- Re-order your catalogues by dragging and dropping items in the "Catalogue Details" screen


== Frequently Asked Questions ==

= I have more than one catalogue. What do I do? =

You can use the attribute "id='X'" in the product-catalogue shortcode to specify a catalogue, where 'X' is replaced with the catalogue ID (available at the top of the catalogue details page).

= How do I remove the sidebar from my catalogue? =

You can use the attribute "sidebar='No'" in the product-catalogue shortcode to remove the sidebar from a catalogue.

= Can I start with a layout other than the default "Thumbnail" layout? =

Add the attribute "starting_layout='LAYOUT'", where LAYOUT is replaced with the layout you would like to be the starting layout (List or Detail are the two options currently).

= How do I exclude one of the layouts? =

The attribute "excluded_layouts" lets you stop one or more layouts from being displayed. It accepts a comma separated list of layouts you wish to exclude. For example, "exclude_layouts='Thumbnail, List'" would make your catalogue display only the "Detail" view.

= What is enabled in the 'Premium' version? =

The premium version allows you to add more than 100 products to the plugin, to use the 'Tags' feature, and to add additional images to products.

= Why do I get a "Page not found" error when I turn on SEO friendly links? =

You need to have some kind of pretty permalinks already enabled on your blog for them to work on the plugin. 

Videos

Tutorial Part 1
[youtube http://www.youtube.com/watch?v=9sdHtGGZpKU]

Tutorial Part 2
[youtube http://www.youtube.com/watch?v=KBhdiNCtLvM]

Premium Features
[youtube http://www.youtube.com/watch?v=l9mWNqOIB_w]

== Screenshots ==

1. The "detail" catalogue view
2. A product's detailed view, with mutiple images that can be clicked to display in the main image area
3. The "thumbnail" catalogue view
4. The "list" catalogue view
5. The admin area

== Changelog ==
= 2.2.3 =
- Small update for uploading products from spreadsheets

= 2.2.2 =
- Bug fixes for custom fields
- Shortcodes can now be used in product descriptions

= 2.2.1 =
- Two small bug fixes

= 2.2 =
- New Premium feature: Custom Fields!
- Custom fields let you add fields to your products, that can be included in the description of your products via shortcode, so that you can have a common product template
- New feature: Non-displayed products
- Non-displayed products let you temporarily remove a product from all catalogues (ex: if it's out of stock)
- Tags can now be imported via spreadsheet
- Catalogue height now adjusts depending on the size of the current layout
- Tutorial videos are available in the FAQ section

= 2.1.5 =
- Small bug fix
- Some language files added

= 2.1.4 =
- Three small fixes for the front-end and product page

= 2.1.3 =
- Small fix for "Tags" functionality with new AJAX filtering
- Small optimizations to return catalogue quicker after users filter the catalogue

= 2.1.2 =
- Beta AJAX catalogue filtering as an option
- Number of characters in a product's "details" view description added as an option
- Fixed a small catalogue detail's page bug

= 2.1 =
- Implemented view counting for products, based on clicks on title or image links
- Added mobile stylesheet (v1) and product sorting for premium users
- Increased compatibility for uploaded product spreadsheets (more forgiving of small errors in column names, better error reporting)
- Attempted to make tables compatible with MySQL strict mode
- Fixed an error where SEO friendly URL's stopped working shortly after being setup

= 2.0.1 =
- Added in the WordPress Uploader for product images
- SEO friendly single product URLs are now an option
- Plugin tables now use UTF8 encoding

= 2.0 =
- Added an 'Options' page
- Added 'Read More' as an option on the 'Options' page
- Added 'Color Scheme' as an option on the 'Options' page
- Added 'Tags Logic' as an option on the 'Options' page
- Added 'Product Links' as an option on the 'Options' page
- Implemented a premium version for new users

= 1.2 = 
- Added pagination for the admin pages to allow easier access to all products
- Added a 'product link' field for sites participating in affiliate programs
- Made shortcode easier to find
- Added single product pages if FancyBox for WordPress isn't installed
- Fixed the issue with product images being deleted on upgrade
- Fixed category labels behaviour when products are being filtered

= 1.1 = 
- Added localization (hopefully!)
- Added an "initial_layout" shortcode
- Added an "exclude_layouts" shortcode
- Added individual product URLs, when blogs have FancyBox for WordPress installed
- Made a number of small changes

= 1.0 =
Initial Version.

== Upgrade Notice ==

= 2.1.4 = 

- Three small fixes for the front-end and product page
