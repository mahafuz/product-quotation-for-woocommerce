import { useState } from 'react';
import { useDispatch } from 'react-redux';
import { __ } from '@wordpress/i18n';

import { FETCH_ADDONS } from '@Redux/types/addons.types';

import {
	Grid,
	GridItem,
	Box,
	Heading,
	Text,
	Img,
	Flex,
	Center,
	useColorModeValue,
	HStack,
	Image,
	VStack,
	Switch,
	IconButton,
} from '@chakra-ui/react';

import {
	BsArrowUpRight,
	BsHeartFill,
	BsHeart,
	BsFillGearFill,
} from 'react-icons/bs';

import {
	fireNotify,
	getAllAddons,
	getAddonActiveStatus,
	addons as allAddons,
	makeRequest,
} from '@Utils/helper';

function index( { addon } ) {
	const dispatch = useDispatch();
	const cardBg = useColorModeValue( 'gray.100', 'gray.700' );
	const [ status, setStatus ] = useState(
		getAddonActiveStatus( addon.name )
	);

	const handleChange = ( e, addon ) => {
		const value = e.target.checked;

		setStatus( value );

		makeRequest( {
			action: 'quotify/addons/save',
			addon: addon.name,
			status: value,
		} ).then( ( response ) => {
			if ( response.data?.success ) {
				dispatch( {
					type: FETCH_ADDONS,
					payload: response.data?.data,
				} );
			} else {
				fireNotify(
					sprintf(
						// translators: %s: AddonName
						__( '%s Addon Failed to saved.', 'pqfw' ),
						addon.label
					),
					'error'
				);
			}
		} );
	};

	return (
		<GridItem key={ addon.id }>
			<Box
				bg={ cardBg }
				rounded="xl"
				shadow="md"
				overflow="hidden"
				display="flex"
				flexDirection="column"
				justifyContent="space-between"
				h="100%"
			>
				{ /* Main content */ }
				<VStack spacing={ 3 } p={ 4 } flex="1">
					<Image
						src={ addon.icon }
						alt={ addon.name }
						boxSize="50px"
						objectFit="contain"
					/>
					<Text fontWeight="bold">{ addon.label }</Text>
				</VStack>

				{ /* Footer */ }
				<Flex
					justify="space-between"
					align="center"
					p={ 3 }
					borderTop="1px solid"
					borderColor={ useColorModeValue( 'gray.300', 'gray.600' ) }
					bg={ useColorModeValue( 'gray.200', 'gray.800' ) }
				>
					{ /* Left: Switch */ }
					<HStack>
						<Switch
							colorScheme="teal"
							size="md"
							isChecked={ status }
							onChange={ ( e ) => handleChange( e, addon ) }
						/>
					</HStack>

					{ /* Right: Settings Button */ }
					<IconButton
						aria-label={ `Settings for ${ addon.name }` }
						icon={ <BsFillGearFill /> }
						size="sm"
						variant="ghost"
					/>
				</Flex>
			</Box>
		</GridItem>
	);
}

export default index;
