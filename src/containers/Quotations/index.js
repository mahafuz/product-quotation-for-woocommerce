import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import { fetchAllQuotations } from '@Redux/actions/quotations.actions';
import DataTable from 'react-data-table-component';
import { __ } from '@wordpress/i18n';
import { Flex, Button, Heading, Grid, GridItem } from '@chakra-ui/react';

import { RiDeleteBin6Line, RiEditLine } from 'react-icons/ri';
import { MdOutlineRestore } from 'react-icons/md';

const statusArray = [
	{
		label: __('All', 'pqfw'),
		value: 'all',
	},
	// {
	// 	label: __('Publish', 'pqfw'),
	// 	value: 'publish',
	// },
	// {
	// 	label: __('Pending', 'pqfw'),
	// 	value: 'pending',
	// },
	{
		label: __('Trash', 'pqfw'),
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

	const [fetchStatus, setFetchingStatus] = useState(false);
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
					'Are you sure you want to permanently delete selected courses?',
					'pqfw'
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
		if (status !== 'trash') {
			// selectedRows.forEach((item) => {
			// 	dispatch(
			// 		moveCourseToTrash({
			// 			ID: item.id ? item.id : item.ID,
			// 		})
			// 	);
			// });
		} else if (bulkAction.value === 'restore') {
			// selectedRows.forEach((item) => {
			// 	dispatch(restoreCourse(item));
			// });
		} else {
			// selectedRows.forEach((item) => {
			// 	dispatch(
			// 		deleteCourse({
			// 			ID: item.id ? item.id : item.ID,
			// 		})
			// 	);
			// });
		}
	};

	useEffect(() => {
		if (
			!quotations.data ||
			(quotations.data && quotations.data?.quotations?.length < 0) ||
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
			setFetchingStatus(true);
			dispatch(fetchAllQuotations(status, 1, 10, value)).then(() => {
				setFetchingStatus(false);
			});
		};

		return (
			<Grid>
				<GridItem className="quotify-table-header-action__left">
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
				</GridItem>
				<GridItem className="quotify-table-header-action__right">
					<span>{quotations.data?.length} Items</span>
				</GridItem>
			</Grid>
		);
	}, [bulkActionData, status]);

	const columns = [
		{
			name: __('Title', 'pqfw'),
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
			name: __('Author', 'pqfw'),
			sortable: true,
			cell: (row) => <span>{row.author_name}</span>,
		},
		{
			name: __('Date', 'pqfw'),
			sortable: true,
			cell: (row) => (
				<div>
					<span>{moment(row.date).format('MMMM DD, YYYY')}</span>
					<br />
					<span className="quotify-table-time">
						{moment(row.date).format('h:mm A')}
					</span>
				</div>
			),
		},
		{
			name: __('Status', 'pqfw'),
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
											`${route_path}admin.php?page=pqfw-product-quotations&id=${row.id}&action=edit`
										);
									} else {
										navigate(`edit-/${row.id}`);
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
			<Heading>{__('Quotations', 'pqfw')}</Heading>
			<DataTable
				selectableRows
				persistTableHead
				onSelectedRowsChange={(e) => setBulkActionData(e)}
				progressPending={fetchStatus}
				progressComponent={<h1>Loading quotations...</h1>}
				paginationResetDefaultPage={false} // optionally, a hook to reset pagination to page 1
				subHeader
				subHeaderAlign={`left`}
				paginationTotalRows={quotations?.totalItems} //pagination
				paginationDefaultPage={quotations.currentPage}
				subHeaderComponent={subHeaderComponentMemo}
				columns={columns}
				data={quotations.data?.quotations}
			/>
		</>
	);
}

export default index;
