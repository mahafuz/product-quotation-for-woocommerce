import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Grid } from '@chakra-ui/react';

import Addon from './Addon';

import {
	makeRequest,
	getAllAddons,
	addons as allAddons,
	admin_url,
	getAddonInfo,
} from '@Utils/helper';

const addonsInfo = [
	{
		label: __( 'Contact Form 7', 'pqfw' ),
		name: 'contact-form-7',
		is_pro: false,
		required_plugin: true,
		details: __(
			'Use contact form 7 as quotation submission form.',
			'pqfw'
		),
		icon: 'https://ps.w.org/contact-form-7/assets/icon.svg',
		url: `${ admin_url }admin.php?page=forms`,
		docsUrl: `https://wpindiedev.xyz/docs/contact-form-7/`,
	},
	{
		label: __( 'WPForms', 'pqfw' ),
		name: 'wpforms',
		is_pro: false,
		required_plugin: false,
		details: __( 'Use WPForms as quotation submission form.', 'pqfw' ),
		icon: 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		url: `${ admin_url }admin.php?page=forms`,
		docsUrl: `https://wpindiedev.xyz/docs/wpforms/`,
	},
];

export default function index() {
	return (
		<Grid templateColumns="repeat(5, 1fr)" gap={ 6 } p={ 4 }>
			{ addonsInfo &&
				addonsInfo?.map( ( addon, index ) => (
					<Addon addon={ addon } key={ index } />
				) ) }
		</Grid>
	);
}
