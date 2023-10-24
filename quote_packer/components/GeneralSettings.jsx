import React, { useState, useEffect } from 'react'

import { FormToggle, SelectControl } from '@wordpress/components';
import { getPages, getCart, getNonce, translate } from '../Helpers';

const GeneralSettings = ({ settings, setSettings, saveSettings }) => {
	const [pages, setPages] = useState([...getPages()]);
	const [cart, setCart] = useState(getCart('url'));

	useEffect(() => {
		wp.ajax.send('pqfw_cart_get_permalink', {
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
			<h3 className='pqfw-tab-title'>{translate('gn-settings-title')}</h3>
			<div className="inside">
				<p className="help">{translate('gn-settings-desc')}</p>
				<table className="form-table">
					<tr>
						<th>{translate('cart-btn-label')}</th>
						<td>
							<FormToggle
								checked={settings?.hide_add_to_cart_button}
								onChange={(e) => setSettings({
									...settings,
									hide_add_to_cart_button: e.target.checked
								})}
							/>
							<p className="description">{translate('cart-btn-desc')}</p>
						</td>
					</tr>

					<tr>
						<th>{translate('hide-price-label')}</th>
						<td>
							<FormToggle
								checked={settings?.hide_product_prices}
								onChange={(e) => setSettings({
									...settings,
									hide_product_prices: e.target.checked
								})}
							/>
							<p className="description">{translate('hide-price-desc')}</p>
						</td>
					</tr>

					<tr>
						<th>{translate('select-cartpage-label')}</th>
						<td>
							<SelectControl
								label={translate('quotation-cart-page')}
								value={settings.quotation_cart_page}
								hideLabelFromVision={true}
								options={pages}
								onChange={(id) => setSettings({
									...settings,
									quotation_cart_page: id
								})}
							/>
							<p className="description">{translate('select-cartpage-desc')} <a target="_blank" href={cart}><strong>{translate('quotation-cart-page')}</strong></a></p>
						</td>
					</tr>
				</table>
			</div>
			<div className="submit-wrapper">
				<button className="button button-primary" onClick={saveSettings}>{translate('save-changes')}</button>
			</div>
		</div>
	)
}

export default GeneralSettings