import { __ } from '@wordpress/i18n';
import { Link } from 'react-router-dom';
import Navigation from '@Components/Navigation';
import { getRoutePath } from '@Utils/global';

import AngleRightIcon from '@src/images/angle-right.svg';

import { getPluginLogo } from '@Utils/config';
import './index.scss';

function index({ render }) {
	return (
		<div className="quotify-top-bar">
			<div className="quotify-backend-top-bar-left">
				<Link to={`${getRoutePath()}admin.php?page=quotify`}>
					<img
						className="quotify-backend-app-logo"
						src={getPluginLogo()}
						alt={__('logo', 'quotify')}
					/>
				</Link>
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
