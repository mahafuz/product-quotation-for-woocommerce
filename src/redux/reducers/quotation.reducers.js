import {
	FETCH_QUOTATION,
	STATUS_UPDATE,
} from '@Redux/types/quotations.types';

const initialState = {
	quotation: null,
	loading: false,
	error: null,
};

function quotationReducer(state = initialState, action) {
	const payload = action.payload;

	switch (action.type) {
		case FETCH_QUOTATION:
			return {
				...state,
				quotation: payload.quotation,
				loading: false,
				error: null,
			};
		case STATUS_UPDATE:
			// Update the quotation status in the current quotation
			if (state.quotation && state.quotation.ID === payload.id) {
				return {
					...state,
					quotation: {
						...state.quotation,
						status: payload.status,
					},
				};
			}
			return state;
		default:
			return state;
	}
}

export default quotationReducer;
