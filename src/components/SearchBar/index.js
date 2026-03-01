import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import './index.scss';

const DATE_OPTIONS = [
	{ value: 'all', label: __('All Time', 'quotify') },
	{ value: 'today', label: __('Today', 'quotify') },
	{ value: 'week', label: __('This Week', 'quotify') },
	{ value: 'month', label: __('This Month', 'quotify') },
	{ value: 'quarter', label: __('This Quarter', 'quotify') },
	{ value: 'year', label: __('This Year', 'quotify') },
];

const SearchBar = ({ onSearch, onDateChange, searching }) => {
	const [searchTerm, setSearchTerm] = useState('');
	const [showDateFilter, setShowDateFilter] = useState(false);
	const [selectedDate, setSelectedDate] = useState(DATE_OPTIONS[0]);

	// Debounced search
	useEffect(() => {
		const timeoutId = setTimeout(() => {
			onSearch(searchTerm);
		}, 300);

		return () => clearTimeout(timeoutId);
	}, [searchTerm, onSearch]);

	const handleDateChange = (option) => {
		setSelectedDate(option);
		setShowDateFilter(false);
		onDateChange(option.value);
	};

	const handleClearSearch = () => {
		setSearchTerm('');
		onSearch('');
	};

	return (
		<div className="quotify-search-bar">
			<div className="quotify-search-bar__input">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
					<circle cx="11" cy="11" r="8" />
					<path d="m21 21-4.35-4.35" />
				</svg>
				<input
					type="text"
					placeholder={__('Search quotations...', 'quotify')}
					value={searchTerm}
					onChange={(e) => setSearchTerm(e.target.value)}
					disabled={searching}
				/>
				{searchTerm && (
					<button
						className="quotify-search-bar__clear"
						onClick={handleClearSearch}
						type="button"
					>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
							<line x1="18" y1="6" x2="6" y2="18" />
							<line x1="6" y1="6" x2="18" y2="18" />
						</svg>
					</button>
				)}
			</div>

			<div className="quotify-search-bar__filters">
				<div className="quotify-date-filter">
					<button
						className="quotify-date-filter__button"
						onClick={() => setShowDateFilter(!showDateFilter)}
						type="button"
					>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
							<rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
							<line x1="16" y1="2" x2="16" y2="6" />
							<line x1="8" y1="2" x2="8" y2="6" />
							<line x1="3" y1="10" x2="21" y2="10" />
						</svg>
						<span>{selectedDate.label}</span>
						<svg
							width="14"
							height="14"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							strokeWidth="2"
							className={`quotify-date-filter__arrow ${showDateFilter ? 'quotify-date-filter__arrow--open' : ''}`}
						>
							<polyline points="6 9 12 15 18 9" />
						</svg>
					</button>

					{showDateFilter && (
						<div className="quotify-date-filter__dropdown">
							{DATE_OPTIONS.map((option) => (
								<button
									key={option.value}
									className={`quotify-date-filter__option ${
										selectedDate.value === option.value ? 'quotify-date-filter__option--active' : ''
									}`}
									onClick={() => handleDateChange(option)}
									type="button"
								>
									{option.label}
									{selectedDate.value === option.value && (
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
											<polyline points="20 6 9 17 4 12" />
										</svg>
									)}
								</button>
							))}
						</div>
					)}
				</div>
			</div>
		</div>
	);
};

export default SearchBar;
