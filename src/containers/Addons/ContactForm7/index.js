import { useState, useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate, Link } from 'react-router-dom';
import { __ } from '@wordpress/i18n';

import { FETCH_ADDONS } from '@Redux/types/addons.types';
import {
	Button,
	FormToggle,
	SelectControl,
	Spinner,
} from '@wordpress/components';
import { BsFillGearFill } from 'react-icons/bs';

import WPModal from '@Components/Modal/WPModal';

import { makeRequest } from '@Utils/global';

import {
	fireNotify,
} from '@Utils/spa';

function index({ addon }) {
	const navigate = useNavigate();
	const dispatch = useDispatch();
	const saved = useSelector((state) => state?.addons?.cf7);
	const [openModal, setOpenModal] = useState(false);
	const [saving, setSaving] = useState(false);
	const [status, setStatus] = useState(saved); // Initialize with Redux value
	const [settings, setSettings] = useState({});
	const [forms, setForms] = useState([]);

	// Sync local status with Redux store changes
	useEffect(() => {
		setStatus(saved);
	}, [saved]); // Add saved as dependency

	useEffect(() => {
		if (openModal && !forms?.length) {
			makeRequest({
				action: 'quotify/ajax/addons/contact_form_7/get_all_forms',
			}).then((response) => {
				setForms([...response?.data]);
			});
		}

		if (openModal) {
			makeRequest({
				action: 'quotify/ajax/addons/contact_form_7/get_settings',
			}).then((response) => {
				setSettings(response?.data?.data?.settings)
			});
		}
	}, [openModal]);

	const handleAddonSaveSettings = () => {
		setSaving(true);

		makeRequest({
			action: 'quotify/ajax/addons/contact_form_7/save_settings',
			settings
		}).then(( response ) => {
			const fetchedSettings = response?.data?.data?.settings;

			setSettings({
				...settings,
				...fetchedSettings
			});

			setSaving(false);

			fireNotify(
				response?.data?.data?.message,
				'success'
			);
		});
	};

	const moreSettings = () => {
		localStorage.setItem('pqfw_settings_active_tab', 'form');
		navigate(addon.settings);
	};

	const handleChange = (e, addon) => {
		const value = e.target.checked;

		// Optimistically update UI immediately
		setStatus(value);

		makeRequest({
			action: 'quotify/ajax/addons/save',
			addon: addon.name,
			status: value,
		}).then((response) => {
			if (response.data?.success) {
				dispatch({
					type: FETCH_ADDONS,
					payload: response.data?.data,
				});

				// No need to setStatus here as useEffect will sync from Redux
				fireNotify(
					sprintf(
						// translators: %s: AddonName
						__('%s Addon saved successfully.', 'quotify'),
						addon.label
					),
					'success'
				);
			} else {
				// Revert on error
				setStatus(!value);
				fireNotify(
					sprintf(
						// translators: %s: AddonName
						__('%s Addon Failed to saved.', 'quotify'),
						addon.label
					),
					'error'
				);
			}
		}).catch((error) => {
			// Revert on network error
			setStatus(!value);
			fireNotify(
				__('Network error occurred. Please try again.', 'quotify'),
				'error'
			);
		});
	};

	const footer = () => {
		return (
			<>
				<div className="quotify-addon-more-settings">
					{__('Go to the detailed:', 'quotify')}
					<Button onClick={moreSettings}>
						{__('settings', 'quotify')}
					</Button>
				</div>
				<div className="action">
					<Button
						className="quotify-button"
						onClick={() => handleAddonSaveSettings()}
					>
						{saving && <Spinner />}
						{__('Save', 'quotify')}
					</Button>
				</div>
			</>
		);
	};

	return (
		<div
			key={addon.id}
			className={`quotify-card quotify-single-addon${addon?.upcoming ? ` disable` : ''}`}
		>
			<WPModal
				isOpen={openModal}
				title={__('Contact Form 7 Settings', 'quotify')}
				size="fill"
				suffix="cf7-settings"
				onRequestClose={() => {
					setOpenModal(false);
				}}
				contentLabel={__('Contact Form 7 - Settings', 'quotify')}
				shouldCloseOnClickOutside={true}
				footer={footer}
			>
				<table className="quotify-addon-settings-table">
					<tr>
						<td>{__('Select Quotation Form', 'quotify')}</td>
						<td>
							<SelectControl
								label={__('Form', 'quotify')}
								onChange={(form_id) =>
									setSettings({
										...settings,
										form_id,
									})
								}
								value={settings?.form_id}
								hideLabelFromVision={true}
								options={forms}
							/>

							<p className="description">
								{__(
									'Select a contact form 7 form as a quotation form',
									'quotify'
								)}
							</p>
						</td>
					</tr>
				</table>
			</WPModal>
			<div className="quotify-card-body">
				{addon?.upcoming && (
					<span className="up-coming">Coming Soon</span>
				)}
				<img
					className="quote-card-thumbnail"
					style={{ maxWidth: '100px' }}
					src={addon.icon}
					alt={addon.name}
				/>
				<h4 className="quote-card-title">{addon.label}</h4>
			</div>

			<div className="quotify-card-footer">
				{!addon?.upcoming && (
					<>
						<FormToggle
							checked={status}
							onChange={(e) => handleChange(e, addon)}
						/>

						<Button
							className="quotify-more-settings"
							onClick={() => setOpenModal(true)}
							aria-label={`Settings for ${addon.name}`}
							icon={<BsFillGearFill />}
						/>
					</>
				)}
			</div>
		</div>
	);
}

export default index;