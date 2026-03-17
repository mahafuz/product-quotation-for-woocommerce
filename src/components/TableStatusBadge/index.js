import { __ } from '@wordpress/i18n';
import './index.scss';

const TableStatusBadge = ({ status }) => {
	const statusConfig = {
		pending: {
			label: __('Pending', 'quotify'),
			className: 'status-pending',
			icon: '⏱'
		},
		publish: {
			label: __('Approved', 'quotify'),
			className: 'status-approved',
			icon: '✓'
		},
		trash: {
			label: __('Trash', 'quotify'),
			className: 'status-trash',
			icon: '🗑'
		},
		draft: {
			label: __('Draft', 'quotify'),
			className: 'status-draft',
			icon: '📝'
		},
	};

	const config = statusConfig[status] || {
		label: status,
		className: 'status-unknown',
		icon: ''
	};

	return (
		<span className={`quotify-table-status ${config.className}`}>
			{config.icon && <span className="quotify-table-status__icon">{config.icon}</span>}
			<span className="quotify-table-status__label">{config.label}</span>
		</span>
	);
};

export default TableStatusBadge;
