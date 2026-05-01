import {useState, useEffect} from 'react'

import { FormToggle, SelectControl } from '@wordpress/components';
import { makeRequest } from '@Utils/global';
import { getPages } from '@Utils/global';
import { getCartUrl } from '@Utils/cart';

import { __ } from '@wordpress/i18n';

const GeneralSettings = ({ settings, setSettings, saveSettings }) => {
	const [pages, setPages] = useState([...getPages()]);
	const [cart, setCart] = useState(getCartUrl());

	useEffect(()=>{
		makeRequest({
			action: 'quotify/cart/get_permalink',
			pageID: settings?.quotation_cart_page
		}).then(({ data }) => {
			setCart(data?.data?.url);
		});
	}, [settings.quotation_cart_page]);

  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>{__( 'General Settings' )}</h3>
		<div className="inside">
			<p className="help">{__('Configure global quotation settings to customize how your customers interact with the quotation system.')}</p>
			<table className="form-table">
				<tr>
					<th>{__('Hide "Add to cart" Button')}</th>
					<td>
						<FormToggle
							checked={ settings?.hide_add_to_cart_button }
							onChange={(e) => setSettings({
								...settings,
								hide_add_to_cart_button: e.target.checked
							})}
						/>
						<p className="description">{__('When enabled, replaces WooCommerce "Add to cart" buttons with quotation buttons on all product pages.')}</p>
					</td>
				</tr>

				<tr>
					<th>{__('Hide product prices')}</th>
					<td>
						<FormToggle
							checked={ settings?.hide_product_prices }
							onChange={(e) => setSettings({
								...settings,
								hide_product_prices: e.target.checked
							})}
						/>
						<p className="description">{__('Conceal product prices throughout your store to encourage customers to request quotations.', 'quotify')}</p>
					</td>
				</tr>

				<tr>
					<th>{__('Quotation Cart Page')}</th>
					<td>
						<SelectControl
							label={__('Quotation Cart Page')}
							value={ settings.quotation_cart_page }
							hideLabelFromVision={ true }
							options={ pages }
							onChange={(id) => setSettings({
								...settings,
								quotation_cart_page: id
							})}
						/>
						<p className="description">{__('Select the page where customers can view products added to their quotation. Currently viewing: ')} <a target="_blank" href={cart}><strong>{__('Quotation Cart Page', 'quotify')}</strong></a></p>
					</td>
				</tr>


				<tr>
					<th>{__('Empty cart message')}</th>
					<td>
						<input
							type="text"
							className="regular-text"
							value={ settings?.empty_cart_message || '' }
							onChange={(e) => setSettings({
								...settings,
								empty_cart_message: e.target.value
							})}
						/>
						<p className="description">{__('Custom message to display when the quotation cart is empty.')}</p>
					</td>
				</tr>
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>{__('Save Changes')}</button>
		</div>
	</div>
  )
}

export default GeneralSettings