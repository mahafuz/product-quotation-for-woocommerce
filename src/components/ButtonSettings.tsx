import React from 'react'

import { FormToggle } from '@wordpress/components';

const ButtonSettings = ({ settings, setSettings, saveSettings }) => {
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
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>Save Changes</button>
		</div>
	</div>
  )
}

export default ButtonSettings