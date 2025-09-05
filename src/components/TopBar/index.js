import { useSelector } from 'react-redux';
import { __ } from '@wordpress/i18n';

import Navigation from "@Components/Navigation";

import { toplevel_menu_icon_url } from '@Utils/helper';
import './index.scss';

function index({ render }) {
	return (
		<div className="quotify-top-bar">
			<div className="quotify-backend-top-bar-left">
				<img
					className="quotify-backend-app-logo"
					src={toplevel_menu_icon_url}
					alt={__('logo', 'quotify')}
				/>
				<h4>{__('Quotify', 'quotify')}</h4>
				<div className="separator"></div>
				{render()}
			</div>
			<div className="quotify-backend-top-bar-right">
				<Navigation />
			</div>
		</div>
	);
}

export default index;
