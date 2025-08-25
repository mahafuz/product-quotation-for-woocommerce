import { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux';
import { fetchAllQuotations } from '@Redux/actions/quotations.actions';
import { Flex, Text } from '@chakra-ui/react'

function index() {
	const dispatch = useDispatch();
	const quotations = useSelector((state) => state.quotations);

	const [fetching, setFetching] = useState(false);
	const [status, setStatus] = useState(quotations.status ? quotations.status : 'all');

	useEffect(() => {
		if (!quotations.data || (quotations.data && quotations.data.length < 0) || status) {
			setFetching(true);
			dispatch(fetchAllQuotations(status))
				.then((res) => {
					console.log('Quotations fetched successfully', res);
					setFetching(false);
				})
				.catch((error) => {
					console.error('Error fetching quotations:', error);
					setFetching(false);
				});
		}
	}, [status]);

	return (
		<Flex gap={4}>
			<Text>1</Text>
			<Text>2</Text>
			<Text>3</Text>
			<Text>4</Text>
			<Text>5</Text>
		</Flex>	
	);
}

export default index