import { __ } from '@wordpress/i18n';
import './index.scss';

const StatCard = ({ icon, label, value, trend, onClick, variant = 'default' }) => {
	const handleClick = () => {
		if (onClick) {
			onClick();
		}
	};

	return (
		<div
			className={`quotify-stat-card ${onClick ? 'quotify-stat-card--clickable' : ''} quotify-stat-card--${variant}`}
			onClick={handleClick}
			role={onClick ? 'button' : undefined}
			tabIndex={onClick ? 0 : undefined}
		>
			<div className="quotify-stat-card__icon">
				{icon}
			</div>
			<div className="quotify-stat-card__content">
				<div className="quotify-stat-card__value">{value}</div>
				<div className="quotify-stat-card__label">{label}</div>
				{trend && (
					<div className={`quotify-stat-card__trend ${trend.type}`}>
						{trend.type === 'up' && (
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
								<polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
								<polyline points="17 6 23 6 23 12" />
							</svg>
						)}
						{trend.type === 'down' && (
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
								<polyline points="23 18 13.5 8.5 8.5 13.5 1 6" />
								<polyline points="17 18 23 18 23 12" />
							</svg>
						)}
						<span>{trend.label}</span>
					</div>
				)}
			</div>
		</div>
	);
};

export default StatCard;
