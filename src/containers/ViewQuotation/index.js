import { useState, useEffect, useRef } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { __ } from '@wordpress/i18n';
import {
	getQuote,
	updateQuoteStatus,
	emailQuotation,
	moveQuoteToTrash,
	deleteQuote,
} from '@Redux/actions/quotations.actions';
import TopBar from '@Components/TopBar';
import { getRoutePath } from '@Utils/global';
import { useNavigate } from 'react-router-dom';

import './index.scss';

// Status badge component
const StatusBadge = ({ status, isDropdown }) => {
	const statusConfig = {
		pending: {
			label: __('Pending', 'quotify'),
			className: 'status-pending',
		},
		publish: {
			label: __('Approved', 'quotify'),
			className: 'status-approved',
		},
		trash: { label: __('Trash', 'quotify'), className: 'status-trash' },
		draft: { label: __('Draft', 'quotify'), className: 'status-draft' },
	};

	const config = statusConfig[status] || {
		label: status,
		className: 'status-unknown',
	};

	if (isDropdown) {
		return (
			<span
				className={`quotify-status-badge ${config.className} status-clickable`}
			>
				<span className="status-dot"></span>
				{config.label}
				<svg
					width="12"
					height="12"
					viewBox="0 0 24 24"
					fill="none"
					stroke="currentColor"
					strokeWidth="2"
				>
					<polyline points="6 9 12 15 18 9" />
				</svg>
			</span>
		);
	}

	return (
		<span className={`quotify-status-badge ${config.className}`}>
			<span className="status-dot"></span>
			{config.label}
		</span>
	);
};

// Loading skeleton component
const LoadingSkeleton = () => (
	<div className="quotify-content-wrap">
		<div className="quotify-skeleton">
			<div className="skeleton-header">
				<div className="skeleton-title"></div>
				<div className="skeleton-meta"></div>
			</div>
			<div className="skeleton-card">
				<div className="skeleton-section-title"></div>
				<div className="skeleton-line"></div>
				<div className="skeleton-line"></div>
				<div className="skeleton-line"></div>
			</div>
			<div className="skeleton-card">
				<div className="skeleton-section-title"></div>
				<div className="skeleton-product">
					<div className="skeleton-product-img"></div>
					<div className="skeleton-product-info">
						<div className="skeleton-line"></div>
						<div className="skeleton-line short"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
);

// Product item component with image fallback
const ProductItem = ({ product, index }) => {
	const [imgError, setImgError] = useState(false);

	const handleImageError = () => {
		setImgError(true);
	};

	return (
		<div className="quotify-product-card" key={product.id || index}>
			<div className="quotify-product-image">
				{!imgError && product.img ? (
					<img
						src={product.img}
						alt={product.name}
						onError={handleImageError}
					/>
				) : (
					<div className="quotify-product-image-placeholder">
						<svg
							width="40"
							height="40"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							strokeWidth="2"
						>
							<rect
								x="3"
								y="3"
								width="18"
								height="18"
								rx="2"
								ry="2"
							/>
							<circle cx="8.5" cy="8.5" r="1.5" />
							<polyline points="21 15 16 10 5 21" />
						</svg>
					</div>
				)}
				{product.quantity && product.quantity > 1 && (
					<span className="quotify-product-quantity">
						x{product.quantity}
					</span>
				)}
			</div>
			<div className="quotify-product-details">
				<a
					href={product.link}
					target="_blank"
					rel="noreferrer"
					className="quotify-product-name"
				>
					<h4>{product.name}</h4>
				</a>
				{product.price && (
					<div className="quotify-product-price">
						<span
							dangerouslySetInnerHTML={{
								__html: product.price,
							}}
						/>
					</div>
				)}
				{product.message && (
					<div className="quotify-product-message">
						<strong>{__('Note:', 'quotify')}</strong>{' '}
						{product.message}
					</div>
				)}
			</div>
		</div>
	);
};

// Info row component
const InfoRow = ({ label, value, icon }) => (
	<div className="quotify-info-row">
		{icon && <span className="quotify-info-icon">{icon}</span>}
		<span className="quotify-info-label">{label}</span>
		<span className="quotify-info-value">{value}</span>
	</div>
);

