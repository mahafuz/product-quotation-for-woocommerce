import { Link } from "react-router-dom";
import {
	Box,
	Text,
	Stack,
	Collapse,
	Icon,
	useColorModeValue,
	useDisclosure,
} from '@chakra-ui/react'

import { route_path } from '@Utils/helper';

const MobileNavItem = ({ label, children, href }) => {
	const { isOpen, onToggle } = useDisclosure()

	return (
		<Stack spacing={4} onClick={children && onToggle}>
			<Box
				py={2}
				as="a"
				justifyContent="space-between"
				alignItems="center"
				_hover={{
					textDecoration: 'none',
				}}>
				<Text fontWeight={600} color={useColorModeValue('gray.600', 'gray.200')}>
					<Link to={`${route_path}admin.php?page=${href}`}>{label}</Link>
				</Text>
				{children && (
					<Icon
						as={MdKeyboardArrowDown}
						transition={'all .25s ease-in-out'}
						transform={isOpen ? 'rotate(180deg)' : ''}
						w={6}
						h={6}
					/>
				)}
			</Box>

			<Collapse in={isOpen} animateOpacity style={{ marginTop: '0!important' }}>
				<Stack
					mt={2}
					pl={4}
					borderLeft={1}
					borderStyle={'solid'}
					borderColor={useColorModeValue('gray.200', 'gray.700')}
					align={'start'}>
					{children &&
						children.map((child) => (
							<Box as="a" key={child.label} py={2}>
								<Link to={`${route_path}admin.php?page=${key}`}>{child.label}</Link>
							</Box>
						))}
				</Stack>
			</Collapse>
		</Stack>
	)
}

export default MobileNavItem;