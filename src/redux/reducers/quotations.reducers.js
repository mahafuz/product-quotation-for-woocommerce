import { createSlice } from '@reduxjs/toolkit';
import {
	FETCH_QUOTATION,
	FETCH_ALL_QUOTATIONS,
	MOVE_TO_TRASH,
	DELETE_QUOTATION,
} from '@Redux/types/quotations.types';

const initialState = {};

function quotationsReducer(state = initialState, action) {
	const payload = action.payload;

	switch (action.type) {
		case FETCH_ALL_QUOTATIONS:
			return {
				...payload,
			};
		case FETCH_QUOTATION:
			return {
				...state,
				quotation: {
					...payload.quotation.quotation
				}
			};
		case MOVE_TO_TRASH:
			if (state.data) {
				const updatedData = state.data.map((item) => {
					if (parseInt(item.id) === parseInt(payload.id)) {
						return { ...item, ...payload };
					}
					return item;
				});

				return {
					...state,
					data: updatedData,
				};
			}
			return {
				...state,
				data: [payload],
			};
		case DELETE_QUOTATION:
			if (state.data) {
				return {
					...state,
					data: [
						...state.data.filter(
							(item) => parseInt(item.id) !== parseInt(payload.id)
						),
					],
				};
			}
			return {
				...state,
				data: [payload],
			};
		default:
			return state;
	}
}

export default quotationsReducer;
