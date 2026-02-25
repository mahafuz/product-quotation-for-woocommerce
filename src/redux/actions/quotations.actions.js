import { fireNotify, renderError } from '@Utils/spa';

import {
	API,
	getAjaxUrl,
	makeRequest,
	currentUserCan,
	getCurrentUserId,
	isAdmin,
} from '@Utils/global';

import {
	DELETE_QUOTATION,
	FETCH_ALL_QUOTATIONS,
	FETCH_QUOTATION,
	UPDATE_CURRENT_PAGE,
	MOVE_TO_TRASH,
	RESTORE_QUOTATION,
} from '@Redux/types/quotations.types';

import { __ } from '@wordpress/i18n';
import { ajaxNonce } from '@Utils/config';

export const fetchAllQuotations =
	(status = 'publish', page = 1, per_page = 10, search = '') =>
	async (dispatch) => {
		let params = {
			action: 'quotify/ajax/quotations/load',
			status: status === 'all' ? 'any' : status,
			nonce: ajaxNonce(),
			page,
			per_page,
			context: 'edit',
		};
		if (!isAdmin || currentUserCan().manage_options === false) {
			params = {
				...params,
				author: getCurrentUserId(),
			};
		}
		if (search) {
			params = {
				...params,
				search,
			};
		}

		return await API.get(getAjaxUrl(), {
			params,
		}).then(
			(response) => {
				dispatch({
					type: FETCH_ALL_QUOTATIONS,
					payload: {
						data: response?.data?.data?.quotations,
						totalItems: parseInt(response?.data?.data?.total),
						status,
						currentPage: parseInt(
							response?.data?.data?.currentPage
						),
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
	return await API.get(getAjaxUrl(), {
		params: {
			action: 'quotify/ajax/quotations/get',
			id,
			nonce: ajaxNonce(),
		},
	}).then(
		(response) => {
			dispatch({
				type: FETCH_QUOTATION,
				payload: {
					quotation: response?.data.data?.quotation,
				},
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
		action: 'quotify/ajax/quotations/delete',
		id,
		nonce: ajaxNonce(),
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
		action: 'quotify/ajax/quotations/delete',
		id,
		nonce: ajaxNonce(),
		force: true,
	}).then((response) => {
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
		action: 'quotify/ajax/quotations/restore',
		id: params.id,
		nonce: ajaxNonce(),
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
