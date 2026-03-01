import { useEffect, useState, useMemo, useCallback, useRef } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import {
	fetchAllQuotations,
	updateCurrentPage,
	fetchStats,
	moveQuoteToTrash,
	deleteQuote,
	restoreQuote,
} from '@Redux/actions/quotations.actions';
import DataTable from 'react-data-table-component';
import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';

import TopBar from '@Components/TopBar';
import StatCard from '@Components/StatCard';
import SearchBar from '@Components/SearchBar';
import TableStatusBadge from '@Components/TableStatusBadge';
import QuickActions from '@Components/QuickActions';
import EmptyState from '@Components/EmptyState';
import BulkAction from '@Components/BulkAction';

import { ajaxNonce } from '@Utils/config';

import { getRoutePath, isAdmin, sliceString, getAjaxUrl } from '@Utils/global';
import { fireNotify } from '@Utils/spa';

import './index.scss';

// Status filter options - now including "Approved"
const STATUS_OPTIONS = [
	{ label: __('All', 'quotify'), value: 'all' },
	{ label: __('Pending', 'quotify'), value: 'pending' },
	{ label: __('Approved', 'quotify'), value: 'publish' },
	{ label: __('Trash', 'quotify'), value: 'trash' },
];

const QuotationsList = () => {
	const dispatch = useDispatch();
	const navigate = useNavigate();
	const quotations = useSelector((state) => state.quotations);

	// State
	const [fetching, setFetching] = useState(false);
	const [searching, setSearching] = useState(false);
	const [status, setStatus] = useState('all');
	const [searchTerm, setSearchTerm] = useState('');
	const [dateFilter, setDateFilter] = useState('all');
	const [bulkActionData, setBulkActionData] = useState({});
	const [hasInitialized, setHasInitialized] = useState(false);

	// Track initial mount
	const isInitialMount = useRef(true);

	// Fetch quotations with useCallback to prevent infinite re-renders
	const fetchQuotations = useCallback((page = 1, perPage = 10, search = '') => {
		setFetching(true);
		dispatch(fetchAllQuotations(status, page, perPage, search))
			.finally(() => {
				setFetching(false);
			});
	}, [dispatch, status]);

	// Fetch stats
	const fetchDashboardStats = useCallback((filter = 'all') => {
		dispatch(fetchStats(filter));
	}, [dispatch]);

	// Initial fetch only on mount
	useEffect(() => {
		if (isInitialMount.current) {
			isInitialMount.current = false;
			// Only fetch if we don't have data or status changed
			if (!quotations.data || quotations.data.length === 0) {
				setFetching(true);
				dispatch(fetchAllQuotations(status, 1, 10, ''))
					.finally(() => {
						setFetching(false);
						setHasInitialized(true);
					});
			} else {
				setHasInitialized(true);
			}
		}
	}, [dispatch, status, quotations.data]); // Only re-run if status changes

	// Fetch stats when date filter changes (including initial mount)
	useEffect(() => {
		fetchDashboardStats(dateFilter);
	}, [dateFilter, fetchDashboardStats]);

	// Handle status change
	const handleStatusChange = useCallback((newStatus) => {
		setStatus(newStatus);
		setBulkActionData({});
	}, []);

	// Handle search
	const handleSearch = useCallback((term) => {
		setSearching(true);
		setSearchTerm(term);
		setFetching(true);
		dispatch(fetchAllQuotations(status, 1, 10, term))
			.finally(() => {
				setSearching(false);
				setFetching(false);
			});
	}, [dispatch, status]);

	// Handle date filter change
	const handleDateChange = useCallback((filter) => {
		setDateFilter(filter);
	}, []);

	// Handle export to CSV
	const handleExport = useCallback(() => {
		const params = new URLSearchParams({
			action: 'quotify/ajax/quotations/export',
			nonce: ajaxNonce(),
			status: status,
			date_filter: dateFilter,
		});

		const exportUrl = `${getAjaxUrl()}?${params.toString()}`;

		// Create a hidden link to trigger download
		const link = document.createElement('a');
		link.href = exportUrl;
		link.download = `quotations-${new Date().toISOString().split('T')[0]}.csv`;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);

		fireNotify(__('Exporting quotations...', 'quotify'), 'success');
	}, [status, dateFilter]);

	// Handle page change
	const handlePageChange = useCallback((page) => {
		dispatch(updateCurrentPage(page));
		fetchQuotations(page, 10, searchTerm);
	}, [dispatch, fetchQuotations, searchTerm]);

	// Handle rows per page change
	const handleItemsPage = useCallback((itemsPerPage, page) => {
		dispatch(updateCurrentPage(page));
		fetchQuotations(page, itemsPerPage, searchTerm);
	}, [dispatch, fetchQuotations, searchTerm]);

	// Handle action callbacks - refreshes the list and stats
	const handleActionComplete = useCallback(() => {
		setBulkActionData({});
		// Refetch current page with current filters
		dispatch(fetchAllQuotations(status, quotations.currentPage || 1, 10, searchTerm));
		// Also refresh stats to reflect changes
		dispatch(fetchStats(dateFilter));
	}, [dispatch, status, quotations.currentPage, searchTerm, dateFilter]);

	// Bulk action handler
	const bulkActionHandler = useCallback((selectedRows, bulkAction) => {
		if (status !== 'trash') {
			selectedRows.forEach((item) => {
				dispatch(
					moveQuoteToTrash({
						ID: item.id ? item.id : item.ID,
					})
				);
			});
			fireNotify(
				__('Selected quotations moved to trash.', 'quotify'),
				'success'
			);
		} else if (bulkAction.value === 'restore') {
			selectedRows.forEach((item) => {
				dispatch(restoreQuote(item.id ? item.id : item.ID));
			});
			fireNotify(
				__('Selected quotations restored.', 'quotify'),
				'success'
			);
		} else {
			if (
				confirm(
					__(
						'Are you sure you want to permanently delete selected quotations?',
						'quotify'
					)
				)
			) {
				selectedRows.forEach((item) => {
					dispatch(
						deleteQuote({
							ID: item.id ? item.id : item.ID,
						})
					);
				});
				fireNotify(
					__('Selected quotations permanently deleted.', 'quotify'),
					'success'
				);
			}
		}
		setBulkActionData({});
	}, [status, dispatch]);

	// Bulk action options
	const bulkOptions = useMemo(() => {
		if (status !== 'trash') {
			return [{ value: 'trash', label: __('Move to Trash', 'quotify') }];
		}
		return [
			{ value: 'restore', label: __('Restore', 'quotify') },
			{ value: 'delete', label: __('Delete Permanently', 'quotify') },
		];
	}, [status]);

	// Table columns
	const columns = useMemo(() => [
		{
			name: __('Title', 'quotify'),
			sortable: true,
			minWidth: '250px',
			cell: (row) => (
				<div className="quotify-table-title-wrap">
					<button
						className="quotify-table-title-link"
						onClick={() => {
							if (isAdmin()) {
								navigate(
									`${getRoutePath()}admin.php?page=quotify&id=${row.id}&action=view`
								);
							} else {
								navigate(`view-quote/${row.id}`);
							}
						}}
						type="button"
					>
						<span dangerouslySetInnerHTML={{ __html: sliceString(row.title) }} />
					</button>
				</div>
			),
		},
		{
			name: __('Author', 'quotify'),
			sortable: true,
			minWidth: '150px',
			cell: (row) => (
				<div className="quotify-table-author">
					<span>{row.author_name || __('Guest', 'quotify')}</span>
				</div>
			),
		},
		{
			name: __('Date', 'quotify'),
			sortable: true,
			minWidth: '120px',
			cell: (row) => (
				<div className="quotify-table-date">
					<span>{row.date}</span>
				</div>
			),
		},
		{
			name: __('Status', 'quotify'),
			sortable: true,
			minWidth: '120px',
			cell: (row) => <TableStatusBadge status={row.status} />,
		},
		{
			name: __('Actions', 'quotify'),
			sortable: false,
			width: '100px',
			right: true,
			cell: (row) => (
				<QuickActions
					quotation={row}
					onUpdate={handleActionComplete}
					onEmail={handleActionComplete}
					onDelete={handleActionComplete}
				/>
			),
		},
	], [navigate, handleActionComplete]);

	// Stats icons
	const statsIcons = useMemo(() => ({
		total: (
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
				<polyline points="3.27 6.96 12 12.01 20.73 6.96" />
				<line x1="12" y1="22.08" x2="12" y2="12" />
			</svg>
		),
		pending: (
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<circle cx="12" cy="12" r="10" />
				<polyline points="12 6 12 12 12 18" />
			</svg>
		),
		approved: (
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
				<polyline points="22 4 12 14.01 9 11.01" />
			</svg>
		),
		trash: (
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<polyline points="3 6 5 6 21 6" />
				<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
				<line x1="10" y1="11" x2="10" y2="17" />
				<line x1="14" y1="11" x2="14" y2="17" />
			</svg>
		),
		value: (
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<line x1="12" y1="1" x2="12" y2="23" />
				<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
			</svg>
		),
	}), []);

	// Format currency value
	const formatValue = useCallback((value) => {
		// Ensure value is a number
		const numValue = typeof value === 'number' ? value : parseFloat(value || 0);
		return new Intl.NumberFormat('en-US', {
			style: 'currency',
			currency: 'USD',
			minimumFractionDigits: 0,
			maximumFractionDigits: 0,
		}).format(numValue);
	}, []);

	// Get empty state type
	const getEmptyStateType = useCallback(() => {
		if (searchTerm) return 'search';
		if (status === 'trash') return 'trash';
		return 'quotations';
	}, [searchTerm, status]);

	return (
		<>
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">
							{__('Quotations', 'quotify')}
						</h4>
					</div>
				)}
			/>

			<div className="quotify-content-wrap">
				<div className="quotify-quotations-wrapper">
					{/* Stats Dashboard */}
					<div className="quotify-stats-dashboard">
						<StatCard
							variant="primary"
							icon={statsIcons.total}
							label={__('Total Quotations', 'quotify')}
							value={quotations.stats?.total || 0}
							onClick={() => handleStatusChange('all')}
						/>
						<StatCard
							variant="warning"
							icon={statsIcons.pending}
							label={__('Pending', 'quotify')}
							value={quotations.stats?.pending || 0}
							onClick={() => handleStatusChange('pending')}
						/>
						<StatCard
							variant="success"
							icon={statsIcons.approved}
							label={__('Approved', 'quotify')}
							value={quotations.stats?.approved || 0}
							onClick={() => handleStatusChange('publish')}
						/>
						<StatCard
							variant="danger"
							icon={statsIcons.trash}
							label={__('Trash', 'quotify')}
							value={quotations.stats?.trash || 0}
							onClick={() => handleStatusChange('trash')}
						/>
						<StatCard
							variant="purple"
							icon={statsIcons.value}
							label={__('Total Value', 'quotify')}
							value={formatValue(quotations.stats?.value || 0)}
						/>
					</div>

					{/* Main Content */}
					<div className="quotify-quotations-content">
						{/* Header with Search and Filters */}
						<div className="quotify-quotations-header">
							<div className="quotify-quotations-header__filters">
								{STATUS_OPTIONS.map((option) => (
									<button
										key={option.value}
										className={`quotify-status-filter ${
											status === option.value ? 'quotify-status-filter--active' : ''
										}`}
										onClick={() => handleStatusChange(option.value)}
										type="button"
									>
										{option.label}
									</button>
								))}
							</div>

							<div className="quotify-quotations-header__actions">
								<button
									className="quotify-export-button"
									onClick={handleExport}
									type="button"
									title={__('Export to CSV', 'quotify')}
								>
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
										<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
										<polyline points="7 10 12 15 17 10" />
										<line x1="12" y1="15" x2="12" y2="3" />
									</svg>
									<span>{__('Export', 'quotify')}</span>
								</button>
								<BulkAction
									data={bulkActionData}
									applyActionHandler={bulkActionHandler}
									confirmMessage={
										status === 'trash'
											? __(
													'Are you sure you want to permanently delete selected quotations?',
													'quotify'
											  )
											: __(
													'Are you sure you want to move to trash?',
													'quotify'
											  )
									}
									options={bulkOptions}
								/>
							</div>
						</div>

						{/* Search Bar */}
						<div className="quotify-quotations-search">
							<SearchBar
								onSearch={handleSearch}
								onDateChange={handleDateChange}
								searching={searching}
							/>
						</div>

						{/* Table */}
						<div className="quotify-quotations-table">
							{fetching && !hasInitialized ? (
								<div className="quotify-table-loading">
									<Spinner />
									<span>{__('Loading quotations...', 'quotify')}</span>
								</div>
							) : quotations.data && quotations.data.length > 0 ? (
								<>
									{/* Desktop Table */}
									<div className="quotify-desktop-table">
										<DataTable
											columns={columns}
											data={quotations.data}
											selectableRows
											onSelectedRowsChange={setBulkActionData}
											pagination
											paginationServer
											paginationTotalRows={quotations.totalItems}
											onChangePage={handlePageChange}
											onChangeRowsPerPage={handleItemsPage}
											paginationDefaultPage={quotations.currentPage}
											paginationResetDefaultPage={false}
											persistTableHead
											className="quotify-list-table"
											noHeader
											progressPending={fetching}
											customStyles={{
												table: {
													style: {
														height: '600px',
													},
												},
											}}
											progressComponent={
												<div className="quotify-table-loading-inline">
													<Spinner />
												</div>
											}
										/>
									</div>

									{/* Mobile Card View */}
									<div className="quotify-mobile-cards">
										{quotations.data.map((row) => (
											<div key={row.id} className="quotify-mobile-card">
												<div className="quotify-mobile-card__header">
													<button
														className="quotify-mobile-card__title"
														onClick={() => {
															if (isAdmin()) {
																navigate(
																	`${getRoutePath()}admin.php?page=quotify&id=${row.id}&action=view`
																);
															} else {
																navigate(`view-quote/${row.id}`);
															}
														}}
														type="button"
													>
														<span dangerouslySetInnerHTML={{ __html: sliceString(row.title) }} />
													</button>
													<QuickActions
														quotation={row}
														onUpdate={handleActionComplete}
														onEmail={handleActionComplete}
														onDelete={handleActionComplete}
													/>
												</div>
												<div className="quotify-mobile-card__body">
													<div className="quotify-mobile-card__row">
														<span className="quotify-mobile-card__label">{__('Author', 'quotify')}</span>
														<span className="quotify-mobile-card__value">
															{row.author_name || __('Guest', 'quotify')}
														</span>
													</div>
													<div className="quotify-mobile-card__row">
														<span className="quotify-mobile-card__label">{__('Date', 'quotify')}</span>
														<span className="quotify-mobile-card__value">{row.date}</span>
													</div>
													<div className="quotify-mobile-card__row">
														<span className="quotify-mobile-card__label">{__('Status', 'quotify')}</span>
														<span className="quotify-mobile-card__value">
															<TableStatusBadge status={row.status} />
														</span>
													</div>
												</div>
											</div>
										))}
									</div>
								</>
							) : (
								<EmptyState type={getEmptyStateType()} />
							)}
						</div>
					</div>
				</div>
			</div>
		</>
	);
};

export default QuotationsList;
