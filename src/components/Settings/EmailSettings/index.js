import { __ } from '@wordpress/i18n';
import { FormToggle } from '@wordpress/components';

const EmailSettings = ({ settings, setSettings, saveSettings }) => {
  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
			<h3 className='pqfw-tab-title'>{__( 'Email Settings' )}</h3>
			<div className="inside">
				<p className="help">{__( 'Customize email settings for better experience that will ensure the ease of use as you like.' )}</p>
				<table className="form-table">
	        		<tr>
						<th>{__( 'Receive Email' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_form_send_mail }
								onChange={(e) => setSettings({
									...settings,
									pqfw_form_send_mail: e.target.checked
								})}
							/>
							<p className="description">{__( 'Receive email for each user submitted quotation from the Quotations Cart page.' )}</p>
						</td>
					</tr>
					{settings?.pqfw_form_send_mail && (
						<tr>
							<th>{__( 'Recipient' )}</th>
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
								<p className="description">{__( 'Add recipient email ID that will receive each quotation on the email.' )}</p>
							</td>
						</tr>
					)}
					<tr>
						<th>{__( 'Send Email' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_send_mail_to_customer }
								onChange={(e) => setSettings({
									...settings,
									pqfw_send_mail_to_customer: e.target.checked
								})}
							/>
							<p className="description">{__( 'Send a copy of the email to the customer as well for each submitted quotation from the Quotations Cart page.' )}</p>
						</td>
					</tr>
					<tr>
						<th>{__( 'Customize Email Subjects' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_custom_email_subject_enabled }
								onChange={(e) => setSettings({
									...settings,
									pqfw_custom_email_subject_enabled: e.target.checked
								})}
							/>
							<p className="description">{__( 'Enable custom email subject templates with dynamic placeholders for admin and customer notifications.' )}</p>
						</td>
					</tr>
					{ settings?.pqfw_custom_email_subject_enabled ? (
						<>
							<tr>
								<th colspan="2">
									<h4>{__( 'Email Subject Configuration' )}</h4>
									<p className="description">{__( 'Customize email subjects using placeholders: {quotation_id}, {customer_name}, {site_name}, {customer_subject}, {customer_email}, {date}, {time}' )}</p>
								</th>
							</tr>
							<tr>
								<th>{__( 'Admin Email Subject' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										placeholder={__( 'New Quotation Request from {customer_name} - {quotation_id}' )}
										value={ settings?.pqfw_admin_email_subject || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_admin_email_subject: e.target.value
										})}
									/>
									<p className="description">{__( 'Subject for admin notification emails. Leave empty to use default: "New Quotation Request from {customer_name} - {quotation_id}"' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Customer Email Subject' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										placeholder={__( 'Your Quotation Request Received - {quotation_id}' )}
										value={ settings?.pqfw_customer_email_subject || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_customer_email_subject: e.target.value
										})}
									/>
									<p className="description">{__( 'Subject for customer confirmation emails. Leave empty to use default: "Your Quotation Request Received - {quotation_id}"' )}</p>
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

export default EmailSettings
