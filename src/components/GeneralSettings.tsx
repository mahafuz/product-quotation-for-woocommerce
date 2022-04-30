import React from 'react'

import { FormToggle } from '@wordpress/components';

const GeneralSettings = ({ settings, setSettings, saveSettings }) => {

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
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>Save Changes</button>
		</div>
	</div>
  )
}

export default GeneralSettings