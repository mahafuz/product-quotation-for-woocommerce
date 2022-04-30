import React, { useState } from 'react'
  import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import ButtonSettings from './ButtonSettings';
import EmailSettings from './EmailSettings';
import FormSettings from './FormSettings';
import GeneralSettings from './GeneralSettings';
import { getSavedSettings, getNonce } from '../Helpers';

import ButtonIcon from './../images/button.png';
import FormIcon from './../images/form.png';
import EmailIcon from './../images/email.png';
import GeneralSettingsIcon from './../images/cog.svg';

const App = () => {
	const savedTab = localStorage.getItem( 'pqfw_settings_active_tab' ) || 'button';
	const [activeTab, setActiveTab ] = useState<string>( savedTab );
	const [settings, setSettings ] = useState<Object>(getSavedSettings());

	const saveActiveTab = (name) => {
		localStorage.setItem( 'pqfw_settings_active_tab', name );
	}

	const saveSettings = (e) => {
		let button = e.target;
		button.classList.add( 'updating-message' );

		wp.ajax.send('pqrf_save_settings', {
			data: {
				_wpnonce: getNonce(),
				settings: JSON.stringify(settings)
			},
			success: function(response) {
				toast.success(response.message);
			},
			error: function(error) {
				toast.error(error.message);
			},
			complete: function() {
				button.classList.remove( 'updating-message' );
			}
		});
	}

  return (
	  <>
	  	<h1 className="pqfw-app-title">Settings</h1>
		<div className={`pqfw-settings-tabs-warp${ false ? ` pqfw-pro-deactivate` : ''}`}>
			<ToastContainer
				position="top-right"
				autoClose={2000}
				hideProgressBar={true}
				newestOnTop={false}
				closeOnClick
				rtl={false}
				pauseOnFocusLoss
				pauseOnHover
				theme='colored'
			/>
			<div id="pqfw-settings-tabs">
				<a
					href="#"
					className={`pqfw-settings-nav-tab${ activeTab === 'general' ? ` pqfw-settings-nav-tab-active` : ''}`}
					onClick={() => {
						setActiveTab( 'general' )
						saveActiveTab( 'general' )
					}}
				><img src={GeneralSettingsIcon} /> General Settings</a>
				<a
					href="#"
					className={`pqfw-settings-nav-tab${ activeTab === 'button' ? ` pqfw-settings-nav-tab-active` : ''}`}
					onClick={() => {
						setActiveTab( 'button' )
						saveActiveTab( 'button' )
					}}
				><img src={ButtonIcon} /> Button</a>
				<a
					href="#"
					className={`pqfw-settings-nav-tab${ activeTab === 'form' ? ` pqfw-settings-nav-tab-active` : ''}`}
					onClick={() => {
						setActiveTab( 'form' )
						saveActiveTab( 'form' )
					}}
				><img src={FormIcon} /> Form</a>
				<a
					href="#"
					className={`pqfw-settings-nav-tab${ activeTab === 'email' ? ` pqfw-settings-nav-tab-active` : ''}`}
					onClick={() => {
						setActiveTab( 'email' )
						saveActiveTab( 'email' )
					}}
				><img src={EmailIcon} /> Email</a>
			</div>
			<div id="pqfw-settings-tabs-contents">
				{ activeTab === 'general' && (<GeneralSettings
					settings={settings}
					setSettings={setSettings }
					saveSettings={saveSettings}
				/>)}
				{ activeTab === 'button' && (<ButtonSettings
					settings={settings}
					setSettings={setSettings }
					saveSettings={saveSettings}
				/>)}
				{ activeTab === 'email' && (<EmailSettings
					settings={settings}
					setSettings={setSettings }
					saveSettings={saveSettings}
				/>)}
				{ activeTab === 'form' && (<FormSettings
					settings={settings}
					setSettings={setSettings }
					saveSettings={saveSettings}
				/>)}
			</div>
		</div>
	</>
  )
}

export default App