function Index({ id }) {
	const dispatch = useDispatch();
	const navigate = useNavigate();
	const quotationState = useSelector((state) => state.quotation);

	const [localLoading, setLocalLoading] = useState(true);
	const [localError, setLocalError] = useState(null);
	const [updatingStatus, setUpdatingStatus] = useState(false);
	const [sendingEmail, setSendingEmail] = useState(false);
	const [showStatusDropdown, setShowStatusDropdown] = useState(false);
	const [showActionMenu, setShowActionMenu] = useState(false);

	const statusDropdownRef = useRef(null);
	const actionMenuRef = useRef(null);

	const quotation = quotationState?.quotation;
	const meta = quotation?.meta || {};

	// Close dropdowns when clicking outside
	useEffect(() => {
		const handleClickOutside = (event) => {
			if (
				statusDropdownRef.current &&
				!statusDropdownRef.current.contains(event.target)
			) {
				setShowStatusDropdown(false);
			}
			if (
				actionMenuRef.current &&
				!actionMenuRef.current.contains(event.target)
			) {
				setShowActionMenu(false);
			}
		};

		document.addEventListener('mousedown', handleClickOutside);
		return () => {
			document.removeEventListener('mousedown', handleClickOutside);
		};
	}, []);

	useEffect(() => {
		setLocalLoading(true);
		setLocalError(null);

		dispatch(getQuote(id))
			.then((response) => {
				if (response?.data?.data?.not_found) {
					navigate(`${getRoutePath()}admin.php?page=quotify`);
				}
				setLocalLoading(false);
			})
			.catch(() => {
				setLocalError(
					__('Failed to load quotation. Please try again.', 'quotify')
				);
				setLocalLoading(false);
			});
	}, [id, dispatch, navigate]);

	// Handle status change
	const handleStatusChange = (newStatus) => {
		setUpdatingStatus(true);
		setShowStatusDropdown(false);
		setLocalLoading(true);
		setLocalError(null);

		dispatch(updateQuoteStatus(id, newStatus)).finally(() => {
		setUpdatingStatus(false);

		dispatch(getQuote(id))
			.then((response) => {
				if (response?.data?.data?.not_found) {
					navigate(`${getRoutePath()}admin.php?page=quotify`);
				}
				setLocalLoading(false);
			})
			.catch(() => {
				setLocalError(
					__('Failed to load quotation. Please try again.', 'quotify')
				);
				setLocalLoading(false);
			});
		});
	};

	const handlePrint = () => {
		window.print();
	};

	// Handle export (simplified - just print for now)
	const handleExport = () => {
		setShowActionMenu(false);
		window.print();
	};

	// Handle delete
	const handleDelete = () => {
		if (
			confirm(
				__('Are you sure you want to delete this quotation?', 'quotify')
			)
		) {
			setShowActionMenu(false);
			dispatch(deleteQuote(id));
			navigate(`${getRoutePath()}admin.php?page=quotify`);
		}
	};

	// Handle move to trash
	const handleTrash = () => {
		setShowActionMenu(false);
		dispatch(moveQuoteToTrash(id))
			.then((response) => {
				if (response?.data?.success) {
					// Refresh to show updated status
					dispatch(getQuote(id));
				}
			})
			.catch(() => {
				// If failed, refresh to get correct state
				dispatch(getQuote(id));
			});
	};

	// Status options
	const statusOptions = [
		{ value: 'pending', label: __('Pending', 'quotify'), icon: '⏱' },
		{ value: 'publish', label: __('Approved', 'quotify'), icon: '✓' },
		{ value: 'draft', label: __('Draft', 'quotify'), icon: '📝' },
		{ value: 'trash', label: __('Trash', 'quotify'), icon: '🗑' },
	];

	// Handle loading state
	if (localLoading) {
		return (
			<>
				<TopBar
					render={() => (
						<div className="quotify-top-bar-left">
							<h4 className="quotify-top-bar-heading">
								{__('Quote Details', 'quotify')}
							</h4>
						</div>
					)}
				/>
				<LoadingSkeleton />
			</>
		);
	}

	// Handle error state
	if (localError) {
		return (
			<>
				<TopBar
					render={() => (
						<div className="quotify-top-bar-left">
							<h4 className="quotify-top-bar-heading">
								{__('Quote Details', 'quotify')}
							</h4>
						</div>
					)}
				/>
				<div className="quotify-content-wrap">
					<div className="quotify-error-state">
						<svg
							width="64"
							height="64"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							strokeWidth="2"
						>
							<circle cx="12" cy="12" r="10" />
							<line x1="12" y1="8" x2="12" y2="12" />
							<line x1="12" y1="16" x2="12.01" y2="16" />
						</svg>
						<h3>{__('Error Loading Quotation', 'quotify')}</h3>
						<p>{localError}</p>
						<button
							className="quotify-btn quotify-btn-primary"
							onClick={() => window.location.reload()}
						>
							{__('Retry', 'quotify')}
						</button>
					</div>
				</div>
			</>
		);
	}

	// Handle no quotation found
	if (!quotation) {
		return (
			<>
				<TopBar
					render={() => (
						<div className="quotify-top-bar-left">
							<h4 className="quotify-top-bar-heading">
								{__('Quote Details', 'quotify')}
							</h4>
						</div>
					)}
				/>
				<div className="quotify-content-wrap">
					<div className="quotify-empty-state">
						<svg
							width="64"
							height="64"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							strokeWidth="2"
						>
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
							<polyline points="14 2 14 8 20 8" />
							<line x1="16" y1="13" x2="8" y2="13" />
							<line x1="16" y1="17" x2="8" y2="17" />
							<polyline points="10 9 9 9 8 9" />
						</svg>
						<h3>{__('Quotation Not Found', 'quotify')}</h3>
						<p>
							{__(
								'The quotation you are looking for does not exist.',
								'quotify'
							)}
						</p>
					</div>
				</div>
			</>
		);
	}

	const products = Array.isArray(meta.pqfw_products_info)
		? meta.pqfw_products_info
		: [];

	return (
		<div className="quotify-single-quotation">
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">
							{__('Quote Details', 'quotify')}
						</h4>
					</div>
				)}
			/>

			<div className="quotify-content-wrap">
				{/* Header Section */}
				<div className="quotify-quote-header">
					<div className="quotify-quote-title-section">
						<h1 className="quotify-quote-title">
							{quotation.title}
						</h1>
						<div className="quotify-quote-meta">
							<span className="quotify-quote-id">
								#{quotation.ID}
							</span>
						</div>
					</div>
					<div className="quotify-quote-actions">
						<div
							className="quotify-status-wrapper"
							ref={statusDropdownRef}
						>
							<button
								className="quotify-status-badge-button"
								onClick={() =>
									setShowStatusDropdown(!showStatusDropdown)
								}
							>
								<StatusBadge
									status={quotation.status}
									isDropdown={true}
								/>
							</button>
							{showStatusDropdown && (
								<div className="quotify-dropdown-menu">
									{statusOptions.map((option) => (
										<button
											key={option.value}
											className={`quotify-dropdown-item ${
												quotation.status ===
												option.value
													? 'active'
													: ''
											}`}
											onClick={() =>
												handleStatusChange(option.value)
											}
											disabled={updatingStatus}
										>
											<span className="status-icon">
												{option.icon}
											</span>
											{option.label}
											{quotation.status ===
												option.value && (
												<svg
													width="16"
													height="16"
													viewBox="0 0 24 24"
													fill="none"
													stroke="currentColor"
													strokeWidth="2"
												>
													<polyline points="20 6 9 17 4 12" />
												</svg>
											)}
										</button>
									))}
								</div>
							)}
						</div>
						<div
							className="quotify-action-menu"
							ref={actionMenuRef}
						>
							<button
								className="quotify-btn quotify-btn-secondary"
								onClick={() =>
									setShowActionMenu(!showActionMenu)
								}
							>
								<svg
									width="16"
									height="16"
									viewBox="0 0 24 24"
									fill="none"
									stroke="currentColor"
									strokeWidth="2"
								>
									<circle cx="12" cy="12" r="1" />
									<circle cx="12" cy="5" r="1" />
									<circle cx="12" cy="19" r="1" />
								</svg>
								{__('Actions', 'quotify')}
							</button>
							{showActionMenu && (
								<div className="quotify-dropdown-menu">
									<button
										className="quotify-dropdown-item"
										onClick={() => {
											setShowActionMenu(false);
											navigate(
												`${getRoutePath()}admin.php?page=quotify`
											);
										}}
									>
										<svg
											width="16"
											height="16"
											viewBox="0 0 24 24"
											fill="none"
											stroke="currentColor"
											strokeWidth="2"
										>
											<line
												x1="19"
												y1="12"
												x2="5"
												y2="12"
											/>
											<polyline points="12 19 5 12 12 5" />
										</svg>
										{__('Back to List', 'quotify')}
									</button>
									<div className="quotify-dropdown-divider"></div>
									{quotation.status !== 'trash' && (
										<button
											className="quotify-dropdown-item danger"
											onClick={handleTrash}
										>
											<svg
												width="16"
												height="16"
												viewBox="0 0 24 24"
												fill="none"
												stroke="currentColor"
												strokeWidth="2"
											>
												<polyline points="3 6 5 6 21 6" />
												<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
											</svg>
											{__('Move to Trash', 'quotify')}
										</button>
									)}
									<button
										className="quotify-dropdown-item danger"
										onClick={handleDelete}
									>
										<svg
											width="16"
											height="16"
											viewBox="0 0 24 24"
											fill="none"
											stroke="currentColor"
											strokeWidth="2"
										>
											<polyline points="3 6 5 6 21 6" />
											<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
											<line
												x1="10"
												y1="11"
												x2="10"
												y2="17"
											/>
											<line
												x1="14"
												y1="11"
												x2="14"
												y2="17"
											/>
										</svg>
										{__('Delete Permanently', 'quotify')}
									</button>
								</div>
							)}
						</div>
					</div>
				</div>

				<div className="quotify-grid">
					{/* Customer Information Card */}
					<div className="quotify-card quotify-customer-card">
						<div className="quotify-card-header">
							<h3 className="quotify-card-title">
								<svg
									width="20"
									height="20"
									viewBox="0 0 24 24"
									fill="none"
									stroke="currentColor"
									strokeWidth="2"
								>
									<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
									<circle cx="12" cy="7" r="4" />
								</svg>
								{__('Customer Information', 'quotify')}
							</h3>
						</div>
						<div className="quotify-card-body">
							<InfoRow
								label={__('Name', 'quotify')}
								value={meta.pqfw_customer_name}
							/>
							<InfoRow
								label={__('Email', 'quotify')}
								value={
									<a
										href={`mailto:${meta.pqfw_customer_email}`}
									>
										{meta.pqfw_customer_email}
									</a>
								}
							/>
							{meta.pqfw_customer_phone && (
								<InfoRow
									label={__('Phone', 'quotify')}
									value={meta.pqfw_customer_phone}
								/>
							)}
							<InfoRow
								label={__('Date', 'quotify')}
								value={quotation.date}
							/>
						</div>
					</div>

					{/* Quote Details Card */}
					<div className="quotify-card quotify-details-card">
						<div className="quotify-card-header">
							<h3 className="quotify-card-title">
								<svg
									width="20"
									height="20"
									viewBox="0 0 24 24"
									fill="none"
									stroke="currentColor"
									strokeWidth="2"
								>
									<circle cx="12" cy="12" r="10" />
									<line x1="12" y1="16" x2="12" y2="12" />
									<line x1="12" y1="8" x2="12.01" y2="8" />
								</svg>
								{__('Quote Details', 'quotify')}
							</h3>
						</div>
						<div className="quotify-card-body">
							{meta.pqfw_customer_subject && (
								<InfoRow
									label={__('Subject', 'quotify')}
									value={meta.pqfw_customer_subject}
								/>
							)}
							{meta.pqfw_customer_comments && (
								<div className="quotify-comments">
									<span className="quotify-info-label">
										{__('Comments', 'quotify')}
									</span>
									<p className="quotify-comments-text">
										{meta.pqfw_customer_comments}
									</p>
								</div>
							)}
						</div>
					</div>
				</div>

				{/* Products Card */}
				<div className="quotify-card quotify-products-card">
					<div className="quotify-card-header">
						<h3 className="quotify-card-title">
							<svg
								width="20"
								height="20"
								viewBox="0 0 24 24"
								fill="none"
								stroke="currentColor"
								strokeWidth="2"
							>
								<circle cx="9" cy="21" r="1" />
								<circle cx="20" cy="21" r="1" />
								<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
							</svg>
							{__('Products', 'quotify')}
							<span className="quotify-product-count">
								{products.length} {__('items', 'quotify')}
							</span>
						</h3>
					</div>
					<div className="quotify-card-body">
						{products.length > 0 ? (
							<div className="quotify-products-list">
								{products.map((product, index) => (
									<ProductItem
										key={product.id || index}
										product={product}
										index={index}
									/>
								))}
							</div>
						) : (
							<div className="quotify-empty-products">
								<svg
									width="48"
									height="48"
									viewBox="0 0 24 24"
									fill="none"
									stroke="currentColor"
									strokeWidth="2"
								>
									<circle cx="9" cy="21" r="1" />
									<circle cx="20" cy="21" r="1" />
									<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
								</svg>
								<p>
									{__(
										'No products in this quotation.',
										'quotify'
									)}
								</p>
							</div>
						)}
					</div>
				</div>
			</div>
		</div>
	);
}

export default Index;
