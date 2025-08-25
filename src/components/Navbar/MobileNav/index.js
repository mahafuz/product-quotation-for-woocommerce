import {
	Stack,
	useColorModeValue,
} from '@chakra-ui/react'

import MobileNavItem from '../MobileNavItem';

const MobileNav = () => {
	const adminmenu = window.PqfwGlobal.menu;

	return (
		<Stack bg={useColorModeValue('white', 'gray.800')} p={4} display={{ md: 'none' }}>
			{Object.entries(JSON.parse(adminmenu)).map(([key, navItem], index) => (
				<MobileNavItem key={index} href={key} label={navItem.label} {...navItem} />
			))}
		</Stack>
	)
}

export default MobileNav;