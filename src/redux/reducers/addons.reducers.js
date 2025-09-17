import {
	FETCH_ADDONS,
	SAVE_ADDONS
} from '@Redux/types/addons.types';

const initialState = {};

function addonsReducer(state = initialState, action) {
	const payload = action.payload;

	switch (action.type) {
		case FETCH_ADDONS:
			return {
				...state,
				...payload
			}
		case SAVE_ADDONS:
			return state;	
		default:
			return state;
	}
}

export default addonsReducer;
