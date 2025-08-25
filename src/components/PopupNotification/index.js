import 'react-toastify/dist/ReactToastify.css';

import './styles.scss';

import React from 'react';
import { ToastContainer } from 'react-toastify';

export default function PopupNotification(props) {
	return (
		<React.Fragment>
			<ToastContainer {...props} />
		</React.Fragment>
	);
}
