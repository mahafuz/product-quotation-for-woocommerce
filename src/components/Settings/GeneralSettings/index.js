import { FormToggle } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const GeneralSettings = ({ settings, setSettings, saveSettings }) => {

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

			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>{__('Save Changes')}</button>
		</div>
	</div>
  )
}

export default GeneralSettings