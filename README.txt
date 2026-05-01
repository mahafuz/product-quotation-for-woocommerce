=== Quotify | Product Quotation - Request a Quote for WooCommerce ===
Contributors: mahfuz01
Tags: request a quote, price quote, woocommerce quote, quote cart, rfq
Requires at least: 4.0
WC requires at least: 6.0
WC tested up to: 10.1.2
Tested up to: 6.9
Stable tag: 2.6.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Allow your customer to add products to Quotation Cart and ask for price or any information regarding the order by submitting a Quotation form.

== Description ==

Transform your WooCommerce store into a powerful B2B quotation system with Quotify - Product Quotation for WooCommerce.

Quotify replaces the standard "Add to Cart" functionality with a professional quotation request workflow, perfect for wholesale businesses, custom product manufacturers, and service-based providers.

**What Makes Quotify Different?**

🎯 Modern React Dashboard - Lightning-fast admin interface to manage all quotations with advanced filtering, statistics, and bulk operations

📊 Real-Time Analytics - Track quotation performance with date-range filtering and live statistics dashboard

🔄 Full Variation Support - Customers select specific product variations, admins see complete product details with variation attributes

📧 Smart Email System - Customizable email templates with dynamic placeholders for professional customer communication

🔒 Built-in Security - Configurable rate limiting prevents spam and abuse, protecting your site from unwanted submissions

All features included free. No premium upsells, no hidden costs.

**Perfect For:**
- Wholesale businesses offering bulk pricing
- Custom product manufacturers (made-to-order items)
- B2B operations needing formal quote workflows
- Service-based businesses (consultations, custom solutions)
- Anyone needing flexible pricing beyond fixed cart prices

Stop losing customers who need personalized pricing. Quotify makes it easy to receive, manage, and respond to quotation requests professionally.

