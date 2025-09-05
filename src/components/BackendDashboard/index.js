import Quotations from '@Containers/Quotations';
import ViewQuotation from '@Containers/ViewQuotation';
import Addons from '@Containers/Addons';
import Help from '@Containers/Help';

import Settings from '@Components/Settings';
import PopupNotification from '@Components/PopupNotification';

import { useQuery } from '@Utils/helper';

const renderSwitch = (page, id, action, path) => {
	switch (page) {
		case 'pqfw-product-quotations':
			if (id && 'view' === action) {
				return <ViewQuotation id={id} />;
			}
			return <Quotations />;
		case 'pqfw-product-quotations-addons':
			return <Addons />;
		case 'pqfw-product-quotations-tools':
			return <h1>Tools</h1>;
		case 'pqfw-product-quotations-settings':
			return <Settings />;
		case 'pqfw-product-quotations-help':
			return <Help />;
		default:
	}
};

export default function BackendDashboard() {
	const query = useQuery();

	return (
		<>
			<PopupNotification icon={false} hideProgressBar={true} />
			{/* <Container fluid maxW={`95%`}> */}
			{renderSwitch(
				query.get('page'),
				parseInt(query.get('id')),
				query.get('action'),
				query.get('path')
			)}
			{/* </Container> */}
		</>
	);
}
