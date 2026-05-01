import { __ } from '@wordpress/i18n';
import { FormToggle } from '@wordpress/components';

const FormSettings = ({ settings, setSettings, saveSettings }) => {
  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
			<h3 className='pqfw-tab-title'>{__( 'Form Settings' )}</h3>
			<div className="inside">
				<p className="help">{__('Customize quotation form behavior, style, and fields to match your store requirements.', 'quotify')}</p>

				<table className="form-table">
					<tr>
						<th>{__( 'Default Form Style' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_form_default_design }
								onChange={(e) => setSettings({
									...settings,
									pqfw_form_default_design: e.target.checked
								})}
							/>
							<p className="description">{__('Use the plugin\'s default form styling for a consistent, professional appearance.', 'quotify')}</p>
						</td>
					</tr>
					{ settings?.pqfw_form_default_design ? (
						<tr>
							<th>{__( 'Floating Form Style' )}</th>
							<td>
								<FormToggle
									checked={ settings?.pqfw_floating_form }
									onChange={(e) => setSettings({
										...settings,
										pqfw_floating_form: e.target.checked
									})}
								/>
								<p className="description">{__('Apply a modern, compact floating form design. Disable to use custom CSS or theme styling.', 'quotify')}</p>
							</td>
						</tr>
					) : ''}
					<tr>
						<th>{__( 'Add Privacy Policy' )}</th>
						<td>
							<FormToggle
								checked={ settings?.privacy_policy }
								onChange={(e) => setSettings({
									...settings,
									privacy_policy: e.target.checked
								})}
							/>
							<p className="description">{__('Require customers to accept your terms before submitting quotation requests.', 'quotify')}</p>
						</td>
					</tr>
					{ settings?.privacy_policy ? (
						<>
							<tr>
								<th>{__( 'Privacy Policy Label' )}</th>
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
									<p className="description">{__('Custom checkbox label. Supports shortcodes: [terms] and [privacy_policy]', 'quotify')}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Privacy Policy' )}</th>
								<td>
									<textarea
										cols="30"
										rows="5" className="regular-text"
										onChange={(e) => setSettings({
											...settings,
											privacy_policy_content: e.target.value
										})}
									>{ settings?.privacy_policy_content }</textarea>
									<p className="description">{__('Custom checkbox label. Supports shortcodes: [terms] and [privacy_policy]', 'quotify')}</p>
								</td>
							</tr>
						</>
					) : ''}
					<tr>
						<th>{__( 'Enable Rate Limiting' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_rate_limit_enabled }
								onChange={(e) => setSettings({
									...settings,
									pqfw_rate_limit_enabled: e.target.checked
								})}
							/>
							<p className="description">
								{__(
									'Limit how many times the same visitor can submit the quotation form within a time window.',
								)}
							</p>
						</td>
					</tr>
					{ settings?.pqfw_rate_limit_enabled ? (
						<>
							<tr>
								<th>{__( 'Max Submissions' )}</th>
								<td>
									<input
										type="number"
										className="small-text"
										min="1"
										value={ settings?.pqfw_rate_limit_count }
										onChange={(e) => setSettings({
											...settings,
											pqfw_rate_limit_count: parseInt(e.target.value, 10) || 0,
										})}
									/>
									<p className="description">
										{__('Maximum number of form submissions allowed per visitor within the time window.', 'quotify')}
									</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Time Window (minutes)' )}</th>
								<td>
									<input
										type="number"
										className="small-text"
										min="1"
										value={ settings?.pqfw_rate_limit_period }
										onChange={(e) => setSettings({
											...settings,
											pqfw_rate_limit_period: parseInt(e.target.value, 10) || 0,
										})}
									/>
									<p className="description">
										{__(
											'Window size during which submissions are counted (in minutes).',
										)}
									</p>
								</td>
							</tr>
						</>
					) : ''}
					<tr>
						<th>{__( 'Customize Form Fields' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_form_fields_customization_enabled }
								onChange={(e) => setSettings({
									...settings,
									pqfw_form_fields_customization_enabled: e.target.checked
								})}
							/>
							<p className="description">{__( 'Enable customization of form field labels, required status, and visibility.' )}</p>
						</td>
					</tr>
					{ settings?.pqfw_form_fields_customization_enabled ? (
						<>
							<tr>
								<th colspan="2">
									<h4>{__( 'Form Field Configuration' )}</h4>
									<p className="description">{__( 'Customize the labels, required status, and visibility for each form field.' )}</p>
								</th>
							</tr>
							<tr>
								<th>{__( 'Full Name Field' )}</th>
								<td>
									<div style={{display: 'flex', alignItems: 'center', gap: '20px'}}>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Enabled:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_name_enabled }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_name_enabled: e.target.checked
												})}
											/>
										</div>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Required:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_name_required }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_name_required: e.target.checked
												})}
											/>
										</div>
										<input
											type="text"
											className="regular-text"
											placeholder={__( 'Full Name' )}
											value={ settings?.pqfw_field_name_label || '' }
											onChange={(e) => setSettings({
												...settings,
												pqfw_field_name_label: e.target.value
											})}
											style={{marginLeft: '10px'}}
										/>
									</div>
								</td>
							</tr>
							<tr>
								<th>{__( 'Email Field' )}</th>
								<td>
									<div style={{display: 'flex', alignItems: 'center', gap: '20px'}}>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Enabled:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_email_enabled }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_email_enabled: e.target.checked
												})}
											/>
										</div>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Required:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_email_required }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_email_required: e.target.checked
												})}
											/>
										</div>
										<input
											type="text"
											className="regular-text"
											placeholder={__( 'Email' )}
											value={ settings?.pqfw_field_email_label || '' }
											onChange={(e) => setSettings({
												...settings,
												pqfw_field_email_label: e.target.value
											})}
											style={{marginLeft: '10px'}}
										/>
									</div>
								</td>
							</tr>
							<tr>
								<th>{__( 'Subject Field' )}</th>
								<td>
									<div style={{display: 'flex', alignItems: 'center', gap: '20px'}}>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Enabled:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_subject_enabled }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_subject_enabled: e.target.checked
												})}
											/>
										</div>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Required:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_subject_required }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_subject_required: e.target.checked
												})}
											/>
										</div>
										<input
											type="text"
											className="regular-text"
											placeholder={__( 'Subject' )}
											value={ settings?.pqfw_field_subject_label || '' }
											onChange={(e) => setSettings({
												...settings,
												pqfw_field_subject_label: e.target.value
											})}
											style={{marginLeft: '10px'}}
										/>
									</div>
									<p className="description">{__( 'Note: This field is automatically hidden when custom email subjects are enabled.' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Phone Field' )}</th>
								<td>
									<div style={{display: 'flex', alignItems: 'center', gap: '20px'}}>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Enabled:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_phone_enabled }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_phone_enabled: e.target.checked
												})}
											/>
										</div>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Required:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_phone_required }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_phone_required: e.target.checked
												})}
											/>
										</div>
										<input
											type="text"
											className="regular-text"
											placeholder={__( 'Phone' )}
											value={ settings?.pqfw_field_phone_label || '' }
											onChange={(e) => setSettings({
												...settings,
												pqfw_field_phone_label: e.target.value
											})}
											style={{marginLeft: '10px'}}
										/>
									</div>
								</td>
							</tr>
							<tr>
								<th>{__( 'Comments Field' )}</th>
								<td>
									<div style={{display: 'flex', alignItems: 'center', gap: '20px'}}>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Enabled:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_comments_enabled }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_comments_enabled: e.target.checked
												})}
											/>
										</div>
										<div>
											<label style={{marginRight: '8px'}}>{__( 'Required:' )}</label>
											<FormToggle
												checked={ settings?.pqfw_field_comments_required }
												onChange={(e) => setSettings({
													...settings,
													pqfw_field_comments_required: e.target.checked
												})}
											/>
										</div>
										<input
											type="text"
											className="regular-text"
											placeholder={__( 'Comments' )}
											value={ settings?.pqfw_field_comments_label || '' }
											onChange={(e) => setSettings({
												...settings,
												pqfw_field_comments_label: e.target.value
											})}
											style={{marginLeft: '10px'}}
										/>
									</div>
								</td>
							</tr>
						</>
					) : ''}
				</table>
			</div>
			<div className="submit-wrapper">
				<button className="button button-primary" onClick={saveSettings}>{__( 'Save Changes' )}</button>
			</div>
		</div>
	  )
}

export default FormSettings
