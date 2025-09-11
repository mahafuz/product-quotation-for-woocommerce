import { __ } from '@wordpress/i18n';

import Addon from './Addon';
import TopBar from '@Components/TopBar';

import './index.scss';

import {
	makeRequest,
	getAllAddons,
	addons as allAddons,
	admin_url,
	getAddonInfo,
} from '@Utils/helper';

const addonsInfo = [
	{
		label: __('Contact Form 7', 'quotify'),
		name: 'contact-form-7',
		is_pro: false,
		required_plugin: false,
		upcoming: true,
		details: __('Use contact form 7 as quotation submission form.', 'quotify'),
		icon: 'https://ps.w.org/contact-form-7/assets/icon.svg',
		url: `${admin_url}admin.php?page=forms`,
		docsUrl: `https://wpindiedev.xyz/docs/contact-form-7/`,
	},
];

export default function index() {
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
				{addonsInfo &&
					addonsInfo?.map((addon, index) => (
						<Addon addon={addon} key={index} />
					))}
			</div>
		</>
	);
}
