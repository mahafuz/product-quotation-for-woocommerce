import { Link } from 'react-router-dom';
import { getRoutePath } from '@Utils/global';
import { useQuery } from '@Utils/spa';
import './index.scss';

const Navigation = ({ currentPath }) => {
	const adminmenu = window?.QUOTIFY_CONFIG?.menu;
	const query = useQuery();
	const page = query.get('page');

	return (
		<div className="quotify-backend-dashboard-navigation">
			{/* {Object.entries(JSON.parse(adminmenu)).map(
				([ key, navItem], index) => (
					<Link
						key={navItem.label}
						to={`${getRoutePath()}admin.php?page=${key}`}
						className={
							page === key
								? 'current navigation-item'
								: 'navigation-item'
						}
					>
						{navItem.title}
					</Link>
				)
			)} */}
		</div>
	);
};

export default Navigation;