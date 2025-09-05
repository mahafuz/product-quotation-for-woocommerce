import { useState } from 'react';
import { useDispatch } from 'react-redux';
import { __ } from '@wordpress/i18n';

import { FETCH_ADDONS } from '@Redux/types/addons.types';
import { Button, FormToggle } from '@wordpress/components';
import { BsFillGearFill } from 'react-icons/bs';

import {
	fireNotify,
	getAddonActiveStatus,
	addons as allAddons,
	makeRequest,
} from '@Utils/helper';

function index({ addon }) {
	const dispatch = useDispatch();
	const [status, setStatus] = useState(getAddonActiveStatus(addon.name));

	const handleChange = (e, addon) => {
		const value = e.target.checked;

		setStatus(value);

		makeRequest({
			action: 'quotify/addons/save',
			addon: addon.name,
			status: value,
		}).then((response) => {
			if (response.data?.success) {
				dispatch({
					type: FETCH_ADDONS,
					payload: response.data?.data,
				});
			} else {
				fireNotify(
					sprintf(
						// translators: %s: AddonName
						__('%s Addon Failed to saved.', 'pqfw'),
						addon.label
					),
					'error'
				);
			}
		});
	};

	return (
		<div key={addon.id} className={`quotify-card quotify-single-addon${addon.upcoming ? ` disable` : ''}`}>
			<div className="quotify-card-body">
				{addon.upcoming && (<span className='up-coming'>Coming Soon</span>)}
				<img
					className="quote-card-thumbnail"
					style={{ maxWidth: '100px' }}
					src={addon.icon}
					alt={addon.name}
				/>
				<h4 className="quote-card-title">{addon.label}</h4>
			</div>

			<div className="quotify-card-footer">
				<FormToggle
					checked={status}
					onChange={(e) => handleChange(e, addon)}
				/>

				<Button
					aria-label={`Settings for ${addon.name}`}
					icon={<BsFillGearFill />}
				/>
			</div>
		</div>
	);
}

export default index;
