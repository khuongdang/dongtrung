=== YITH WooCommerce Affiliates ===

Contributors: yithemes
Tags:  affiliate, affiliate marketing, affiliate plugin, affiliate tool, affiliates, woocommerce affiliates, woocommerce referral, lead, link, marketing, money, partner, referral, referral links, referrer, sales, woocommerce, wp e-commerce, affiliate campaign, affiliate marketing, affiliate plugin, affiliate program, affiliate software, affiliate tool, track affiliates, tracking, affiliates manager, yit, yith, yithemes, yit affiliates, yith affiliates, yithemes affiliates
Requires at least: 4.0.0
Tested up to: 4.5.2
Stable tag: 1.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

YITH WooCommerce Affiliates allows you to create affiliate profiles and grant your affiliates earnings each time someone purchases from their link.

== Description ==

Do you want to improve your sales by sharing advertising of your products and of your site on other blogs to get visibility? But, maybe, you have not the time and the patience to browse the web in search of the right ones? No problem, you can let your users share your products on their sites and blogs in exchange of a commission for each product sold. Don’t you know how to do? No problem, again: you are in the right place, because here you find YITH WooCommerce Affiliates that makes this for you in a few clicks. Try it and you'll see on your own how it works!

= Free Features =

* When a user visits the store with a refer ID in query string, the plugin saves the affiliate ID and credits commissions to him/her if this visit turns into purchase
* Refer ID will be stored in user's cookie for a time that can be set in admin panel; this way, even though visit and purchase do not happen during the same navigation session, commissions can be credited correctly
* You can credit commissions only to affiliates that have registered and have been enabled correctly
* You can create new affiliates directly from users registered to your site
* You can use a shortcode to allow affiliate registration
* You can set a general commission for affiliates for each order coming from their refer ID
* Commission status changes automatically each time order status changes
* The plugin manages automatically all totals concerning affiliates and updates them according to the status of commissions and orders
* The plugin calculates automatically refunds and decrements the total of affiliate commissions in case of refunds
* You can register payments that have to be made to affiliates
* You can handle basic reports that can be filtered by date
* You can customise parameters for the cookies managed by the plugin
* Affiliates can access their own dashboard, where they find all information about sales trend
* You can use the shortcode "Generate link" to generate links for taking users to your site with the correct refer ID

== Installation ==

1. Unzip the downloaded zip file.
2. Upload the plugin folder into the `wp-content/plugins/` directory of your WordPress site.
3. Activate `YITH WooCommerce Affiliates` from Plugins page

YITH WooCommerce Affiliates will add a new submenu called "Affiliates" under "YIT Plugins" menu. Here you are able to configure all the plugin settings.

== Screenshots ==

1. [Admin] List of commissions
2. [Admin] List of affiliates
3. [Admin] List of payments
4. [Admin] Marketplace stats
5. [Admin] Marketplace settings
6. [Users] Affiliate dashboard
7. [Users] List of commissions
8. [Users] Click lists
9. [Users] List of payments
10. [Users] Link generator
11. [Users] User settings
12. [Users] Affiliate settings

== Changelog ==

= 1.0.8 - Released: Jun, 08 - 2016 =

* Added: support WC 2.6 RC1
* Added: style for #yith_wcaf_order_referral_commissions, #yith_wcaf_payment_affiliate, #yith_wcaf_commission_payments
* Added: per page input in affiliate dashboard
* Tweak: added filter yith_wcaf_is_hosted to filter check over submitted host / server name match in link_generator callback
* Fixed: column ordering anchor in affiliate dashboard


= 1.0.7 - Released: May, 05 - 2016 =

* Added: WordPress 4.5.x support
* Fixed: removed useless library invocation
* Fixed: generate link shortcode (removed protocol before check for local url)

= 1.0.6 - Released: Apr, 05 - 2016 =

* Added filter "yith_wcaf_is_valid_token" to is_valid_token
* Tweak changed EOL to LF
* Tweak: Performance improved with new plugin core 2.0
* Fixed order awaiting payment handling
* Fixed view problems due to new YITH menu page slug
* Fixed generate link shortcode (url parsing improvements)
* Fixed affiliate research
* Fixed plugin-fw loading

= 1.0.5 - Released: Oct, 16 - 2015 =

* Added: Option to prevent referral cookie to expire
* Tweak: Increased expire seconds limit
* Tweak: Changed disabled attribute in readonly attribute for link-generator template
* Fixed: Commissions/Payment status now translatable from .po files
* Fixed: Fatal error occurring sometimes when using YOAST on backend

= 1.0.4 - Released: Aug, 13 - 2015 =

* Added: Compatibility with WC 2.4.2
* Tweak: Added missing text domain on link-generator template (thanks to dabodude)
* Tweak: Updated internal plugin-fw

= 1.0.3 - Released: Aug, 05 - 2015 =

* Fixed: minor bugs

= 1.0.2 - Released: Apr, 03 - 2015 =

* Tweak: Improved older PHP versions compatibility (removed dynamic class invocation)

= 1.0.1 - Released: Jul, 31 - 2015 =

* Fixed: fatal error for PHP version older then 5.5

= 1.0.0 - Released: Jul, 30 - 2015 =

* Initial release

== Suggestions ==

If you have suggestions about how to improve YITH WooCommerce Affiliates, you can [write us](mailto:plugins@yithemes.com "Your Inspiration Themes") so we can bundle them into YITH WooCommerce Affiliates.

== Translators ==

= Available Languages =
* English (Default)

If you have created your own language pack, or have an update for an existing one, you can send [gettext PO and MO file](http://codex.wordpress.org/Translating_WordPress "Translating WordPress")
[use](http://yithemes.com/contact/ "Your Inspiration Themes") so we can bundle it into YITH WooCommerce Affiliates Languages.