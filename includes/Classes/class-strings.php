<?php
/**
 * Responsible for registering shortocde.
 *
 * @since 1.2.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Registers shortcode for cart.
 *
 * @since 1.0.0
 */
class Strings {

	/**
	 * Return all translateable strings for settings panel.
	 *
	 * @since 2.0.3
	 */
	public function get() {
		return [
			// Index page.
			'app-page-title'                => __( 'Settings', 'pqfw' ),
			'general-settings-menu-label'   => __( 'General Settings', 'pqfw' ),
			'button-settings-menu-label'    => __( 'Button', 'pqfw' ),
			'form-settings-menu-label'      => __( 'Form', 'pqfw' ),
			'email-settings-menu-label'     => __( 'Email', 'pqfw' ),

			// General settings component.
			'gn-settings-title'             => __( 'General Settings', 'pqfw' ),
			'gn-settings-desc'              => __( 'Customize general settings for better experience that will ensure the ease of use as you like.', 'pqfw' ),
			'cart-btn-label'                => __( 'Hide "Add to cart" Button', 'pqfw' ),
			'cart-btn-desc'                 => __( 'Hide the "Add to cart" buttons on all products.', 'pqfw' ),
			'hide-price-label'              => __( 'Hide product prices', 'pqfw' ),
			'hide-price-desc'               => __( 'Hide product prices', 'pqfw' ),
			'select-cartpage-label'         => __( '"Quotation cart" page', 'pqfw' ),
			'select-cartpage-desc'          => __( 'Choose the quote cart page from the list where users will see the list of added products to the quote. Visit current', 'pqfw' ),
			'quotation-cart-page'           => __( 'Quotation Cart Page', 'pqfw' ),

			// Button settings component.
			'btn-settings-label'            => __( 'Button Settings', 'pqfw' ),
			'btn-settings-desc'             => __( 'For better experience choose your own button settings and styles that will ensure the design compatibility with your active theme, as well as functionality', 'pqfw' ),
			'show-btn-label'                => __( 'Show Button', 'pqfw' ),
			'show-btn-desc'                 => __( 'Show Add To Quotation button on category/shop/loop page', 'pqfw' ),
			'show-btn-desc-single-page'     => __( 'Show Add To Quotation button on product single page', 'pqfw' ),
			'btn-text-label'                => __( 'Button Text', 'pqfw' ),
			'btn-text-desc'                 => __( 'Change Add To Quote button text', 'pqfw' ),
			'btn-position-label'            => __( 'Button position in Loop', 'pqfw' ),
			'btn-position-desc'             => __( 'Select Add To Quote button position in the loop.', 'pqfw' ),
			'btn-options-1'                 => __( 'At product end', 'pqfw' ),
			'btn-options-2'                 => __( 'At product start', 'pqfw' ),
			'btn-options-3'                 => __( 'Before product title', 'pqfw' ),
			'btn-options-4'                 => __( 'After product title', 'pqfw' ),
			'btn-position-single-label'     => __( 'Button position in Single Product', 'pqfw' ),
			'btn-position-single-desc'      => __( 'Select Add To Quote button position in the single product page.', 'pqfw' ),
			'btn-position-single-options-1' => __( 'Before add to cart button', 'pqfw' ),
			'btn-position-single-options-2' => __( 'After add to cart button', 'pqfw' ),
			'btn-position-single-options-3' => __( 'End of product', 'pqfw' ),
			'btn-style-label'               => __( 'Button Style', 'pqfw' ),
			'normal'                        => __( 'Normal', 'pqfw' ),
			'hover'                         => __( 'Hover', 'pqfw' ),
			'text-color'                    => __( 'Text Color', 'pqfw' ),
			'background'                    => __( 'Background', 'pqfw' ),
			'reset-message'                 => __( 'Reset the custom style and back to theme default style?', 'pqfw' ),
			'reset'                         => __( 'Reset', 'pqfw' ),
			'font-size'                     => __( 'Font Size', 'pqfw' ),
			'width'                         => __( 'Width', 'pqfw' ),

			// Form settings component.
			'form-settings-label'           => __( 'Form Settings', 'pqfw' ),
			'form-settings-desc'            => __( 'For better experience choose your own form settings & styles that will ensure the design compatibility with your active theme.', 'pqfw' ),
			'default-form-style-label'      => __( 'Default Form Style', 'pqfw' ),
			'default-form-style-desc'       => __( 'Use default form style that comes with this plugin or you can clean design your own form styles rather not overriding each css class.', 'pqfw' ),
			'floated-form-label'            => __( 'Floated Form', 'pqfw' ),
			'floated-form-desc'             => __( 'Use floated or stacked styled form on the Quotations Cart Page.', 'pqfw' ),
			'add-pvp-label'                 => __( 'Add Privacy Policy', 'pqfw' ),
			'add-pvp-desc'                  => __( 'Ask user to accept terms and condition before submitting the quotation form.', 'pqfw' ),
			'pvp-label-label'               => __( 'Privacy Policy Label', 'pqfw' ),
			'pvp-label-desc'                => __( 'You can use the shortcode [terms] and [privacy_policy] (from WooCommerce 3.4.0)', 'pqfw' ),
			'pvp-content-label'             => __( 'Privacy Policy', 'pqfw' ),

			// Email settings component.
			'email-settings-label'          => __( 'Email Settings', 'pqfw' ),
			'email-settings-desc'           => __( 'Customize email settings for better experience that will ensure the ease of use as you like.', 'pqfw' ),
			'receive-email-label'           => __( 'Receive Email', 'pqfw' ),
			'receive-email-desc'            => __( 'Receive email for each user submitted quotation from the Quotations Cart page.', 'pqfw' ),
			'recipient-label'               => __( 'Recipient', 'pqfw' ),
			'recipient-desc'                => __( 'Add recipient email ID that will receive each quotation on the email.', 'pqfw' ),
			'send-email-label'              => __( 'Send Email', 'pqfw' ),
			'send-email-desc'               => __( 'Send a copy of the email to the customer as well for each submitted quotation from the Quotations Cart page.', 'pqfw' ),

			// '' => __( '', 'pqfw' ),

			// Save changes.
			'save-changes' => __( 'Save Changes', 'pqfw' ),
		];
	}
}