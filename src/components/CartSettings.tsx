import React from 'react'

import { FormToggle, SelectControl } from '@wordpress/components';

import Select from 'react-select'

import { translate } from '../Helpers';

const CartSettings = ({ settings, setSettings, saveSettings }) => {

	return (
		<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
			<h3 className='pqfw-tab-title'>Cart Table</h3>
			<div className="inside">
				<p className="help">Customize quotation cart table settings for better experience that will ensure the ease of use as you like.</p>
				<table className="form-table">
					<tr>
						<th>Table Columns</th>
						<td>
							<Select
								closeMenuOnSelect={false}
								defaultValue={settings?.cart_table_columns}
								isMulti
								options={[
									{ value: 'thumbnail', label: 'Thumbnail' },
									{ value: 'product', label: 'Product' },
									{ value: 'price', label: 'Price' },
									{ value: 'quantity', label: 'Quantity' },
									{ value: 'message', label: 'Message' }
								]}
								onChange={(e) => setSettings({
									...settings,
									cart_table_columns: [...e]
								})}
							/>
							<p className="description">Customize table quotation table columns.</p>
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

export default CartSettings