import React, {useState, useEffect} from 'react'

import { FormToggle, SelectControl } from '@wordpress/components';
import { getPages, getCart, getNonce } from './../Helpers';

const GeneralSettings = ({ settings, setSettings, saveSettings }) => {
	const [pages, setPages] = useState([...getPages()]);
	const [cart, setCart] = useState(getCart( 'url' ) );

	useEffect(()=>{
		wp.ajax.send( 'pqfw_cart_get_permalink', {
			data: {
				_wpnonce: getNonce(),
				pageID: settings?.quotation_cart_page
			},
			success: ({ url }) => {
				setCart(url);
			}
		});
	}, [settings.quotation_cart_page]);

  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>General Settings</h3>
		<div className="inside">
			<p className="help">Customize email settings for better experience that will ensure the ease of use as you like.</p>
			<table className="form-table">
				<tr>
					<th>Hide "Add to cart" Button</th>
					<td>
						<FormToggle
							checked={ settings?.hide_add_to_cart_button }
							onChange={(e) => setSettings({
								...settings,
								hide_add_to_cart_button: e.target.checked
							})}
						/>
						<p className="description">Receive email for each user submitted quotatin from the <strong>Quotations Cart</strong> page.</p>
					</td>
				</tr>

				<tr>
					<th>Hide product prices</th>
					<td>
						<FormToggle
							checked={ settings?.hide_product_prices }
							onChange={(e) => setSettings({
								...settings,
								hide_product_prices: e.target.checked
							})}
						/>
						<p className="description">Receive email for each user submitted quotatin from the <strong>Quotations Cart</strong> page.</p>
					</td>
				</tr>

				<tr>
					<th>"Quotation cart" page</th>
					<td>
						<SelectControl
							label="Quotation cart page"
							value={ settings.quotation_cart_page }
							hideLabelFromVision={ true }
							options={ pages }
							onChange={(id) => setSettings({
								...settings,
								quotation_cart_page: id
							})}
						/>
						<p className="description">Choose from this list the page on which users will see the list of products added to the quote and send the request. Visit current <a target="_blank" href={cart}><strong>Quotation Cart Page</strong></a></p>
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

export default GeneralSettings