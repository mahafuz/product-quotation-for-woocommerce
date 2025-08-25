import { Link } from 'react-router-dom';
import {
	Box,
	Stack,
	Popover,
	PopoverTrigger,
	PopoverContent,
	useColorModeValue,
} from '@chakra-ui/react'

import { route_path } from '@Utils/helper';

import DesktopSubNav from '../DesktopSubNav';

const DesktopNav = () => {
	const linkColor = useColorModeValue('gray.600', 'gray.200')
	const linkHoverColor = useColorModeValue('gray.800', 'white')
	const popoverContentBgColor = useColorModeValue('white', 'gray.800')
	const adminmenu = window.PqfwGlobal.menu;

	return (
		<Stack direction={'row'} spacing={4} align={'center'}>
			{Object.entries(JSON.parse(adminmenu)).map(([key, navItem], index) => (
				<Box key={navItem.label}>
					<Popover trigger={'hover'} placement={'bottom-start'}>
						<PopoverTrigger>
							<Box
								as="a"
								p={2}
								fontSize={'sm'}
								fontWeight={500}
								color={linkColor}
								_hover={{
									textDecoration: 'none',
									color: linkHoverColor,
								}}>
								<Link to={`${route_path}admin.php?page=${key}`}>{navItem.title}</Link>
							</Box>
						</PopoverTrigger>

						{navItem.children && (
							<PopoverContent
								border={0}
								boxShadow={'xl'}
								bg={popoverContentBgColor}
								p={4}
								rounded={'xl'}
								minW={'sm'}>
								<Stack>
									{navItem.children.map((child) => (
										<DesktopSubNav key={child.label} {...child} />
									))}
								</Stack>
							</PopoverContent>
						)}
					</Popover>
				</Box>
			))}
		</Stack>
	)
}

export default DesktopNav;