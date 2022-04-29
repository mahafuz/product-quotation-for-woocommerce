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
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>Save Changes</button>
		</div>
	</div>
  )
}

export default FormSettings