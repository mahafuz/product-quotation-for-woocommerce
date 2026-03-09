import { useState, useEffect, useRef } from 'react';
import { __ } from '@wordpress/i18n';
import { useNavigate } from 'react-router-dom';
import { isAdmin, getRoutePath } from '@Utils/global';
import { updateQuoteStatus, emailQuotation, moveQuoteToTrash, deleteQuote, restoreQuote } from '@Redux/actions/quotations.actions';
import { useDispatch } from 'react-redux';
import './index.scss';

const QuickActions = ({ quotation, onUpdate, onEmail, onDelete }) => {
	const dispatch = useDispatch();
	const navigate = useNavigate();
	const [isOpen, setIsOpen] = useState(false);
	const [updating, setUpdating] = useState(false);
	const [sendingEmail, setSendingEmail] = useState(false);
	const dropdownRef = useRef(null);

	// Close dropdown when clicking outside
	useEffect(() => {
		const handleClickOutside = (event) => {
			if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
				setIsOpen(false);
			}
		};

		document.addEventListener('mousedown', handleClickOutside);
		return () => {
			document.removeEventListener('mousedown', handleClickOutside);
		};
	}, []);

	const handleStatusChange = (newStatus) => {
		setUpdating(true);
		setIsOpen(false);

		dispatch(updateQuoteStatus(quotation.id || quotation.ID, newStatus))
			.finally(() => {
				setUpdating(false);
				if (onUpdate) {
					onUpdate();
				}
			});
	};

	const handleEmail = () => {
		setSendingEmail(true);
		setIsOpen(false);

		dispatch(emailQuotation(quotation.id || quotation.ID))
			.finally(() => {
				setSendingEmail(false);
				if (onEmail) {
					onEmail();
				}
			});
	};

	const handleView = () => {
		setIsOpen(false);
		const id = quotation.id || quotation.ID;
		if (isAdmin()) {
			navigate(`${getRoutePath()}admin.php?page=quotify&id=${id}&action=view`);
		} else {
			navigate(`view-quote/${id}`);
		}
	};

	const handleTrash = () => {
		setIsOpen(false);
		dispatch(moveQuoteToTrash({ ID: quotation.id || quotation.ID }))
			.finally(() => {
				if (onDelete) {
					onDelete();
				}
			});
	};

	const handleDelete = () => {
		if (confirm(__('Are you sure you want to permanently delete this quotation?', 'quotify'))) {
			setIsOpen(false);
			dispatch(deleteQuote({ ID: quotation.id || quotation.ID }))
				.finally(() => {
					if (onDelete) {
						onDelete();
					}
				});
		}
	};

	const handleRestore = () => {
		setIsOpen(false);
		dispatch(restoreQuote(quotation.id || quotation.ID))
			.finally(() => {
				if (onDelete) {
					onDelete();
				}
			});
	};

	const isTrashed = quotation.status === 'trash';
	const isPending = quotation.status === 'pending';
	const isApproved = quotation.status === 'publish';

	return (
		<div className="quotify-quick-actions" ref={dropdownRef}>
			<button
				className="quotify-quick-actions__trigger"
				onClick={() => setIsOpen(!isOpen)}
				type="button"
				aria-label={__('Actions', 'quotify')}
				aria-expanded={isOpen}
			>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
					<circle cx="12" cy="12" r="1" />
					<circle cx="12" cy="5" r="1" />
					<circle cx="12" cy="19" r="1" />
				</svg>
			</button>

			{isOpen && (
				<div className="quotify-quick-actions__dropdown">
					{!isTrashed && (
						<>
							<button
								className="quotify-quick-actions__item"
								onClick={handleView}
								type="button"
							>
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
									<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11-8-8z" />
									<circle cx="12" cy="12" r="3" />
								</svg>
								{__('View Details', 'quotify')}
							</button>

							<div className="quotify-quick-actions__divider"></div>

							{isPending && (
								<button
									className="quotify-quick-actions__item"
									onClick={() => handleStatusChange('publish')}
									disabled={updating}
									type="button"
								>
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
										<polyline points="20 6 9 17 4 12" />
									</svg>
									{updating ? __('Approving...', 'quotify') : __('Approve', 'quotify')}
								</button>
							)}

							{isApproved && (
								<button
									className="quotify-quick-actions__item"
									onClick={() => handleStatusChange('pending')}
									disabled={updating}
									type="button"
								>
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
										<circle cx="12" cy="12" r="10" />
										<polyline points="12 6 12 12 12 18" />
									</svg>
									{updating ? __('Marking Pending...', 'quotify') : __('Mark Pending', 'quotify')}
								</button>
							)}

							<button
								className="quotify-quick-actions__item"
								onClick={handleTrash}
								type="button"
							>
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
									<polyline points="3 6 5 6 21 6" />
									<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
								</svg>
								{__('Move to Trash', 'quotify')}
							</button>
						</>
					)}

					{isTrashed && (
						<button
							className="quotify-quick-actions__item"
							onClick={handleRestore}
							type="button"
						>
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
								<polyline points="9 14 4 9 9 4" />
								<path d="M20 20v-7a4 4 0 0 0-4-4H4a4 4 0 0 0-4 4v7" />
								<line x1="12" y1="20" x2="12" y2="4" />
							</svg>
							{__('Restore', 'quotify')}
						</button>
					)}

					<div className="quotify-quick-actions__divider"></div>

					<button
						className="quotify-quick-actions__item quotify-quick-actions__item--danger"
						onClick={handleDelete}
						type="button"
					>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
							<polyline points="3 6 5 6 21 6" />
							<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
							<line x1="10" y1="11" x2="10" y2="17" />
							<line x1="14" y1="11" x2="14" y2="17" />
						</svg>
						{__('Delete Permanently', 'quotify')}
					</button>
				</div>
			)}
		</div>
	);
};

export default QuickActions;
