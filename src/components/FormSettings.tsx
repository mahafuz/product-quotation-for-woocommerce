import React from 'react'

import { FormToggle } from '@wordpress/components';

const FormSettings = ({ settings, setSettings, saveSettings }) => {
  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>Form Settings</h3>
		<div className="inside">
			<p className="help">For better experience choose your own form styles that will ensure the 
			design compatibility with your active theme.</p>

			<table className="form-table">
				<tr>
					<th>Default Form Style</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_form_default_design }
							onChange={(e) => setSettings({
								...settings,
								pqfw_form_default_design: e.target.checked
							})}
						/>
						<p className="description">Use default form style that comes with this plugin or you can clean design your own form styles rather not overriding each css class.</p>
					</td>
				</tr>
				{ settings?.pqfw_form_default_design ? (
					<tr>
						<th>Floated Form</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_floating_form }
								onChange={(e) => setSettings({
									...settings,
									pqfw_floating_form: e.target.checked
								})}
							/>
							<p className="description">Use floated or stacked styled form on the <strong>Quotations Cart</strong> Page.</p>
						</td>
					</tr>
				) : ''}
				<tr>
					<th>Add Privacy Policy</th>
					<td>
						<FormToggle
							checked={ settings?.privacy_policy }
							onChange={(e) => setSettings({
								...settings,
								privacy_policy: e.target.checked
							})}
						/>
						<p className="description">Ask user to accept terms and condition before submitting the quotation form.</p>
					</td>
				</tr>
				{ settings?.privacy_policy ? (
					<>
						<tr>
							<th>Privacy Policy Label</th>
							<td>
								<input
									type="text"
									className="regular-text"
									value={ settings?.privacy_policy_label }
									onChange={(e) => setSettings({
										...settings,
										privacy_policy_label: e.target.value
									})}
								/>
								<p className="description">You can use the shortcode [terms] and [privacy_policy] (from WooCommerce 3.4.0)</p>
							</td>
						</tr>
						<tr>
							<th>Privacy Policy</th>
							<td>
								<textarea
									cols="30"
									rows="5" className="regular-text"
									onChange={(e) => setSettings({
										...settings,
										privacy_policy_content: e.target.value
									})}
								>{ settings?.privacy_policy_content }</textarea>
								<p className="description">You can use the shortcode [terms] and [privacy_policy] (from WooCommerce 3.4.0)</p>
							</td>
						</tr>
					</>
				) : ''}
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>Save Changes</button>
		</div>
	</div>
  )
}

export default FormSettings