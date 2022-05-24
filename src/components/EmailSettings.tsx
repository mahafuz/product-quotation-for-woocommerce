import React from 'react'

import { FormToggle } from '@wordpress/components';
import { translate } from '../Helpers';

const EmailSettings = ({ settings, setSettings, saveSettings }) => {

  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>{translate( 'email-settings-label' )}</h3>
		<div className="inside">
			<p className="help">{translate( 'email-settings-desc' )}</p>
			<table className="form-table">
        		<tr>
					<th>{translate( 'receive-email-label' )}</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_form_send_mail }
							onChange={(e) => setSettings({
								...settings,
								pqfw_form_send_mail: e.target.checked
							})}
						/>
						<p className="description">{translate( 'receive-email-desc' )}</p>
					</td>
				</tr>
				{settings?.pqfw_form_send_mail && (
					<tr>
						<th>{translate( 'recipient-label' )}</th>
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
							<p className="description">{translate( 'recipient-desc' )}</p>
						</td>
					</tr>
				)}
				<tr>
					<th>{translate( 'send-email-label' )}</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_send_mail_to_customer }
							onChange={(e) => setSettings({
								...settings,
								pqfw_send_mail_to_customer: e.target.checked
							})}
						/>
						<p className="description">{translate( 'send-email-desc' )}</p>
					</td>
				</tr>
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>{translate( 'save-changes' )}</button>
		</div>
	</div>
  )
}

export default EmailSettings