import React, { useState, useRef, useEffect } from 'react'

import { FormToggle, ColorPicker, TabPanel, ColorIndicator, RangeControl, SelectControl } from '@wordpress/components';

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

	console.log('settings', settings);
  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>Button Settings</h3>
		<div className="inside">
			<p className="help">For better experience choose your own button settings and styles that will ensure the 
			design compatibility with your active theme, as wel as functionality</p>

			<table className="form-table">
				<tr>
					<th>Show Button</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_shop_page_button }
							onChange={(e) => setSettings({
								...settings,
								pqfw_shop_page_button: e.target.checked
							})}
						/>
						<p className="description">Show <strong>Add To Quotation</strong> button on category/shop/loop page</p>
					</td>
				</tr>
				<tr>
					<th>Show Button</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_product_page_button }
							onChange={(e) => setSettings({
								...settings,
								pqfw_product_page_button: e.target.checked
							})}
						/>
						<p className="description">Show <strong>Add To Quotation</strong> button on product single page</p>
					</td>
				</tr>
				<tr>
					<th>Button Text</th>
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
						<p className="description">Show <strong>Add To Quotation</strong> button on product single page</p>
					</td>
				</tr>
				<tr>
					<th>Button position in Loop</th>
					<td>
						<SelectControl
							label="Button position in Loop"
							value={ settings?.button_position }
							hideLabelFromVision={ true }
							options={[
								{ label: 'At product end', value: 'woocommerce_after_shop_loop_item' },
								{ label: 'At product start', value: 'woocommerce_before_shop_loop_item' },
								{ label: 'Before product title', value: 'woocommerce_before_shop_loop_item_title' },
								{ label: 'After product title', value: 'woocommerce_after_shop_loop_item_title' },
							]}
							onChange={(position) => setSettings({
								...settings,
								button_position: position
							})}
						/>
						<p className="description">Receive email for each user submitted quotatin from the <strong>Quotations Cart</strong> page.</p>
					</td>
				</tr>
				<tr>
					<th>Button Style</th>
					<td>
						<TabPanel
							className="my-tab-panel"
							activeClass="active-tab"
							initialTabName='normal'
							tabs={ [
								{
									name: 'normal',
									title: 'Normal',
									className: 'normal-color',
								},
								{
									name: 'hover',
									title: 'Hover',
									className: 'hover-color',
								},
							] }
						>
							{ ( tab ) => (
								<div className='pqfw-color-picker-container'>
									{ tab.name === 'hover' && (
										<>
											<p className="color-picker-label">Text Color</p>
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

											<p className="color-picker-label">Background</p>
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
										</>
									)}
									{
										tab.name === 'normal' && (
											<>
												<p className="color-picker-label">Text Color</p>
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

												<p className="color-picker-label">Background</p>
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

												<p className="color-picker-label">Font Size</p>
												<RangeControl
													value={ settings?.button_font_size }
													onChange={( value ) => setSettings({
														...settings,
														button_font_size: value
													})}
													max={ 50 }
												/>

												<p className="color-picker-label">Width</p>
												<RangeControl
													value={ settings?.button_width }
													onChange={( value ) => setSettings({
														...settings,
														button_width: value
													})}
													max={ 300 }
												/>
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
			<button className="button button-primary" onClick={saveSettings}>Save Changes</button>
		</div>
	</div>
  )
}

export default ButtonSettings