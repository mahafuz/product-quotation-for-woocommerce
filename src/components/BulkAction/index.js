import React, { useState } from 'react';

import { Button } from '@wordpress/components';
import Select from 'react-select';
import { __ } from '@wordpress/i18n';

const defaultProps = {
	data: [],
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
	return (
		<React.Fragment>
			<div className="quotify-bulk-actions">
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
						if (
							data.selectedRows &&
							data.selectedRows.length > 0 &&
							bulkAction.value &&
							confirm(confirmMessage) //eslint-disable-line
						) {
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
