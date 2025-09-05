import {
	API,
	current_user_can,
	current_user_id,
	fireNotify,
	is_admin,
	renderError,
	makeRequest,
} from '@Utils/helper';

import {
	DELETE_QUOTATION,
	FETCH_ALL_QUOTATIONS,
	FETCH_QUOTATION,
	UPDATE_CURRENT_PAGE,
	MOVE_TO_TRASH,
	RESTORE_QUOTATION,
} from '@Redux/types/quotations.types';

import { __ } from '@wordpress/i18n';
import { ajaxurl } from '@Utils/helper';

export const fetchAllQuotations =
	(status = 'publish', page = 1, per_page = 10, search = '') =>
	async (dispatch) => {
		let params = {
			action: 'quotify/ajax/load',
			status: status === 'all' ? 'any' : status,
			page,
			per_page,
			context: 'edit',
		};
		if (!is_admin || current_user_can.manage_options === false) {
			params = {
				...params,
				author: current_user_id,
			};
		}
		if (search) {
			params = {
				...params,
				search,
			};
		}

		return await API.get(ajaxurl, {
			params,
		}).then(
			(response) => {
				dispatch({
					type: FETCH_ALL_QUOTATIONS,
					payload: {
						data: response?.data?.data?.quotations,
						totalItems: parseInt(response?.data?.data?.total),
						status,
					},
				});
				return response;
			},
			(e) => {
				renderError(e);
			}
		);
	};

export const updateCurrentPage = (page) => (dispatch) => {
	dispatch({
		type: UPDATE_CURRENT_PAGE,
		payload: page,
	});
};

export const getQuote = (id) => async (dispatch) => {
	return await API.get(ajaxurl, {
		params: {
			action: 'quotify/quotation/get',
			id,
		},
	}).then(
		(response) => {
			dispatch({
				type: FETCH_QUOTATION,
				payload: {
					quotation: response?.data.data?.quotation
				}
			});

			return response;
		},
		(e) => {
			renderError(e);
		}
	);
};

export const moveQuoteToTrash = (id) => async (dispatch) => {
	makeRequest({
		action: 'quotify/quotations/delete',
		id,
		force: false,
	}).then((response) => {
		if (response.data?.success) {
			dispatch({
				type: MOVE_TO_TRASH,
				payload: response.data,
			});

			fireNotify(__(`Moved to Trash!`, 'quotify'), 'success');
		} else {
			renderError(e);
		}
	});
};

export const deleteQuote = (id) => async (dispatch) => {
	makeRequest({
		action: 'quotify/quotations/delete',
		id,
		force: true,
	}).then((response) => {
		console.log('response', response);
		if (response.data?.success) {
			dispatch({
				type: DELETE_QUOTATION,
				payload: response.data,
			});

			fireNotify(__(`Quotation Deleted!`, 'quotify'), 'success');
		} else {
			renderError(e);
		}
	});
};

export const restoreQuote = (params) => async (dispatch) => {
	makeRequest({
		action: 'quotify/quotations/restore',
		id: params.id,
	}).then((response) => {
		if (response.data?.success) {
			dispatch({
				type: RESTORE_QUOTATION,
				payload: response.data,
			});

			fireNotify(
				__(`Quotation restored successfully!`, 'quotify'),
				'success'
			);
		} else {
			renderError(e);
		}
	});
};
