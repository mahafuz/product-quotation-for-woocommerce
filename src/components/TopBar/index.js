import { __ } from '@wordpress/i18n';
import Navigation from '@Components/Navigation';

import AngleRightIcon from '@src/images/angle-right.svg';

import { getPluginLogo } from '@Utils/config';
import './index.scss';

function index({ render }) {
	return (
		<div className="quotify-top-bar">
			<div className="quotify-backend-top-bar-left">
				<img
					className="quotify-backend-app-logo"
					src={getPluginLogo()}
					alt={__('logo', 'quotify')}
				/>
				<div className="separator">
					<img src={AngleRightIcon} alt="" />
				</div>
				{render()}
			</div>
			<div className="quotify-backend-top-bar-right">
				<Navigation />
			</div>
		</div>
	);
}

export default index;
