import { useState, useEffect } from 'react';
import { TextControl, SelectControl } from '@wordpress/components';
import { makeRequest, getPages } from '@Utils/global';
import { getCartUrl } from '@Utils/cart';

import { __ } from '@wordpress/i18n';

const CartSettings = ({ settings, setSettings, saveSettings }) => {
	const [pages, setPages] = useState([...getPages()]);
	const [cart, setCart] = useState(getCartUrl());

	useEffect(() => {
		makeRequest({
			action: 'quotify/cart/get_permalink',
			pageID: settings?.quotation_cart_page
		}).then(({ data }) => {
			setCart(data?.data?.url);
		});
	}, [settings.quotation_cart_page]);

  return (
	<div id="pqfw-settings-cart" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>{__( 'Cart Settings' )}</h3>
		<div className="inside">
			<p className="help">{__('Customize the quotation cart display and messaging for your customers.')}</p>
			<table className="form-table">
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
						<TextControl
							value={ settings?.empty_cart_message || '' }
							onChange={(value) => setSettings({
								...settings,
								empty_cart_message: value
							})}
						/>
						<p className="description">{__('Custom message to display when the quotation cart is empty.', 'quotify')}</p>
					</td>
				</tr>
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>{__('Save Changes')}</button>
		</div>
	</div>
  );
};

export default CartSettings;
