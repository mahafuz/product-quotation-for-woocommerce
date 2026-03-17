import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { createPortal } from '@wordpress/element';
import { BrowserRouter as Router } from 'react-router-dom';

import BackendDashboard from '@Components/BackendDashboard';
import AdminMenu from '@Components/AdminMenu';

import store from '@Redux/store';

import '@Scss/backend.scss';

document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('quotify-backend-dashboard');

	if (container) {
		const root = createRoot(container);
		const menuPage = document.getElementById('toplevel_page_quotify');

		function MenuPortal({ children }) {
			menuPage.innerHTML = '';
			return createPortal(children, menuPage);
		}

		root.render(
			<Provider store={store}>
				<Router>
					<MenuPortal>
						<AdminMenu />
					</MenuPortal>
					<BackendDashboard />
				</Router>
			</Provider>
		);
	}
});
