import { createSlice } from '@reduxjs/toolkit';
import {
	FETCH_QUOTATION,
	FETCH_ALL_QUOTATIONS,
	MOVE_TO_TRASH,
	DELETE_QUOTATION,
	RESTORE_QUOTATION,
} from '@Redux/types/quotations.types';

const initialState = {};

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

				console.log('itemId', itemId);

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
