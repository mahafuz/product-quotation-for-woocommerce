import {
	Stack,
} from '@wordpress/components'

import MobileNavItem from '../MobileNavItem';

const MobileNav = () => {
	const adminmenu = window.PqfwGlobal.menu;

	return (
		<Stack p={4} display={{ md: 'none' }}>
			{Object.entries(JSON.parse(adminmenu)).map(([key, navItem], index) => (
				<MobileNavItem key={index} href={key} label={navItem.label} {...navItem} />
			))}
		</Stack>
	)
}

export default MobileNav;