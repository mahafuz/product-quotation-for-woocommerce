import React from 'react'

import { FormToggle } from '@wordpress/components';

const EmailSettings = ({ settings, setSettings, saveSettings }) => {

  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>Email Settings</h3>
		<div className="inside">
			<p className="help">Customize email settings for better experience that will ensure the ease of use as you like.</p>
			<table className="form-table">
        <tr>
					<th>Receive Email</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_form_send_mail }
							onChange={(e) => setSettings({
								...settings,
								pqfw_form_send_mail: e.target.checked
							})}
						/>
						<p className="description">Receive email for each user submitted quotatin from the 
            <strong>Quotations Cart</strong> page.</p>
					</td>
				</tr>
				<tr>
					{/* TODO: Mail validation */}
					<th>Recipient</th>
					<td>
						<input
							type="text"
							value={settings?.recipient}
							className="regular-text"
							onChange={(e) => setSettings({
								...settings,
								recipient: e.target.value
							})}
						/>
						<p className="description">Add recipient email ID that will receive each quotation on the email.</p>
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

export default EmailSettings