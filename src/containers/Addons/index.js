import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { __ } from '@wordpress/i18n';

import { FETCH_ADDONS } from '@Redux/types/addons.types';

import ContactForm7 from './ContactForm7';
import TopBar from '@Components/TopBar';

import './index.scss';

import {
	makeRequest,
	admin_url,
	route_path,
	fireNotify,
} from '@Utils/helper';

const cf7Addon = {
	label: __('Contact Form 7', 'quotify'),
	name: 'cf7',
	is_pro: false,
	required_plugin: false,
	details: __('Use contact form 7 as quotation submission form.', 'quotify'),
	icon: 'https://ps.w.org/contact-form-7/assets/icon.svg',
	url: `${admin_url}admin.php?page=forms`,
	docsUrl: `https://wpindiedev.xyz/docs/contact-form-7/`,
	settings: `${route_path}?page=pqfw-product-quotations-settings`,
};

export default function index() {
	const dispatch = useDispatch();

	useEffect(() => {
		makeRequest({
			action: 'quotify/addons/get_all',
		}).then((response) => {
			if (response.data?.success) {
				dispatch({
					type: FETCH_ADDONS,
					payload: response.data?.data,
				});
			} else {
				fireNotify(__('Addon Failed to saved.', 'quotify'), 'error');
			}
		});
	}, []);

	return (
		<>
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">
							{__('Addons', 'quotify')}
						</h4>
					</div>
				)}
			/>

			<div className="quotify-content-wrap quote-container quotify-addons-wrapper">
				<ContactForm7 addon={cf7Addon} />
			</div>
		</>
	);
}
