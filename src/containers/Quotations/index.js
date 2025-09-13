import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import {
	fetchAllQuotations,
	updateCurrentPage,
} from '@Redux/actions/quotations.actions';
import DataTable from 'react-data-table-component';
import { __ } from '@wordpress/i18n';
import { Button, Spinner } from '@wordpress/components';

import { RiDeleteBin6Line, RiEditLine } from 'react-icons/ri';
import { MdOutlineRestore } from 'react-icons/md';

import TopBar from '@Components/TopBar';

import './index.scss';

const statusArray = [
	{
		label: __('All', 'quotify'),
		value: 'all',
	},
	{
		label: __('Pending', 'quotify'),
		value: 'pending',
	},
	{
		label: __('Trash', 'quotify'),
		value: 'trash',
	},
];

import BulkAction from '@Components/BulkAction';

import {
	is_admin,
	admin_url,
	is_pro,
	route_path,
	sliceString,
	moveCourseToTrash,
} from '@Utils/helper';

import {
	moveQuoteToTrash,
	deleteQuote,
	restoreQuote,
} from '../../redux/actions/quotations.actions';

function index() {
	const dispatch = useDispatch();
	const navigate = useNavigate();
	const quotations = useSelector((state) => state.quotations);

	const [bulkActionData, setBulkActionData] = useState({});
	const [fetching, setFetching] = useState(false);
	const [status, setStatus] = useState(
		quotations.status ? quotations.status : 'all'
	);

	const restoreHandler = (id) => {
		dispatch(restoreQuote(id));
	};

	const deleteHandler = (item) => {
		if (item.status !== 'trash') {
			dispatch(
				moveQuoteToTrash({
					ID: item.id ? item.id : item.ID,
				})
			);
		} else if (
			confirm(
				__(
					'Are you sure you want to permanently delete selected quotes?',
					'quotify'
				)
			)
		) {
			dispatch(
				deleteQuote({
					ID: item.id ? item.id : item.ID,
				})
			);
		}
	};

	const bulkActionHandler = (selectedRows, bulkAction) => {
		console.log({ selectedRows, bulkAction });
		if (status !== 'trash') {
			selectedRows.forEach((item) => {
				dispatch(
					moveQuoteToTrash({
						ID: item.id ? item.id : item.ID,
					})
				);
			});
		} else if (bulkAction.value === 'restore') {
			selectedRows.forEach((item) => {
				dispatch(restoreQuote(item));
			});
		} else {
			selectedRows.forEach((item) => {
				dispatch(
					deleteQuote({
						ID: item.id ? item.id : item.ID,
					})
				);
			});
		}
	};

	const handleTableDataFetch = (page = 1, perPage = 10) => {
		setFetching(true);
		dispatch(fetchAllQuotations(status, page, perPage)).then(() => {
			setFetching(false);
		});
	};

	const handlePageChange = (page) => {
		dispatch(updateCurrentPage(page));
		handleTableDataFetch(page);
	};

	const handleItemsPage = (itemsPerPage, page) => {
		dispatch(updateCurrentPage(page));
		handleTableDataFetch(page, itemsPerPage);
	};

	useEffect(() => {
		if (
			!quotations.data ||
			(quotations.data && quotations.data?.length < 0) ||
			status
		) {
			setFetching(true);
			dispatch(fetchAllQuotations(status))
				.then((res) => {
					setFetching(false);
				})
				.catch((error) => {
					setFetching(false);
				});
		}
	}, [status]);

	const publishAction =
		status !== 'trash'
			? [{ value: 'trash', label: __('Move to Trash', 'quotify') }]
			: [];

	const trashAction =
		status === 'trash'
			? [
					{ value: 'restore', label: __('Restore', 'quotify') },
					{
						value: 'delete',
						label: __('Delete Permanently', 'quotify'),
					},
				]
			: [];

	const bulkOptions = [...publishAction, ...trashAction];

	const subHeaderComponentMemo = React.useMemo(() => {
		const searchHandler = (value) => {
			setFetching(true);
			dispatch(fetchAllQuotations(status, 1, 10, value)).then(() => {
				setFetching(false);
			});
		};

		return (
			<>
				<div className="quotify-table-header-action__left">
					<div className="quotify-table-filters">
						{statusArray.map((item, index) => (
							<span
								role="presentation"
								className={`quotify-table-filters__options ${
									status === item.value &&
									'quotify-table-filters__options--active'
								}`}
								key={index}
								onClick={() => setStatus(item.value)}
							>
								{item.label}
							</span>
						))}
					</div>
				</div>
				<div className="quotify-table-header-action__right">
					<BulkAction
						data={bulkActionData}
						applyActionHandler={bulkActionHandler}
						confirmMessage={
							status === 'trash'
								? __(
										'Are you sure you want to permanently delete selected courses?',
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
			</>
		);
	}, [bulkActionData, status, quotations]);

	const columns = [
		{
			name: __('Title', 'quotify'),
			sortable: true,
			cell: (row) => {
				return (
					<div className="quotify-table-title-wrap">
						<div className="quotify-table-title">
							<Link
								to={`${
									is_admin
										? `${route_path}admin.php?page=pqfw-product-quotations&id=${row.id}&action=view`
										: `view-quote/${row.id}`
								}`}
							>
								<span
									className="quotify-table-title"
									dangerouslySetInnerHTML={{
										__html: sliceString(row.title),
									}}
								></span>
							</Link>
						</div>
					</div>
				);
			},
		},
		{
			name: __('Author', 'quotify'),
			sortable: true,
			cell: (row) => <span>{row.author_name}</span>,
		},
		{
			name: __('Date', 'quotify'),
			sortable: true,
			cell: (row) => <div>{row.date}</div>,
		},
		{
			name: __('Status', 'quotify'),
			sortable: true,
			cell: (row) => <span>{row.status}</span>,
		},
		{
			name: __('Action', 'quotify'),
			sortable: true,
			cell: (row) => {
				return (
					<div className="quotify-table-item-control">
						{'trash' !== row.status ? (
							<Button
								type="button"
								preset="purple"
								onClick={() => {
									if (is_admin) {
										navigate(
											`${route_path}admin.php?page=pqfw-product-quotations&id=${row.id}&action=view`
										);
									} else {
										navigate(`view-/${row.id}`);
									}
								}}
								iconPosition="left"
								size="sm"
							>
								<RiEditLine />
							</Button>
						) : (
							<Button
								type="button"
								preset="purple"
								onClick={() => restoreHandler(row)}
								iconPosition="left"
								size="sm"
							>
								<MdOutlineRestore />
							</Button>
						)}
						<Button
							type="button"
							preset="purple"
							onClick={() => deleteHandler(row)}
							iconPosition="left"
							size="sm"
						>
							<RiDeleteBin6Line />
						</Button>
					</div>
				);
			},
		},
	];

	return (
		<>
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">
							{__('Dashboard', 'quotify')}
						</h4>
					</div>
				)}
			/>

			<div className="quotify-dashboard-wrapper quotify-content-wrap">
				<div className="quotify-quotations-list">
					<DataTable
						title={``}
						className="quotify-list-table"

						// Column.
						columns={columns}

						// Rows.
						selectableRows
						onSelectedRowsChange={(e) => setBulkActionData(e)}

						// Data.
						data={quotations.data}
						progressPending={fetching}
						progressComponent={<h1>Loading quotations...</h1>}

						// Subheader.
						subHeader
						persistTableHead
						subHeaderComponent={subHeaderComponentMemo}

						// Pagination.
						pagination
						paginationServer
						onChangePage={handlePageChange} 
						paginationTotalRows={quotations?.totalItems}
						onChangeRowsPerPage={handleItemsPage}
						paginationResetDefaultPage={true}
						paginationDefaultPage={quotations.currentPage}
					/>
				</div>
			</div>
		</>
	);
}

export default index;
