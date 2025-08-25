import { FETCH_ADDONS } from '../types/addons.types';
import { addons as allAddons } from '@Utils/helper';

function addons(state = allAddons, action) {
	const payload = action.payload;
	switch (action.type) {
		case FETCH_ADDONS:
			return {
				...payload,
			};
		default:
			return state;
	}
}
export default addons;