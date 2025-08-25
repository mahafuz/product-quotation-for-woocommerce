import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { createPortal } from '@wordpress/element';
import { BrowserRouter as Router } from 'react-router-dom';
import BackendDashboard from './components/BackendDashboard';
import AdminMenu from './components/AdminMenu';

import { ChakraProvider } from '@chakra-ui/react'

import store from './redux/store';

import './scss/backend.scss';

document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('pqfw-backend-dashboard');

	if (container) {
		const root = createRoot(container);
		const menuPage = document.getElementById('toplevel_page_pqfw-product-quotations');

		function MenuPortal({ children }) {
			menuPage.innerHTML = '';
			return createPortal(children, menuPage);
		}

		root.render(
			<Provider store={store}>
				<ChakraProvider>
					<Router>
						<MenuPortal>
							<AdminMenu />
						</MenuPortal>
						<BackendDashboard />
					</Router>
				</ChakraProvider>
			</Provider>
		);
	}
});
