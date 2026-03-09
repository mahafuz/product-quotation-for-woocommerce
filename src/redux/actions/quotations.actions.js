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
	STATUS_UPDATE,
	FETCH_STATS,
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

export const updateQuoteStatus = (id, status) => async (dispatch) => {
	return await makeRequest({
		action: 'quotify/ajax/quotations/update_status',
		id,
		status,
		nonce: ajaxNonce(),
	}).then((response) => {
		if (response.data?.success) {
			dispatch({
				type: STATUS_UPDATE,
				payload: { id, status },
			});

			// Update the current quotation in state
			dispatch({
				type: FETCH_QUOTATION,
				payload: {
					quotation: response.data?.data?.quotation,
				},
			});

			fireNotify(
				__( 'Quotation status updated successfully!', 'quotify' ),
				'success'
			);

			return response;
		} else {
			renderError(response.data?.data || response.data);
			return response;
		}
	}).catch((error) => {
		renderError(error);
		return error;
	});
};

export const emailQuotation = (id) => async (dispatch) => {
	return await makeRequest({
		action: 'quotify/ajax/quotations/email',
		id,
		nonce: ajaxNonce(),
	}).then((response) => {
		if (response.data?.success) {
			fireNotify(
				__( 'Quotation sent to customer successfully!', 'quotify' ),
				'success'
			);
			return response;
		} else {
			renderError(response.data?.data || response.data);
			return response;
		}
	}).catch((error) => {
		renderError(error);
		return error;
	});
};

export const moveQuoteToTrash = (id) => async (dispatch) => {
	return makeRequest({
		action: 'quotify/ajax/quotations/delete',
		id,
		nonce: ajaxNonce(),
		force: false,
	}).then((response) => {
		if (response.data?.success) {
			dispatch({
				type: MOVE_TO_TRASH,
				payload: { id },
			});

			fireNotify(__(`Moved to Trash!`, 'quotify'), 'success');
		} else {
			renderError(response.data?.data || response.data);
		}
		return response;
	}).catch((error) => {
		renderError(error);
		return error;
	});
};

export const deleteQuote = (id) => async (dispatch) => {
	return makeRequest({
		action: 'quotify/ajax/quotations/delete',
		id,
		nonce: ajaxNonce(),
		force: true,
	}).then((response) => {
		if (response.data?.success) {
			dispatch({
				type: DELETE_QUOTATION,
				payload: { id },
			});

			fireNotify(__(`Quotation Deleted!`, 'quotify'), 'success');
		} else {
			renderError(response.data?.data || response.data);
		}
		return response;
	}).catch((error) => {
		renderError(error);
		return error;
	});
};

export const restoreQuote = (id) => async (dispatch) => {
	return makeRequest({
		action: 'quotify/ajax/quotations/restore',
		id,
		nonce: ajaxNonce(),
	}).then((response) => {
		if (response.data?.success) {
			dispatch({
				type: RESTORE_QUOTATION,
				payload: { id },
			});

			fireNotify(
				__(`Quotation restored successfully!`, 'quotify'),
				'success'
			);
		} else {
			renderError(response.data?.data || response.data);
		}
		return response;
	}).catch((error) => {
		renderError(error);
		return error;
	});
};

export const fetchStats = (date_filter = 'all') => async (dispatch) => {
	return await makeRequest({
		action: 'quotify/ajax/quotations/stats',
		date_filter,
		nonce: ajaxNonce(),
	}).then((response) => {
		if (response.data?.success) {
			dispatch({
				type: FETCH_STATS,
				payload: response.data?.data?.stats || {},
			});
			return response.data?.data?.stats;
		} else {
			renderError(response.data?.data || response.data);
			return null;
		}
	}).catch((error) => {
		renderError(error);
		return null;
	});
};
