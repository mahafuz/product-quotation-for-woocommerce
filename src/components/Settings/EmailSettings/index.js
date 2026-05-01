import { __ } from '@wordpress/i18n';
import { FormToggle } from '@wordpress/components';

const EmailSettings = ({ settings, setSettings, saveSettings }) => {
  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
			<h3 className='pqfw-tab-title'>{__( 'Email Settings' )}</h3>
			<div className="inside">
				<p className="help">{__('Configure email notifications for quotation requests sent to administrators and customers.', 'quotify')}</p>
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
							<p className="description">{__('Send notification emails to site administrators when customers submit quotation requests.', 'quotify')}</p>
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
								<p className="description">{__('Email address that will receive quotation notifications. Default: admin email.', 'quotify')}</p>
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
							<p className="description">{__('Send confirmation emails to customers when they submit quotation requests.', 'quotify')}</p>
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
									<p className="description">{__('Available placeholders: {quotation_id}, {customer_name}, {site_name}, {customer_subject}, {customer_email}, {date}, {time}', 'quotify')}</p>
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
					<tr>
						<th>{__( 'Customize Email Messages' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_custom_email_messages_enabled }
								onChange={(e) => setSettings({
									...settings,
									pqfw_custom_email_messages_enabled: e.target.checked
								})}
							/>
							<p className="description">{__( 'Enable custom email message content for specific sections of admin and customer notifications.' )}</p>
						</td>
					</tr>
					{ settings?.pqfw_custom_email_messages_enabled ? (
						<>
							<tr>
								<th colspan="2">
									<h4>{__( 'Customer Email Messages' )}</h4>
									<p className="description">{__( 'Customize the text content for customer confirmation emails.' )}</p>
								</th>
							</tr>
							<tr>
								<th>{__( 'Greeting' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										value={ settings?.pqfw_customer_email_greeting || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_customer_email_greeting: e.target.value
										})}
									/>
									<p className="description">{__( 'The greeting message shown to customers (e.g., "Thank You for Your Inquiry!")' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Introduction' )}</th>
								<td>
									<textarea
										rows="4"
										className="large-text"
										value={ settings?.pqfw_customer_email_intro || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_customer_email_intro: e.target.value
										})}
									></textarea>
									<p className="description">{__( 'The main introduction message explaining that the quotation was received.' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'What Happens Next?' )}</th>
								<td>
									<textarea
										rows="4"
										className="large-text"
										value={ settings?.pqfw_customer_email_what_next || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_customer_email_what_next: e.target.value
										})}
									></textarea>
									<p className="description">{__( 'List the next steps in the process (one per line). HTML allowed.' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Closing Message' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										value={ settings?.pqfw_customer_email_closing || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_customer_email_closing: e.target.value
										})}
									/>
									<p className="description">{__( 'The closing message (e.g., "We appreciate your business and look forward to serving you!")' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Email Signature' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										value={ settings?.pqfw_customer_email_signature || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_customer_email_signature: e.target.value
										})}
									/>
									<p className="description">{__( 'The signature shown at the bottom of customer emails.' )}</p>
								</td>
							</tr>
							<tr>
								<th colspan="2">
									<h4>{__( 'Admin Email Messages' )}</h4>
									<p className="description">{__( 'Customize the text content for admin notification emails.' )}</p>
								</th>
							</tr>
							<tr>
								<th>{__( 'Greeting' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										value={ settings?.pqfw_admin_email_greeting || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_admin_email_greeting: e.target.value
										})}
									/>
									<p className="description">{__( 'The heading for admin notification emails.' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Introduction' )}</th>
								<td>
									<textarea
										rows="4"
										className="large-text"
										value={ settings?.pqfw_admin_email_intro || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_admin_email_intro: e.target.value
										})}
									></textarea>
									<p className="description">{__( 'The introduction message for admin emails explaining a new quotation was received.' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Closing Message' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										value={ settings?.pqfw_admin_email_closing || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_admin_email_closing: e.target.value
										})}
									/>
									<p className="description">{__( 'The closing message for admin emails.' )}</p>
								</td>
							</tr>
							<tr>
								<th>{__( 'Email Signature' )}</th>
								<td>
									<input
										type="text"
										className="regular-text"
										value={ settings?.pqfw_admin_email_signature || '' }
										onChange={(e) => setSettings({
											...settings,
											pqfw_admin_email_signature: e.target.value
										})}
									/>
									<p className="description">{__( 'The signature shown at the bottom of admin emails.' )}</p>
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
