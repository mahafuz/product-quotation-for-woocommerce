import { Link } from 'react-router-dom';
import { route_path, useQuery } from '@Utils/helper';
import './index.scss';

const Navigation = ({ currentPath }) => {
	const adminmenu = window.PqfwGlobal.menu;
	const query = useQuery();
	const page = query.get('page');

	return (
		<div className="quotify-backend-dashboard-navigation">
			{/* {Object.entries(JSON.parse(adminmenu)).map(
				([ key, navItem], index) => (
					<Link
						key={navItem.label}
						to={`${route_path}admin.php?page=${key}`}
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