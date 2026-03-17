import React, { useState } from 'react';

import { Button } from '@wordpress/components';
import Select from 'react-select';
import { __ } from '@wordpress/i18n';

const defaultProps = {
	data: {},
	applyActionHandler: () => {},
	confirmMessage: '',
	options: [{ value: 'delete', label: __('Delete', 'quotify') }],
};

export default function BulkAction({
	data,
	applyActionHandler,
	confirmMessage,
	options,
}) {
	const [bulkAction, setBulkAction] = useState({});

	// Only show when rows are selected
	const selectedCount = data?.selectedRows?.length || 0;
	if (selectedCount === 0) {
		return null;
	}

	// Get confirm message - can be a string or a function
	const getConfirmMessage = () => {
		if (typeof confirmMessage === 'function') {
			return confirmMessage(bulkAction);
		}
		return confirmMessage;
	};

	return (
		<React.Fragment>
			<div className="quotify-bulk-actions">
				<span className="quotify-bulk-count">
					{selectedCount} {selectedCount === 1 ? __('item selected', 'quotify') : __('items selected', 'quotify')}
				</span>
				<Select
					className="quotify-select"
					classNamePrefix="quotify-react-select"
					placeholder={__('Bulk Actions', 'quotify')}
					isClearable={true}
					value={options.filter(
						(item) => item.value === bulkAction?.value
					)}
					options={options}
					onChange={(e) => setBulkAction(e)}
				/>
				<Button
					className='quotify-button'
					type="button"
					preset="light-purple"
					onClick={() => {
						if (bulkAction.value) {
							const message = getConfirmMessage();
							if (message && !confirm(message)) { //eslint-disable-line
								return;
							}
							applyActionHandler(data.selectedRows, bulkAction);
							setBulkAction('');
						}
					}}
				>{__('Apply', 'quotify')}</Button>
			</div>
		</React.Fragment>
	);
}

BulkAction.defaultProps = defaultProps;
