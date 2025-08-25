import {
	Box,
	Flex,
	Text,
	IconButton,
	Button,
	Stack,
	Collapse,
	useColorModeValue,
	useBreakpointValue,
	useDisclosure,
} from '@chakra-ui/react'

import {
	MdMenu,
	MdClose,
} from 'react-icons/md'

import { Image } from "@chakra-ui/react"

import { version, route_path } from '@Utils/helper'


import DesktopNav from './DesktopNav';
import MobileNav from './MobileNav';
import LogoIcon from "./../../images/docs.svg";
import { Link } from 'react-router-dom';

export default function Navbar() {
	const { isOpen, onToggle } = useDisclosure()

	return (
		<Box>
			<Flex
				bg={useColorModeValue('white', 'gray.800')}
				color={useColorModeValue('gray.600', 'white')}
				minH={'60px'}
				py={{ base: 2 }}
				px={{ base: 4 }}
				borderBottom={1}
				borderStyle={'solid'}
				borderColor={useColorModeValue('gray.200', 'gray.900')}
				align={'center'}>
				<Flex
					flex={{ base: 1, md: 'auto' }}
					ml={{ base: -2 }}
					display={{ base: 'flex', md: 'none' }}>
					<IconButton
						onClick={onToggle}
						icon={isOpen ? <MdClose w={3} h={3} /> : <MdMenu w={5} h={5} />}
						variant={'ghost'}
						aria-label={'Toggle Navigation'}
					/>
				</Flex>
				<Flex flex={{ base: 1 }} justify={{ base: 'center', md: 'start' }}>
					<Text
						textAlign={useBreakpointValue({ base: 'center', md: 'left' })}
						fontFamily={'heading'}
						color={useColorModeValue('gray.800', 'white')}>
						<Link to={`${route_path}admin.php?page=pqfw-product-quotations`}>
							<Image src={LogoIcon} alt="" w={35} h={35}/>
						</Link>
					</Text>

					<Flex display={{ base: 'center', md: 'flex' }} ml={10}>
						<DesktopNav />
					</Flex>
				</Flex>

				<Stack
					flex={{ base: 1, md: 0 }}
					justify={'flex-end'}
					direction={'row'}
					spacing={6}>
					<Button as={'a'} fontSize={'sm'} fontWeight={400}>
						<span>{version}</span>
					</Button>
					<Button
						as={'a'}
						display={{ base: 'none', md: 'inline-flex' }}
						fontSize={'sm'}
						fontWeight={600}
						color={'white'}
						bg={'pink.400'}
						href={'#'}
						_hover={{
							bg: 'pink.300',
						}}>
						Sign Up
					</Button>
				</Stack>
			</Flex>

			<Collapse in={isOpen} animateOpacity>
				<MobileNav />
			</Collapse>
		</Box>
	)
}




