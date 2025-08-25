import {
	API,
	current_user_can,
	current_user_id,
	fireNotify,
	namespace,
	is_admin,
	renderError,
} from '@Utils/helper';

import {
	CREATE_NEW_QUOTATION,
	DELETE_QUOTATION,
	FETCH_ALL_QUOTATIONS,
	FETCH_QUOTATION,
	UPDATE_QUOTATION,
	UPDATE_CURRENT_PAGE,
	MOVE_TO_TRASH,
	RESTORE_QUOTATION,
	STATUS_UPDATE,
} from '@Redux/types/quotations.types';

import { __ } from '@wordpress/i18n';
import { ajaxurl } from '@Utils/helper';

export const createNewQuotation = (courseFormData) => async (dispatch) => { };
export const getQuotation = (ID) => async (dispatch) => { };
export const updateQuotation = (courseFormData) => async (dispatch) => { };
export const deleteQuotation = (params) => async (dispatch) => { };
export const moveQuotationToTrash = (params) => async (dispatch) => { };
export const restoreQuotation = (courseFormData) => async (dispatch) => { };
export const quotationStatusUpdate = (courseFormData) => async (dispatch) => { };

export const fetchAllQuotations = (status = 'publish', page = 1, per_page = 10, search = '') =>
	async (dispatch) => {
		let params = {
			action: 'pqfw_get_quotations',
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
			(res) => {
				dispatch({
					type: FETCH_ALL_QUOTATIONS,
					payload: {
						data: res.data,
						totalItems: parseInt(res.headers['x-wp-total']),
						status,
					},
				});
				return res;
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
