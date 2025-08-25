import { __ } from '@wordpress/i18n';
import { FormToggle } from '@wordpress/components';

const FormSettings = ({ settings, setSettings, saveSettings }) => {
  return (
	<div id="pqfw-settings-button" className='pqfw-settings-tab-content pqfw-settings-tab-content-active'>
		<h3 className='pqfw-tab-title'>{__( 'form-settings-label' )}</h3>
		<div className="inside">
			<p className="help">{__( 'form-settings-desc' )}</p>

			<table className="form-table">
				<tr>
					<th>{__( 'default-form-style-label' )}</th>
					<td>
						<FormToggle
							checked={ settings?.pqfw_form_default_design }
							onChange={(e) => setSettings({
								...settings,
								pqfw_form_default_design: e.target.checked
							})}
						/>
						<p className="description">{__( 'default-form-style-desc' )}</p>
					</td>
				</tr>
				{ settings?.pqfw_form_default_design ? (
					<tr>
						<th>{__( 'floated-form-label' )}</th>
						<td>
							<FormToggle
								checked={ settings?.pqfw_floating_form }
								onChange={(e) => setSettings({
									...settings,
									pqfw_floating_form: e.target.checked
								})}
							/>
							<p className="description">{__( 'floated-form-desc' )}</p>
						</td>
					</tr>
				) : ''}
				<tr>
					<th>{__( 'add-pvp-label' )}</th>
					<td>
						<FormToggle
							checked={ settings?.privacy_policy }
							onChange={(e) => setSettings({
								...settings,
								privacy_policy: e.target.checked
							})}
						/>
						<p className="description">{__( 'add-pvp-desc' )}</p>
					</td>
				</tr>
				{ settings?.privacy_policy ? (
					<>
						<tr>
							<th>{__( 'pvp-label-label' )}</th>
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
								<p className="description">{__( 'pvp-label-desc' )}</p>
							</td>
						</tr>
						<tr>
							<th>{__( 'pvp-content-label' )}</th>
							<td>
								<textarea
									cols="30"
									rows="5" className="regular-text"
									onChange={(e) => setSettings({
										...settings,
										privacy_policy_content: e.target.value
									})}
								>{ settings?.privacy_policy_content }</textarea>
								<p className="description">{__( 'pvp-label-desc' )}</p>
							</td>
						</tr>
					</>
				) : ''}
			</table>
		</div>
		<div className="submit-wrapper">
			<button className="button button-primary" onClick={saveSettings}>{__( 'save-changes' )}</button>
		</div>
	</div>
  )
}

export default FormSettings