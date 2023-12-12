import React from 'react'

import { FormToggle } from '@wordpress/components';
import { translate } from '../Helpers';

const FormSettings = ({ settings, setSettings, saveSettings }) => {
	return (
		<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
			<h3 className='pqfw-tab-title'>{translate('form-settings-label')}</h3>
			<div className="inside">
				<p className="help">{translate('form-settings-desc')}</p>

				<table className="form-table">
					<tr>
						<th>{translate('default-form-style-label')}</th>
						<td>
							<FormToggle
								checked={settings?.pqfw_form_default_design}
								onChange={(e) => setSettings({
									...settings,
									pqfw_form_default_design: e.target.checked
								})}
							/>
							<p className="description">{translate('default-form-style-desc')}</p>
						</td>
					</tr>
					{settings?.pqfw_form_default_design ? (
						<tr>
							<th>{translate('floated-form-label')}</th>
							<td>
								<FormToggle
									checked={settings?.pqfw_floating_form}
									onChange={(e) => setSettings({
										...settings,
										pqfw_floating_form: e.target.checked
									})}
								/>
								<p className="description">{translate('floated-form-desc')}</p>
							</td>
						</tr>
					) : ''}
					<tr>
						<th>{translate('add-pvp-label')}</th>
						<td>
							<FormToggle
								checked={settings?.privacy_policy}
								onChange={(e) => setSettings({
									...settings,
									privacy_policy: e.target.checked
								})}
							/>
							<p className="description">{translate('add-pvp-desc')}</p>
						</td>
					</tr>
					{settings?.privacy_policy ? (
						<>
							<tr>
								<th>{translate('pvp-label-label')}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										value={settings?.privacy_policy_label}
										onChange={(e) => setSettings({
											...settings,
											privacy_policy_label: e.target.value
										})}
									/>
									<p className="description">{translate('pvp-label-desc')}</p>
								</td>
							</tr>
							<tr>
								<th>{translate('pvp-content-label')}</th>
								<td>
									<textarea
										cols="30"
										rows="5" className="regular-text"
										onChange={(e) => setSettings({
											...settings,
											privacy_policy_content: e.target.value
										})}
									>{settings?.privacy_policy_content}</textarea>
									<p className="description">{translate('pvp-label-desc')}</p>
								</td>
							</tr>
						</>
					) : ''}
					<tr>
						<th>Success Message</th>
						<td>
							<input
								type="text"
								className="regular-text"
								value={settings?.success_message}
								onChange={(e) => setSettings({
									...settings,
									success_message: e.target.value
								})}
							/>
							<p className="description">Form submission <span style={{ color: 'green' }}>success</span> message.</p>
						</td>
					</tr>
					<tr>
						<th>Error Message</th>
						<td>
							<input
								type="text"
								className="regular-text"
								value={settings?.error_message}
								onChange={(e) => setSettings({
									...settings,
									error_message: e.target.value
								})}
							/>
							<p className="description">Form submission <span style={{ color: 'red' }}>error</span> message.</p>
						</td>
					</tr>
				</table>
			</div>
			<div className="submit-wrapper">
				<button className="button button-primary" onClick={saveSettings}>{translate('save-changes')}</button>
			</div>
		</div >
	)
}

export default FormSettings