[See the Live Demo](http://wpdiscountx.com/shop/)


## 🚀 Features

**Customer Experience:**
* “Add to Quotation” buttons on shop and product pages
* Persistent quotation cart with session management
* Variable product support - Customers select specific variations (Color, Size, etc.)
* Customizable quotation form with predefined fields
* Real-time price updates based on quantity

**Admin Dashboard (New React SPA):**
* Lightning-fast modern interface for managing quotations
* Real-time statistics dashboard (Total, Pending, Approved, Trash, Total Value)
* Filter quotations by status (All, Pending, Approved, Trash)
* Advanced date-range filtering (Today, This Week, This Month, This Quarter, This Year, All Time)
* Search quotations by customer name, email, or content
* Bulk operations - Move to trash, restore, delete multiple quotations at once
* Quick status changes with one click
* Email customers directly from the dashboard
* View complete quotation details with product variation attributes
* Mobile-responsive design - manage from any device

**Email & Notifications:**
* Automatic admin email notifications for new quotation requests
* Customizable email subjects with dynamic placeholders
* Dynamic tags: {quotation_id}, {customer_name}, {site_name}, {date}, {time}, {customer_email}, {customer_subject}
* Professional HTML email templates included

**Security & Performance:**
* Configurable rate limiting to prevent form spam and abuse
* AJAX nonce verification on all requests for security
* Optimized database queries for fast performance
* Proper input sanitization and escaping

**Configuration Options:**
* Flexible button placement (shop pages, product pages, or both)
* Option to hide “Add to Cart” buttons for quotation-only mode
* Customizable form field labels
* Adjustable rate limit parameters (requests per time period)
* Toggle between custom email subjects or customer-provided subjects

**All features included free.**

## 💙 LOVED Product Quotation For WooCommerce? ##
- If you love Product Quotation For WooCommerce, rate us on [WordPress](https://wordpress.org/plugins/product-quotation-for-woocommerce/#reviews)


Visit [Product Quotation Form For WooCommerce](https://github.com/mahafuz/product-quotation-for-woocommerce) to learn more about how to do better in WordPress with [Help Tutorial, Tips & Tricks]https://github.com/mahafuz/product-quotation-for-woocommerce).

## Privacy Policy 
Product Quotation &#8211; Product Quotation For WooCommerce uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users. 

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).

== Installation ==

= Modern Way: =
1. Go to the WordPress Dashboard "Add New Plugin" section.
2. Search For "product-quotation-for-woocommerce".
3. Install, then Activate it.

= Old Way: =
1. Upload `product-quotation-for-woocommerce` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 2.7.0 - 01-05-2026 =
**✨ Major Update: Complete Admin Dashboard Overhaul**

* ✨ NEW: Modern React-based admin dashboard (single-page application)
* ✨ NEW: Real-time statistics dashboard with live counts and value tracking
* ✨ NEW: Advanced date filtering (Today, This Week, This Month, This Quarter, This Year, All Time)
* ✨ NEW: Search quotations by customer name, email, or content
* ✨ NEW: Bulk operations (move to trash, restore, delete) for efficient management
* ✨ NEW: Quick status changes directly from the interface
* ✨ NEW: Custom email subjects with dynamic placeholders
* ✨ NEW: Configurable rate limiting for form submission protection
* ✨ NEW: Product variation attributes displayed in quotation details
* ✨ IMPROVED: Optimized rendering performance (eliminates blinking during filter changes)
* ✨ IMPROVED: Date display now uses WordPress date format setting consistently
* ✨ IMPROVED: Email templates with customizable subjects
* ✨ FIXED: Date filter now correctly filters quotations by selected date range
* ✨ FIXED: get_author() method handles both post objects and post IDs
* ✨ FIXED: Resolved session management issues

= 2.6.0 - 06-04-2026 =
* ADDED: New dashboard for better quotation management.
* IMPROVEMENTS: Advanced UI Features for quotation management
* IMPROVEMENTS: Compatibility with latest wordpress versions.
* FIXED: Minor bugs

= 2.5.0 - 12-09-2025 =
* ADDED: New dashboard for better quotation management.
* IMPROVEMENTS: Compatibility with latest wordpress versions.
* FIXED: Minor bugs

= 2.0.4 - 24-05-2022 =
* ADDED: Translations supports
* INTEGRATED: Elementor addons for Quotation Cart.
* INTEGRATED: Languages, German, Spanish, French.
* IMPROVEMENTS: Cart functionality improvements for logged out users.
* Fixed: minor bugs

= 2.0.3 - 07-05-2022 =
* ADDED Feature: Send email to customers as well.
* IMPROVEMENTS: Quotation details design improvements.
* IMPROVEMENTS: added price and individual product note in the email template.
* Fixed: minor bugs

= 2.0.2 - 06-05-2022 =
* ADDED Feature: Price update while quantity gets updated.
* ADDED Feature: Option for setting up Quotation Cart page.
* Fixed: minor bugs

= 2.0.1 - 02-05-2022 =
* ADDED Feature: Privacy policy field on the Quotation Cart form
* Fixed: minor bugs

= 2.0.0 - 30-04-2022 =
* ADDED: New settings panel.
* ADDED: Numbers of new features.
* ADDED: Help Page
* Added: Form submission response
* REMOVED: Old entries backup
* Fixed: Minor bug fixes

= 1.2.5 - 28-04-2022 =
* Fixed: Quotation Cart page issue

= 1.2.4 - 28-04-2022 =
* Updated: Readme's

= 1.2.3 - 14-04-2022 =
* Fixed: Quotation button style issue.

= 1.2.2 - 14-04-2022 =
* Added privacy policy

= 1.2.1 - 14-04-2022 =
* Updated readme

= 1.2.0 - 14-04-2022 =
* Rewrite the whole plugin features
* Added new settings options.
* Small bug fixes

= 1.0.0 - 18-12-2020 =
* Initial release


== Frequently Asked Questions ==

= Does it work with any WordPress theme? =
Yes, it will work with any standard WordPress theme.

= Does it required WooCommerce plugin? =
Yes, it will only work with WooCommerce Plugin.

= How can I get support if my plugin is not working? =
If you have problems with this plugin or something is not working as it should, first, follow these preliminary steps:

* Test the plugin with a WordPress default theme, to be sure that the error is not caused by the theme you are currently using.
* Update the plugin with its latest version
* Deactivate the plugin and activate it again
* Deactivate all plugins you are using and check if the problem is still occurring.
If none of the previous listed actions helps you solve the problem, then, please feel free to [open an issue](https://github.com/mahafuz/product-quotation-for-woocommerce/issues)
Thanks!

== Screenshots ==

1. Add to Quotation from shop page - "Add to Quotation" button on product listing
2. Add to Quotation from Single Page - Variation selection and "Add to Quotation" button
3. Quotation Cart - Customer view with products added to cart
4. Quotations List - Modern React dashboard showing all quotations
5. Settings Panel - Configure buttons, email templates, and rate limiting
