import {
	FETCH_QUOTATION,
	FETCH_ALL_QUOTATIONS,
	MOVE_TO_TRASH,
	DELETE_QUOTATION,
	RESTORE_QUOTATION,
	UPDATE_CURRENT_PAGE
} from '@Redux/types/quotations.types';

const initialState = {
	data: false,
	totalItems: 0,
	currentPage: 1
};

function quotationsReducer(state = initialState, action) {
	const payload = action.payload;

	switch (action.type) {
		case FETCH_ALL_QUOTATIONS:
			return {
				...state.quotations,
				...payload,
			};
		case MOVE_TO_TRASH:
			if (state.data) {
				const itemId = parseInt(
					payload?.data?.quotation?.ID || payload?.data?.quotation?.id
				);
				const itemToTrash = state.data.find(
					(item) => parseInt(item.id) === itemId
				);

				if (!itemToTrash) {
					return state;
				}

				const updatedData = state.data.filter(
					(item) => parseInt(item.id) !== itemId
				);

				return {
					...state,
					data: updatedData,
				};
			}
			return {
				...state,
				data: [payload],
			};
		case RESTORE_QUOTATION:
			if (state.data) {
				const itemId = parseInt(
					payload?.data?.quotation?.ID || payload?.data?.quotation?.id
				);

				const itemToRestore = state.data.find(
					(item) => parseInt(item.id) === itemId
				);

				if (!itemToRestore) {
					return state;
				}

				const updatedData = state.data.filter(
					(item) => parseInt(item.id) !== itemId
				);

				return {
					...state,
					data: updatedData
				}
			}

			return {
				...state,
				data: [payload]
			};
		case DELETE_QUOTATION:
			if (state.data) {
				const itemId = parseInt(
					payload?.data?.quotation?.ID || payload?.data?.quotation?.id
				);
				const itemToWipe = state.data.find(
					(item) => parseInt(item.id) === itemId
				);

				if (!itemToWipe) {
					return state;
				}

				const updatedData = state.data.filter(
					(item) => parseInt(item.id) !== itemId
				);

				return {
					...state,
					data: updatedData,
				};
			}
			return {
				...state,
				data: [payload],
			};
		case UPDATE_CURRENT_PAGE:
			return {
				...state,
				data: {
					...state.data,
					currentPage: payload
				}				
			}
		default:
			return state;
	}
}

export default quotationsReducer;
