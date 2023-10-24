import React, { useState, useRef, useEffect } from 'react'

import {
	FormToggle,
	ColorPicker,
	TabPanel,
	ColorIndicator,
	RangeControl,
	SelectControl
} from '@wordpress/components';

import resetIcon from './../images/reset.png';
import { translate } from '../Helpers';

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
		<h3 className='pqfw-tab-title'>{translate( 'btn-settings-label' )}</h3>
		<div className="inside">
			<p className="help">{translate( 'btn-settings-desc' )}</p>

			<table className="form-table">
				<tr>
					<th>{translate('show-btn-label')}</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_shop_page_button }
							onChange={(e) => setSettings({
								...settings,
								pqfw_shop_page_button: e.target.checked
							})}
						/>
						<p className="description">{translate('show-btn-desc')}</p>
					</td>
				</tr>
				<tr>
					<th>{translate('show-btn-label')}</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_product_page_button }
							onChange={(e) => setSettings({
								...settings,
								pqfw_product_page_button: e.target.checked
							})}
						/>
						<p className="description">{translate( 'show-btn-desc-single-page' )}</p>
					</td>
				</tr>
				<tr>
					<th>{translate('btn-text-label')}</th>
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
						<p className="description">{translate('btn-text-desc')}</p>
					</td>
				</tr>
				<tr>
					<th>{translate( 'btn-position-label' )}</th>
					<td>
						<SelectControl
							label={translate('btn-position-label')}
							value={ settings?.button_position }
							hideLabelFromVision={ true }
							options={[
								{
									label : translate( 'btn-options-1' ),
									value : 'woocommerce_after_shop_loop_item'
								},
								{
									label : translate( 'btn-options-2' ),
									value : 'woocommerce_before_shop_loop_item'
								},
								{
									label : translate( 'btn-options-3' ),
									value : 'woocommerce_before_shop_loop_item_title'
								},
								{
									label: translate( 'btn-options-4' ),
									value: 'woocommerce_after_shop_loop_item_title'
								},
							]}
							onChange={(position) => setSettings({
								...settings,
								button_position: position
							})}
						/>
						<p className="description">{translate( 'btn-position-desc' )}</p>
					</td>
				</tr>
				<tr>
					<th>{translate( 'btn-position-single-label' )}</th>
					<td>
						<SelectControl
							label={translate( 'btn-position-single-label' )}
							value={ settings?.button_position_single_product }
							hideLabelFromVision={ true }
							options={[
								{
									label: translate('btn-position-single-options-1'),
									value: 'woocommerce_after_add_to_cart_quantity'
								},
								{
									label: translate('btn-position-single-options-2'),
									value: 'woocommerce_after_add_to_cart_button'
								},
								{
									label: translate( 'btn-position-single-options-3' ),
									value: 'woocommerce_share'
								},
							]}
							onChange={(position) => setSettings({
								...settings,
								button_position_single_product: position
							})}
						/>
						<p className="description">{translate( 'btn-position-single-desc' )}</p>
					</td>
				</tr>
				<tr>
					<th>{translate( 'btn-style-label' )}</th>
					<td>
						<TabPanel
							className="button-style-tab-panel"
							activeClass="active-tab"
							initialTabName='normal'
							tabs={ [
								{
									name: 'normal',
									title: translate( 'normal' ),
									className: 'normal-color',
								},
								{
									name: 'hover',
									title: translate( 'hover' ),
									className: 'hover-color',
								},
							] }
						>
							{ ( tab ) => (
								<div className='pqfw-color-picker-container'>
									{ tab.name === 'hover' && (
										<>
											<p className="color-picker-label">{translate( 'text-color' )}</p>
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

											<p className="color-picker-label">{translate( 'background' )}</p>
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
												if ( confirm( translate( 'reset-message' ) ) ) {
													setSettings({
														...settings,
														button_hover_color: '',
														button_hover_bg_color: ''
													})
												}
											}} className="pqfw-reset-btn"><img src={resetIcon} />{translate( 'reset' )}</button>
										</>
									)}
									{
										tab.name === 'normal' && (
											<>
												<p className="color-picker-label">{translate( 'text-color' )}</p>
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

												<p className="color-picker-label">{translate( 'background' )}</p>
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

												<p className="color-picker-label">{translate( 'font-size' )}</p>
												<RangeControl
													value={ settings?.button_font_size }
													onChange={( value ) => setSettings({
														...settings,
														button_font_size: value
													})}
													max={ 50 }
												/>

												<p className="color-picker-label">{translate( 'width' )}</p>
												<RangeControl
													value={ settings?.button_width }
													onChange={( value ) => setSettings({
														...settings,
														button_width: value
													})}
													max={ 300 }
												/>
												<button onClick={() => {
													if ( confirm( translate( 'reset-message' ) ) ) {
														setSettings({
															...settings,
															button_normal_color: '',
															button_normal_bg_color: '',
															button_font_size: 0,
															button_width: 0
														})
													}
												}} className="pqfw-reset-btn"><img src={resetIcon} />{translate( 'reset' )}</button>
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
			<button className="button button-primary" onClick={saveSettings}>{translate( 'save-changes' )}</button>
		</div>
	</div>
  )
}

export default ButtonSettings