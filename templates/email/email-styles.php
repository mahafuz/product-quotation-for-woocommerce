<?php
/**
 * Email Template Styles
 *
 * Shared CSS styles for all Quotify email templates.
 *
 * @since 2.6.0
 * @package Quotify
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

// Global inline styles for email templates.
return [
	'body'              => 'background-color: #f6f6f6; width: 100%; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; '
		. '-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;',
	'container'         => 'display: block; margin: 0 auto !important; max-width: 580px; padding: 10px; width: 580px; background: white;',
	'content'           => 'box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 45px;',
	'main'              => 'background: #ffffff; border-radius: 3px; width: 100%;',
	'wrapper'           => 'box-sizing: border-box; padding: 20px;',
	'link'              => 'color: #3498db; text-decoration: underline;',
	'heading_primary'   => 'font-size: 35px; font-weight: 500; color: #000000; margin: 0 0 10px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; line-height: 1.4;',
	'heading_secondary' => 'font-size: 24px; font-weight: 500; color: #000000; margin: 0 0 10px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; line-height: 1.4;',
	'text'              => 'font-size: 14px; font-weight: normal; margin: 0 0 15px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; color: #333333;',
	'button_primary'    => 'box-sizing: border-box; display: inline-block; text-decoration: none; font-size: 14px; '
		. 'padding: 12px 30px; background: #7B68EE; color: #FFFFFF !important; border-radius: 6px; font-weight: normal;',
	'button_secondary'  => 'box-sizing: border-box; display: inline-block; text-decoration: none; font-size: 14px; '
		. 'padding: 12px 30px; background: #EAEBEE; color: #0A083A !important; border-radius: 6px; font-weight: normal;',
	'section_heading'   => 'color: #2c3e50; border-bottom: 2px solid #7B68EE; padding-bottom: 10px; margin: 30px 0 20px; font-size: 18px; font-weight: 600;',
	'box_info'          => 'background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;',
	'box_highlight'     => 'background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #7B68EE;',
	'box_notice'        => 'background-color: #fff8e1; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;',
	'box_contact'       => 'background-color: #e8f4f8; padding: 20px; border-radius: 5px; margin-top: 20px;',
	'box_actions'       => 'background-color: #e8f4f8; padding: 15px; border-radius: 5px; margin-top: 20px; text-align: center;',
	'footer'            => 'clear: both; margin-top: 30px; width: 100%; font-size: 15px;',
	'footer_text'       => 'color: #999999;',
	'align_center'      => 'text-align: center;',
	'meta_info'         => 'font-size: 12px; color: #95a5a6; margin-top: 20px; padding-top: 15px; border-top: 1px solid #ecf0f1;',
	'hr'                => 'border: 0; border-bottom: 1px solid #f6f6f6; margin: 20px 0;',
];
