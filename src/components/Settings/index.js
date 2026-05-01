import { useState, useEffect } from 'react';
import { ToastContainer, toast } from 'react-toastify';
import { __ } from '@wordpress/i18n';
import 'react-toastify/dist/ReactToastify.css';
import { getSavedSettings } from '@Utils/config';
import { makeRequest } from '@Utils/global';

import { FETCH_SETTINGS } from '@Redux/types/settings.types';

import { useDispatch } from 'react-redux';

import TopBar from '@Components/TopBar';
import GeneralSettings from './GeneralSettings';
import ButtonSettings from './ButtonSettings';
import FormSettings from './FormSettings';
import EmailSettings from './EmailSettings';
import CartSettings from './CartSettings';

import ButtonIcon from '@src/images/button.png';
import FormIcon from '@src/images/form.png';
import EmailIcon from '@src/images/email.png';
import GeneralSettingsIcon from '@src/images/cog.svg';
import CartIcon from '@src/images/customization.svg';

import '@src/scss/settings.scss';
import './index.scss';
import { fireNotify } from '@Utils/spa';

const App = () => {
	const dispatch = useDispatch();
	const savedTab =
		localStorage.getItem('pqfw_settings_active_tab') || 'general';
	const [activeTab, setActiveTab] = useState(savedTab);
	const [settings, setSettings] = useState(getSavedSettings());

	const saveActiveTab = (name) => {
		localStorage.setItem('pqfw_settings_active_tab', name);
	};

	useEffect(() => {
		makeRequest({
			action: 'quotify/ajax/settings/get_all',
		}).then((response) => {
			if (response.data?.success) {
				dispatch({
					type: FETCH_SETTINGS,
					payload: response.data?.data,
				});
			} else {
				fireNotify(response?.data?.data?.message, 'error');
			}
		});
	}, []);

	const saveSettings = (e) => {
		let button = e.target;
		button.classList.add('updating-message');

		makeRequest({
			action: 'quotify/ajax/settings/save',
			settings: JSON.stringify(settings),
		})
		.then((response) => {
			if (response.data?.success) {
				dispatch({
					type: FETCH_SETTINGS,
					payload: response.data?.data,
				});

				fireNotify(response?.data?.data?.message, 'success');
			} else {
				fireNotify(response?.data?.data?.message, 'error');
			}
		})
		.then(() => {
			button.classList.remove('updating-message');
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
							{__('General')}
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
							className={`pqfw-settings-nav-tab${activeTab === 'cart' ? ` pqfw-settings-nav-tab-active` : ''}`}
							onClick={() => {
								setActiveTab('cart');
								saveActiveTab('cart');
							}}
						>
							<img src={CartIcon} /> {__('Cart')}
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
						{activeTab === 'cart' && (
							<CartSettings
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
