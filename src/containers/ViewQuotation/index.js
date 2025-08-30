import { useState, useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';

import { getQuote } from '@Redux/actions/quotations.actions';

function index({ id }) {
    const dispatch = useDispatch();
    const quotation = useSelector( state => state.quotation );

	const [loading, setLoading] = useState(false);
	const [values, setValues] = useState({});

	useEffect(() => {
		if ( ( ! quotation?.data && id) ) {
			setLoading(true);
			dispatch(getQuote(id)).then((res) => setLoading(false));
		} else {
			setLoading(false);
		}
	}, [id, quotation?.data]);

	return (
        <>
            <h1>Post</h1>
			<code>{JSON.stringify(values)}</code>
        </>
    );
}

export default index;
