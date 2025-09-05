import TopBar from '@Components/TopBar';
import { __ } from '@wordpress/i18n';
import BugsIcon from '@src/images/bugs.svg';
import CogIcon from '@src/images/cog.svg';
import LikeIcon from '@src/images/like.svg';

import GitHubIcon from '@src/images/github.svg';
import WordPressIcon from '@src/images/wordpress.svg';
import FiverrIcon from '@src/images/fiverr.svg';
import LinkedinIcon from '@src/images/linkedin.svg';

import './index.scss';

function index() {
	return (
		<>
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">
							{__('Help', 'quotify')}
						</h4>
						<h1 className="screen-reader-text">
							Product Quotation For WooCommerce Help Page
						</h1>
					</div>
				)}
			/>
			{/* Intro Section */}
			<div className="quotify-help-intro quotify-help-page-header quotify-content-wrap">
				<div className="quotify-card">
					<div className="main-intro">
						<h2 className="intro-title">
							{__(
								'Need help with Product Quotation for WooCommerce?',
								'quotify'
							)}
						</h2>
						<p className="text message intro-description">
							{__(
								'Welcome to the help center for Product Quotation for WooCommerce. Here you can find everything you need to get started, troubleshoot issues,  and make the most out of this plugin. Whether you are looking for documentation,  need direct support, want to report a bug, or simply share your feedback — this page is your one-stop hub.',
								'quotify'
							)}
						</p>
						<p className="text message">
							Product Quotation for WooCommerce is built and
							maintained by Mahafuz. I’m passionate about creating
							tools that make WooCommerce stores more powerful and
							user-friendly. You can connect with me and follow my
							work here:
						</p>
						<div className="quotify-links">
							<a
								target="_blank"
								href="https://github.com/mahafuz"
								className="quotify-social"
							>
								<img src={GitHubIcon} alt="mahafuz-github" />
							</a>
							<a
								target="_blank"
								href="https://www.linkedin.com/in/mahafuz"
								className="quotify-social"
							>
								<img src={LinkedinIcon} alt="mahafuz-github" />
							</a>
							<a
								target="_blank"
								href="https://www.linkedin.com/in/mahafuz"
								className="quotify-social"
							>
								<img src={FiverrIcon} alt="mahafuz-github" />
							</a>
							<a
								target="_blank"
								href="https://mahafuz.com"
								className="quotify-social"
							>
								<img
									src={WordPressIcon}
									alt="mahafuz-wordpress-official"
								/>
							</a>
						</div>
					</div>
					<div className="intro-video">
						<iframe
							src="https://www.youtube.com/embed/TrQir7HQYs8?si=kJ_EjLsQlvLdW2He"
							title="Quotify Introduction Video"
							frameborder="0"
							allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
							referrerpolicy="allowed"
							allowfullscreen
						></iframe>
					</div>
				</div>
			</div>
			<div className="quotify-help-page-wrapper quotify-content-wrap">
				{/* Documentation */}
				<div className="help-block quotify-card">
					<img
						className="quotify-card-thumbnail"
						src={CogIcon}
						alt="Documentation"
					/>
					<h3 className="quotify-card-title">
						Looking for Documentation?
					</h3>
					<p className="message">
						Explore our step-by-step guides and learn how to get the
						most out of Product Quotation for WooCommerce.
					</p>
					<a
						target="_blank"
						href="#"
						className="button button-primary"
					>
						Read the Documentation
					</a>
				</div>

				{/* Support */}
				<div className="help-block quotify-card">
					<img
						className="quotify-card-thumbnail"
						src={CogIcon}
						alt="Support"
					/>
					<h3 className="quotify-card-title">Need Assistance?</h3>
					<p className="message">
						Our expert support team is ready to help you with any
						issue or question.
					</p>
					<a
						target="_blank"
						href="#"
						className="button button-primary"
					>
						Get Support
					</a>
				</div>

				{/* Bug Reporting */}
				<div className="help-block quotify-card">
					<img
						className="quotify-card-thumbnail"
						src={BugsIcon}
						alt="Bug Reporting"
					/>
					<h3 className="quotify-card-title">Found a Bug?</h3>
					<p className="message">
						Help us improve the plugin by reporting issues directly
						on GitHub. We’ll address them as quickly as possible.
					</p>
					<a
						target="_blank"
						href="https://github.com/mahafuz/product-quotation-for-woocommerce/issues/new"
						className="button button-primary"
					>
						Report on GitHub
					</a>
				</div>

				{/* Customization */}
				<div className="help-block quotify-card">
					<img
						className="quotify-card-thumbnail"
						src={CogIcon}
						alt="Customization"
					/>
					<h3 className="quotify-card-title">
						Want Custom Features?
					</h3>
					<p className="message">
						We’d love to hear your ideas for new integrations or
						custom solutions tailored to your store.
					</p>
					<a
						target="_blank"
						href="#"
						className="button button-primary"
					>
						Request Customization
					</a>
				</div>

				{/* Reviews */}
				<div className="help-block quotify-card">
					<img
						className="quotify-card-thumbnail"
						src={LikeIcon}
						alt="Review Plugin"
					/>
					<h3 className="quotify-card-title">Enjoying the Plugin?</h3>
					<p className="message">
						Your feedback means a lot to us and helps the plugin
						grow. Share your experience on WordPress.org.
					</p>
					<a
						target="_blank"
						href="https://wordpress.org/support/plugin/product-quotation-for-woocommerce/reviews/#new-post"
						className="button button-primary"
					>
						Leave a Review
					</a>
				</div>

				{/* Optional Developer Section */}
				<div className="help-block quotify-card">
					<h3 className="quotify-card-title">About the Developer</h3>
					<p className="message">
						Product Quotation for WooCommerce is built and
						maintained by Mahafuz. I’m passionate about creating
						tools that make WooCommerce stores more powerful and
						user-friendly.
					</p>
					<a
						target="_blank"
						href="#"
						className="button button-primary"
					>
						Visit My Website
					</a>
				</div>
			</div>
		</>
	);
}

export default index;
