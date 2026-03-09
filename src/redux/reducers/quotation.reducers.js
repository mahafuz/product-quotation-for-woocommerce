import {
	FETCH_QUOTATION,
	STATUS_UPDATE,
	MOVE_TO_TRASH,
	RESTORE_QUOTATION,
	DELETE_QUOTATION,
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
		case MOVE_TO_TRASH:
			// Update the quotation status to trash
			if (state.quotation && state.quotation.ID === payload.id) {
				return {
					...state,
					quotation: {
						...state.quotation,
						status: 'trash',
					},
				};
			}
			return state;
		case RESTORE_QUOTATION:
			// Update the quotation status when restored
			if (state.quotation && state.quotation.ID === payload.id) {
				return {
					...state,
					quotation: {
						...state.quotation,
						status: 'pending',
					},
				};
			}
			return state;
		case DELETE_QUOTATION:
			// Clear quotation if it was deleted
			if (state.quotation && state.quotation.ID === payload.id) {
				return {
					...state,
					quotation: null,
				};
			}
			return state;
		default:
			return state;
	}
}

export default quotationReducer;
