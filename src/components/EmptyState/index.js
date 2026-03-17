import { __ } from '@wordpress/i18n';
import './index.scss';

const EmptyState = ({ type = 'quotations', action }) => {
	const configurations = {
		quotations: {
			icon: (
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
					<circle cx="9" cy="21" r="1" />
					<circle cx="20" cy="21" r="1" />
					<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
				</svg>
			),
			title: __('No Quotations Yet', 'quotify'),
			message: __("You haven't received any quotation requests yet. When customers submit quotation requests, they'll appear here.", 'quotify'),
		},
		search: {
			icon: (
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
					<circle cx="11" cy="11" r="8" />
					<path d="m21 21-4.35-4.35" />
				</svg>
			),
			title: __('No Results Found', 'quotify'),
			message: __("We couldn't find any quotations matching your search. Try different keywords or filters.", 'quotify'),
		},
		filter: {
			icon: (
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
					<polygon points="22 3 2 3 10 12.46 10 19 14 21 22 21 22 3" />
					<line x1="12" y1="14" x2="12" y2="14.01" />
				</svg>
			),
			title: __('No Quotations in This Filter', 'quotify'),
			message: __("There are no quotations matching your current filter criteria.", 'quotify'),
		},
		trash: {
			icon: (
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
					<polyline points="3 6 5 6 21 6" />
					<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
				</svg>
			),
			title: __('Trash is Empty', 'quotify'),
			message: __("No quotations in trash. Items moved to trash are kept here for 30 days before permanent deletion.", 'quotify'),
		},
	};

	const config = configurations[type] || configurations.quotations;

	return (
		<div className="quotify-empty-state">
			<div className="quotify-empty-state__icon">
				{config.icon}
			</div>
			<h3 className="quotify-empty-state__title">{config.title}</h3>
			<p className="quotify-empty-state__message">{config.message}</p>
			{action && (
				<button
					className="quotify-btn quotify-btn-primary"
					onClick={action.onClick}
				>
					{action.label}
				</button>
			)}
		</div>
	);
};

export default EmptyState;
