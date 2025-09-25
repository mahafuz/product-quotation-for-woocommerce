import { useState, useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { __ } from '@wordpress/i18n';
import { getQuote } from '@Redux/actions/quotations.actions';
import TopBar from '@Components/TopBar';

import { route_path } from '@Utils/helper';

import './index.scss'; // keep this if you're adding SCSS or CSS in the same file
import { useNavigate } from 'react-router-dom';

function Index({ id }) {
	const dispatch = useDispatch();
	const navigate = useNavigate();
	const quotation = useSelector((state) => state.quotation);

	const [loading, setLoading] = useState(false);

	useEffect(() => {
		setLoading(true);
		dispatch(getQuote(id)).then((response) => {
			if (response?.data?.data?.not_found) {
				navigate(
					`${route_path}admin.php?page=quotify`
				);
			}

			setLoading(false);
		});
	}, [id, quotation?.data]);

	const meta = quotation?.meta || {};

	return (
		<>
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">
							{__('Quote Details', 'quotify')}
						</h4>
					</div>
				)}
			/>

			<div className="quotify-content-wrap quote-container">
				<div className="quotify-card">
					<h2 className={`quotify-card-title`}>
						{quotation?.title || 'Quote'}
					</h2>
					<p>
						<strong>Date:</strong> {quotation?.date}
					</p>
					<p>
						<strong>Status:</strong> {quotation?.status}
					</p>
				</div>

				<div className="quotify-card">
					<h3 className={`quotify-card-title`}>Customer Details</h3>
					<p>
						<strong>Name:</strong> {meta.pqfw_customer_name}
					</p>
					<p>
						<strong>Email:</strong> {meta.pqfw_customer_email}
					</p>
					{meta.pqfw_customer_phone && (
						<p>
							<strong>Phone:</strong> {meta.pqfw_customer_phone}
						</p>
					)}
					<p>
						<strong>Subject:</strong> {meta.pqfw_customer_subject}
					</p>
					{meta.pqfw_customer_comments && (
						<p>
							<strong>Comments:</strong>{' '}
							{meta.pqfw_customer_comments}
						</p>
					)}
				</div>

				<div className="quotify-card">
					<h3 className={`quotify-card-title`}>Products</h3>
					{Array.isArray(meta.pqfw_products_info) &&
						meta.pqfw_products_info.map(
							(
								{ name, price, img, link, quantity, message },
								index
							) => (
								<div className="product-item" key={index}>
									<img src={img} alt={name} />
									<div className="product-info">
										<a
											href={link}
											target="_blank"
											rel="noreferrer"
										>
											<h4>{name}</h4>
										</a>
										<p>
											<strong>Price:</strong>{' '}
											<span
												dangerouslySetInnerHTML={{
													__html: price,
												}}
											/>
										</p>
										<p>
											<strong>Quantity:</strong>{' '}
											{quantity}
										</p>
										{message && (
											<p>
												<strong>Message:</strong>{' '}
												{message}
											</p>
										)}
									</div>
								</div>
							)
						)}
				</div>
			</div>
		</>
	);
}

export default Index;
