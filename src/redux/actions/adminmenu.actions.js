import { FETCH_ADMIN_MENU } from './../types/adminmenu.types';
import { makeRequest, renderError } from '@Utils/helper';

export const fetchAdminMenuItems = () => async (dispatch) => {
	return makeRequest({
		action: 'pqfw/admin/get_admin_menu_items',
	}).then(
		(res) => {
			dispatch({
				type: FETCH_ADMIN_MENU,
				payload: JSON.parse(res.data?.data),
			});
			return res;
		},
		(e) => {
			renderError(e);
		}
	);
};
