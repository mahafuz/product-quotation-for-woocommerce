import { useState, useRef, useEffect } from 'react'

import { __ } from '@wordpress/i18n';

import {
	FormToggle,
	ColorPicker,
	TabPanel,
	ColorIndicator,
	RangeControl,
	SelectControl
} from '@wordpress/components';

import resetIcon from './../../../images/reset.png';

const ButtonSettings = ({ settings, setSettings, saveSettings }) => {
	const [ hoverColorVisible, sethoverVisibleColor ] = useState(false);
	const [ hoverVisibleBg, sethoverVisibleBg ] = useState(false);
	const [ normalColorVisible, setNormalColorVisible ] = useState(false);
	const [ normalBgVisible, setNormalBgVisible ] = useState(false);
	const hoverColorRef = useRef(null);
	const hoverBgRef = useRef(null);
	const normalColorRef = useRef(null);
	const normalBgRef = useRef(null);

	const handleClickOutside = (event) => {
		if (hoverColorRef.current && !hoverColorRef.current.contains(event.target)) {
			sethoverVisibleColor(false)
		}
		if (hoverBgRef.current && !hoverBgRef.current.contains(event.target)) {
			sethoverVisibleBg(false)
		}
		if (normalColorRef.current && !normalColorRef.current.contains(event.target)) {
			setNormalColorVisible(false)
		}
		if (normalBgRef.current && !normalBgRef.current.contains(event.target)) {
			setNormalBgVisible(false)
		}
	}

	useEffect(() => {
		// Bind the event listener
		document.addEventListener("mousedown", handleClickOutside);
		return () => {
			// Unbind the event listener on clean up
			document.removeEventListener("mousedown", handleClickOutside);
		};
	}, [handleClickOutside]);

  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
			<h3 className='pqfw-tab-title'>{__( 'Button Settings' )}</h3>
			<div className="inside">
				<p className="help">{__( 'For better experience choose your own button settings and styles that will ensure the design compatibility with your active theme, as well as functionality' )}</p>

				<table className="form-table">
					<tr>
						<th>{__('Show Button')}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_shop_page_button }
								onChange={(e) => setSettings({
									...settings,
									pqfw_shop_page_button: e.target.checked
								})}
							/>
							<p className="description">{__('Show Add To Quotation button on category/shop/loop page', 'quotify')}</p>
						</td>
					</tr>
					<tr>
						<th>{__('Show Button')}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_product_page_button }
								onChange={(e) => setSettings({
									...settings,
									pqfw_product_page_button: e.target.checked
								})}
							/>
							<p className="description">{__( 'Show Add To Quotation button on product single page' )}</p>
						</td>
					</tr>
					<tr>
						<th>{__('Button Text')}</th>
						<td>
							<input
								type="text"
								className="regular-text"
								value={settings?.button_text}
								onChange={(e) => setSettings({
									...settings,
									button_text: e.target.value
								})}
							/>
							<p className="description">{__('Change Add To Quote button text')}</p>
						</td>
					</tr>
					<tr>
						<th>{__('Cart Button Text')}</th>
						<td>
							<input
								type="text"
								className="regular-text"
								value={settings?.cart_button_text}
								onChange={(e) => setSettings({
									...settings,
									cart_button_text: e.target.value
								})}
							/>
							<p className="description">{__('Change View Quotation Cart button text')}</p>
						</td>
					</tr>
					<tr>
						<th>{__( 'Button position in Loop' )}</th>
						<td>
							<SelectControl
								label={__('Button position in Loop')}
								value={ settings?.button_position }
								hideLabelFromVision={ true }
								options={[
									{
										label : __( 'At product end' ),
										value : 'woocommerce_after_shop_loop_item'
									},
									{
										label : __( 'At product start' ),
										value : 'woocommerce_before_shop_loop_item'
									},
									{
										label : __( 'Before product title' ),
										value : 'woocommerce_before_shop_loop_item_title'
									},
									{
										label: __( 'After product title' ),
										value: 'woocommerce_after_shop_loop_item_title'
									},
								]}
								onChange={(position) => setSettings({
									...settings,
									button_position: position
								})}
							/>
							<p className="description">{__( 'Select Add To Quote button position in the loop.' )}</p>
						</td>
					</tr>
					<tr>
						<th>{__( 'Button position in Single Product' )}</th>
						<td>
							<SelectControl
								label={__( 'Button position in Single Product' )}
								value={ settings?.button_position_single_product }
								hideLabelFromVision={ true }
								options={[
									{
										label: __('Before add to cart button'),
										value: 'woocommerce_after_add_to_cart_quantity'
									},
									{
										label: __('After add to cart button'),
										value: 'woocommerce_after_add_to_cart_button'
									},
									{
										label: __( 'End of product' ),
										value: 'woocommerce_share'
									},
								]}
								onChange={(position) => setSettings({
									...settings,
									button_position_single_product: position
								})}
							/>
							<p className="description">{__( 'Select Add To Quote button position in the single product page.' )}</p>
						</td>
					</tr>
					<tr>
						<th>{__( 'Button Style' )}</th>
						<td>
							<TabPanel
								className="button-style-tab-panel"
								activeClass="active-tab"
								initialTabName='normal'
								tabs={ [
									{
										name: 'normal',
										title: __( 'Normal' ),
										className: 'normal-color',
									},
									{
										name: 'hover',
										title: __( 'Hover' ),
										className: 'hover-color',
									},
								] }
							>
								{ ( tab ) => (
									<div className='pqfw-color-picker-container'>
										{ tab.name === 'hover' && (
											<>
												<p className="color-picker-label">{__( 'Text Color' )}</p>
												<ColorIndicator
													colorValue={settings?.button_hover_color}
													onClick={() => sethoverVisibleColor(! hoverColorVisible)}
												/>
												{hoverColorVisible && (
													<div className='pqfw-hover-color-container' ref={hoverColorRef}>
														<ColorPicker
															color={settings?.button_hover_color}
															onChange={(color) => setSettings({...settings, button_hover_color: color})}
															enableAlpha={false}
															defaultValue={settings?.button_hover_color}
														/>
													</div>
												)}

												<p className="color-picker-label">{__( 'Background' )}</p>
												<ColorIndicator
													colorValue={settings?.button_hover_bg_color}
													onClick={() => sethoverVisibleBg(! hoverVisibleBg)}
												/>
												{ hoverVisibleBg && (
													<div className='pqfw-hover-color-container' ref={hoverBgRef}>
														<ColorPicker
															color={settings?.button_hover_bg_color}
															onChange={(color) => setSettings({
																...settings,
																button_hover_bg_color: color
															})}
															enableAlpha={false}
															defaultValue={settings?.button_hover_bg_color}
														/>
													</div>
												)}

												<button onClick={() => {
													if ( confirm( __( 'Reset the custom style and back to theme default style?' ) ) ) {
														setSettings({
															...settings,
															button_hover_color: '',
															button_hover_bg_color: ''
														})
													}
												}} className="pqfw-reset-btn"><img src={resetIcon} />{__( 'Reset' )}</button>
											</>
										)}
										{
											tab.name === 'normal' && (
												<>
													<p className="color-picker-label">{__( 'Text Color' )}</p>
													<ColorIndicator
														colorValue={settings?.button_normal_color}
														onClick={() => setNormalColorVisible(! normalColorVisible)}
													/>
													{ normalColorVisible && (
													<div className="pqfw-normal-color-container" ref={normalColorRef}>
														<ColorPicker
															color={settings?.button_normal_color}
															onChange={(color) => setSettings({
																...settings,
																button_normal_color: color
															})}
															enableAlpha={false}
															defaultValue={settings?.button_normal_color}
														/>
													</div>)}

													<p className="color-picker-label">{__( 'Background' )}</p>
													<ColorIndicator
														colorValue={settings?.button_normal_bg_color}
														onClick={() => setNormalBgVisible(! normalBgVisible)}
													/>
													{ normalBgVisible && (
														<div className="pqfw-normal-color-container" ref={normalBgRef}>
															<ColorPicker
																color={settings?.button_normal_bg_color}
																onChange={(color) => setSettings({
																	...settings,
																	button_normal_bg_color: color
																})}
																enableAlpha={false}
																defaultValue={settings?.button_normal_bg_color}
															/>
														</div>)}

													<p className="color-picker-label">{__( 'Font Size' )}</p>
													<RangeControl
														value={ settings?.button_font_size }
														onChange={( value ) => setSettings({
															...settings,
															button_font_size: value
														})}
														max={ 50 }
													/>

													<p className="color-picker-label">{__( 'Width' )}</p>
													<RangeControl
														value={ settings?.button_width }
														onChange={( value ) => setSettings({
															...settings,
															button_width: value
														})}
														max={ 300 }
													/>
													<button onClick={() => {
														if ( confirm( __( 'Reset the custom style and back to theme default style?' ) ) ) {
															setSettings({
																...settings,
																button_normal_color: '',
																button_normal_bg_color: '',
																button_font_size: 0,
																button_width: 0
															})
														}
													}} className="pqfw-reset-btn"><img src={resetIcon} />{__( 'Reset' )}</button>
												</>
											)
										}
									</div>
								) }
							</TabPanel>
						</td>
					</tr>
				</table>
			</div>
			<div className="submit-wrapper">
				<button className="button button-primary" onClick={saveSettings}>{__( 'Save Changes' )}</button>
			</div>
		</div>
	  )
}

export default ButtonSettings
