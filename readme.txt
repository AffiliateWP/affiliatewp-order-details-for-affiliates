=== AffiliateWP - Order Details For Affiliates ===
Contributors: sumobi, mordauk, drewapicture
Tags: AffiliateWP, affiliate, Pippin Williamson, Andrew Munro, mordauk, pippinsplugins, sumobi, ecommerce, e-commerce, e commerce, selling, membership, referrals, marketing
Requires at least: 3.3
Tested up to: 5.3
Requires PHP: 5.3
Stable tag: 1.1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow affiliates to see order details on referrals they generated.

== Description ==

> This plugin requires [AffiliateWP](https://affiliatewp.com/ "AffiliateWP") <strong>It will NOT function without it.</strong>

This add-on adds a new area to the affiliate’s dashboard that allows a logged-in affiliate to see specific information about the order that their referral generated. Currently it works with both Easy Digital Downloads and WooCommerce.

Features:

1. Affiliates can see order details for each referral they generated from their affiliate dashboard
1. Globally enable access to the order details for all affiliates
1. Enable access on a per-affiliate level to the order details
1. Send an email to the affiliate with the order details included
1. Disable specific information from showing to the affiliate
1. A [affiliate_order_details] shortcode for showing the order details on any WordPress page

The following details can be shown an affiliate who has access:

1. Order Number
1. Order Date
1. Order Total
1. Referral Amount
1. Coupon Code Used
1. Customer Name
1. Customer Email
1. Customer Phone (only available in WooCommerce)
1. Customer Shipping Address (only available in WooCommerce)
1. Customer Billing Address (only available in WooCommerce)

These can also be easily turned off via a simple filter (see FAQ tab). In addition to disabling the information that is shown, you can customize the layout by editing the `dashboard-tab-order-details.php` template file from your child theme.

The affiliate will also be emailed these details at the time the referral was created.

**What is AffiliateWP?**

[AffiliateWP](https://affiliatewp.com/ "AffiliateWP") provides a complete affiliate management system for your WordPress website that seamlessly integrates with all major WordPress e-commerce and membership platforms. It aims to provide everything you need in a simple, clean, easy to use system that you will love to use.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin's name

Then you allow access in one of two ways:

1. Globally enable access for all affiliates. This can be done via the "Allow Global Access To Order Details" checkbox located in Affiliates &rarr; Settings &rarr; Integrations.
2. Enable access on a per-affiliate level. This can be done by editing an affiliate and enabling the "Order Details Access" checkbox located from Affiliates &rarr; Affiliates &rarr; Edit.

Note: When there is global access, the checkbox on the edit affiliate screen is not shown.

== Screenshots ==

== Changelog ==

= 1.1.5 =
* New: The "Order Details" tab now shows within the Affiliate Area Tabs add-on. Affiliate Area Tabs v1.1.6 and AffiliateWP v2.1.7 required.

= 1.1.4 =
* New: The Order Details tab now shows orders where the connected referral is either "paid" or "unpaid" (previously it only showed unpaid)
* New: Added zh_TW translation
* New: The coupon code used on the order is now shown in the order details
* New: The number of orders can now be configured via a new "number" shortcode attribute
* Tweak: The full customer name is now shown in the order details
* Fix: Email not sending if "Allow Global Access" is enabled

= 1.1.3 =
* Fix: Avoid a fatal error in WooCommerce 3.0.0+ on the Order Details tab when an order doesn't exist
* Fix: Other WooCommerce 3.0.0+ compatibility fixes

= 1.1.2 =
* Fix: An issue with the tab's content not loading correctly due to recent changes made in AffiliateWP v1.8.1

= 1.1.1 =
* Fix: The [affiliate_order_details] shortcode was not checking to see if the affiliate had access to the order details

= 1.1 =
* New [affiliate_order_details] shortcode for showing the order details on any WordPress page
* New: Support for WooCommerce Sequential Order Numbers Pro
* New: Table columns will now disappear if all details in that table column is hidden
* New: Specific details can be hidden by enabling admin checkboxes
* Tweak: Moved add-on settings to the "integrations" tab of AffiliateWP
* Tweak: Show "unpaid" referrals by default. This can be changed via the affwp_odfa_referral_args filter

= 1.0.1 =
* New: EDD integration now supports Shipping Address provided by EDD's [Simple Shipping](https://easydigitaldownloads.com/extensions/simple-shipping/ "Simple Shipping")
* New: EDD integration now supports Billing Address provided by payment gateways

= 1.0 =
* Initial release
