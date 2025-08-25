import React from 'react';
import { Link } from 'react-router-dom';
import { route_path } from '@Utils/helper';

export default function MenuItem(props) {
	const { className, children, parent, currentPath, subMenuItems } = props;
	return (
		<React.Fragment>
			<li className={className}>
				{children}
				{className === 'current' && subMenuItems && (
					<>
						<ul className="wp-submenu">
							{subMenuItems.map((item, index) => {
								return (
									<li
										className={
											(!item.slug && !currentPath) ||
											currentPath === item.slug
												? 'current'
												: ''
										}
										key={index}
									>
										<Link
											to={`${route_path}admin.php?page=${parent}${
												item.slug
													? '&path=' + item.slug
													: ''
											}`}
										>
											{item.title}
										</Link>
									</li>
								);
							})}
						</ul>
					</>
				)}
			</li>
		</React.Fragment>
	);
}
