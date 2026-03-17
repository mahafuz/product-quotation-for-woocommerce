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
			<p className="help">{__('Customize general settings for better experience that will ensure the ease of use as you like.')}</p>
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
						<p className="description">{__('Hide the "Add to cart" buttons on all products.')}</p>
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
						<p className="description">{('Hide product prices')}</p>
					</td>
				</tr>

				<tr>
					<th>{__('"Quotation cart" page')}</th>
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
						<p className="description">{__('Choose the quote cart page from the list where users will see the list of added products to the quote. Visit current')} <a target="_blank" href={cart}><strong>{__('Quotation Cart Page')}</strong></a></p>
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