import Quotations from '@Containers/Quotations';
import ViewQuotation from '@Containers/ViewQuotation';
import Addons from '@Containers/Addons';
import Help from '@Containers/Help';

import Settings from '@Components/Settings';
import PopupNotification from '@Components/PopupNotification';

import { useQuery } from '@Utils/spa';

import { getWCNotice } from '@Utils/config';

const renderSwitch = (page, id, action, path) => {
	switch (page) {
		case 'quotify':
			if (id && 'view' === action) {
				return <ViewQuotation id={id} />;
			}
			return <Quotations />;
		case 'quotify-addons':
			return <Addons />;
		case 'quotify-tools':
			return <h1>Tools</h1>;
		case 'quotify-settings':
			return <Settings />;
		case 'quotify-help':
			return <Help />;
		default:
	}
};

export default function BackendDashboard() {
	const query = useQuery();

	return (
		<>
			<div
				dangerouslySetInnerHTML={{
					__html: getWCNotice(),
				}}
				className="quotify-notice"
			></div>
			<PopupNotification icon={false} hideProgressBar={true} />
			{renderSwitch(
				query.get('page'),
				parseInt(query.get('id')),
				query.get('action'),
				query.get('path')
			)}
		</>
	);
}
