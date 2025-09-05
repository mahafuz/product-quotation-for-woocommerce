import { useState } from 'react';
import { ToastContainer, toast } from 'react-toastify';
import { __ } from '@wordpress/i18n';
import 'react-toastify/dist/ReactToastify.css';
import { getSavedSettings, getNonce } from '@Utils/helper';

import TopBar from '@Components/TopBar';
import GeneralSettings from './GeneralSettings';
import ButtonSettings from './ButtonSettings';
import FormSettings from './FormSettings';
import EmailSettings from './EmailSettings';

import ButtonIcon from './../../images/button.png';
import FormIcon from './../../images/form.png';
import EmailIcon from './../../images/email.png';
import GeneralSettingsIcon from './../../images/cog.svg';

import '@src/scss/settings.scss';

const App = () => {
	const savedTab =
		localStorage.getItem('pqfw_settings_active_tab') || 'general';
	const [activeTab, setActiveTab] = useState(savedTab);
	const [settings, setSettings] = useState(getSavedSettings());

	const saveActiveTab = (name) => {
		localStorage.setItem('pqfw_settings_active_tab', name);
	};

	const saveSettings = (e) => {
		let button = e.target;
		button.classList.add('updating-message');

		wp.ajax.send('pqrf_save_settings', {
			data: {
				_wpnonce: getNonce(),
				settings: JSON.stringify(settings),
			},
			success: function (response) {
				toast.success(response.message);
			},
			error: function (error) {
				toast.error(error.message);
			},
			complete: function () {
				button.classList.remove('updating-message');
			},
		});
	};

	return (
		<>
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">
							{__('Settings', 'quotify')}
						</h4>
					</div>
				)}
			/>

			<div className="quotify-content-wrap quote-container">
				<div
					className={`pqfw-settings-tabs-warp${false ? ` pqfw-pro-deactivate` : ''}`}
				>
					<ToastContainer
						position="top-right"
						autoClose={2000}
						hideProgressBar={true}
						newestOnTop={false}
						closeOnClick
						rtl={false}
						pauseOnFocusLoss
						pauseOnHover
						theme="colored"
					/>
					<div id="pqfw-settings-tabs">
						<a
							href="#"
							className={`pqfw-settings-nav-tab${activeTab === 'general' ? ` pqfw-settings-nav-tab-active` : ''}`}
							onClick={() => {
								setActiveTab('general');
								saveActiveTab('general');
							}}
						>
							<img src={GeneralSettingsIcon} />{' '}
							{__('General Settings')}
						</a>
						<a
							href="#"
							className={`pqfw-settings-nav-tab${activeTab === 'button' ? ` pqfw-settings-nav-tab-active` : ''}`}
							onClick={() => {
								setActiveTab('button');
								saveActiveTab('button');
							}}
						>
							<img src={ButtonIcon} /> {__('Button')}
						</a>
						<a
							href="#"
							className={`pqfw-settings-nav-tab${activeTab === 'form' ? ` pqfw-settings-nav-tab-active` : ''}`}
							onClick={() => {
								setActiveTab('form');
								saveActiveTab('form');
							}}
						>
							<img src={FormIcon} /> {__('Form')}
						</a>
						<a
							href="#"
							className={`pqfw-settings-nav-tab${activeTab === 'email' ? ` pqfw-settings-nav-tab-active` : ''}`}
							onClick={() => {
								setActiveTab('email');
								saveActiveTab('email');
							}}
						>
							<img src={EmailIcon} /> {__('Email')}
						</a>
					</div>
					<div id="pqfw-settings-tabs-contents">
						{activeTab === 'general' && (
							<GeneralSettings
								settings={settings}
								setSettings={setSettings}
								saveSettings={saveSettings}
							/>
						)}
						{activeTab === 'button' && (
							<ButtonSettings
								settings={settings}
								setSettings={setSettings}
								saveSettings={saveSettings}
							/>
						)}
						{activeTab === 'form' && (
							<FormSettings
								settings={settings}
								setSettings={setSettings}
								saveSettings={saveSettings}
							/>
						)}
						{activeTab === 'email' && (
							<EmailSettings
								settings={settings}
								setSettings={setSettings}
								saveSettings={saveSettings}
							/>
						)}
					</div>
				</div>
			</div>
		</>
	);
};

export default App;
