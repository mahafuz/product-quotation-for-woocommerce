import { FETCH_SETTINGS, SAVE_SETTINGS } from '@Redux/types/settings.types';

const initialState = {};

function settingsReducer(state = initialState, action) {
	const payload = action.payload;

	switch (action.type) {
		case FETCH_SETTINGS:
			return {
				...state,
				...payload,
			};
		case SAVE_SETTINGS:
			return state;
		default:
			return state;
	}
}

export default settingsReducer;
