import React, { useEffect } from 'react';
import { useSelector } from 'react-redux';
import { Link, useLocation } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import {
	route_path,
	toplevel_menu_icon_url,
	toplevel_menu_title,
} from '@Utils/helper';

import MenuItem from './MenuItem';

function useQuery() {
	const { search } = useLocation();

	return React.useMemo(() => new URLSearchParams(search), [search]);
}
const AdminMenu = () => {
	const adminmenu = useSelector( state => state.adminmenu );
	const location = useQuery();
	const page = location.get('page');
	const path = location.get('path');

	useEffect(() => {
		document.title = adminmenu[page]?.title + ' - ' + toplevel_menu_title;
	}, [page]);

	return (
		<React.Fragment>
			<Link
				to={`${route_path}admin.php?page=quotify`}
				className="wp-has-submenu wp-has-current-submenu wp-menu-open menu-top toplevel_page_quotify menu-top-last"
				aria-haspopup="false"
			>
				<div className="wp-menu-arrow">
					<div></div>
				</div>
				<div
					className="wp-menu-image svg"
					style={{
						backgroundImage: `url('${toplevel_menu_icon_url}')`,
					}}
					aria-hidden="true"
				>
					<br />
				</div>
				<div className="wp-menu-name">{toplevel_menu_title}</div>
			</Link>
			<ul className="wp-submenu wp-submenu-wrap">
				<li className="wp-submenu-head" aria-hidden="true">
					{toplevel_menu_title}
				</li>
				{Object.entries(adminmenu).map(([key, item], index) => {
					return (
						<MenuItem
							className={page === key ? 'current' : ''}
							key={index}
							parent={key}
							currentPath={path}
							subMenuItems={item.sub_items}
						>
							<Link to={`${route_path}admin.php?page=${key}`}>
								{item.title}
								{item?.sub_items && (
									<span className="academy-icon academy-icon--angle-right"></span>
								)}
							</Link>
						</MenuItem>
					);
				})}
			</ul>
		</React.Fragment>
	);
};

export default AdminMenu;
