import TopBar from '@Components/TopBar';
import { __ } from '@wordpress/i18n';
import BugsIcon from '@src/images/bugs.svg';
import CogIcon from '@src/images/cog.svg';
import CustomizationIcon from '@src/images/customization.svg';
import LikeIcon from '@src/images/like.svg';
import GitHubIcon from '@src/images/github.svg';
import WordPressIcon from '@src/images/wordpress.svg';
import FiverrIcon from '@src/images/fiverr.svg';
import LinkedinIcon from '@src/images/linkedin.svg';

import './index.scss';

function HelpPage() {
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
                                'Welcome to the help center for Product Quotation for WooCommerce. Here you can find everything you need to get started, troubleshoot issues, and make the most out of this plugin. Whether you are looking for documentation, need direct support, want to report a bug, or simply share your feedback — this page is your one-stop hub.',
                                'quotify'
                            )}
                        </p>
                        <p className="text message">
                            {__(
                                'Product Quotation for WooCommerce is built and maintained by Mahafuz. I\'m passionate about creating tools that make WooCommerce stores more powerful and user-friendly. You can connect with me and follow my work here:',
                                'quotify'
                            )}
                        </p>
                        <div className="quotify-links">
                            <a
                                target="_blank"
                                rel="noopener noreferrer"
                                href="https://github.com/mahafuz"
                                className="quotify-social"
                            >
                                <img src={GitHubIcon} alt="GitHub" />
                            </a>
                            <a
                                target="_blank"
                                rel="noopener noreferrer"
                                href="https://www.linkedin.com/in/mahafuz"
                                className="quotify-social"
                            >
                                <img src={LinkedinIcon} alt="LinkedIn" />
                            </a>
                            <a
                                target="_blank"
                                rel="noopener noreferrer"
                                href="https://www.fiverr.com"
                                className="quotify-social"
                            >
                                <img src={FiverrIcon} alt="Fiverr" />
                            </a>
                            <a
                                target="_blank"
                                rel="noopener noreferrer"
                                href="https://mahafuz.com"
                                className="quotify-social"
                            >
                                <img src={WordPressIcon} alt="WordPress" />
                            </a>
                        </div>
                    </div>
                    {/* <div className="intro-video">
                        <iframe
                            src="https://www.youtube.com/embed/TrQir7HQYs8?si=kJ_EjLsQlvLdW2He"
                            title={__('Quotify Introduction Video', 'quotify')}
                            frameBorder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerPolicy="strict-origin-when-cross-origin"
                            allowFullScreen
                        ></iframe>
                    </div> */}
                </div>
            </div>
            
            {/* Help Section Grid */}
            <div className="quotify-help-page-wrapper quotify-content-wrap">
                {/* Documentation */}
                {/* <div className="quotify-help-block quotify-card">
                    <img
                        className="quotify-card-thumbnail"
                        src={CogIcon}
                        alt={__('Documentation', 'quotify')}
                    />
                    <h3 className="quotify-card-title">
                        {__('Looking for Documentation?', 'quotify')}
                    </h3>
                    <p className="message">
                        {__('Explore our step-by-step guides and learn how to get the most out of Product Quotation for WooCommerce.', 'quotify')}
                    </p>
                    <a
                        target="_blank"
                        rel="noopener noreferrer"
                        href="#"
                        className="button button-primary"
                    >
                        {__('Read the Documentation', 'quotify')}
                    </a>
                </div> */}

                {/* Support */}
                <div className="quotify-help-block quotify-card">
                    <img
                        className="quotify-card-thumbnail"
                        src={CogIcon}
                        alt={__('Support', 'quotify')}
                    />
                    <h3 className="quotify-card-title">
                        {__('Need Assistance?', 'quotify')}
                    </h3>
                    <p className="message">
                        {__('Our expert support team is ready to help you with any issue or question.', 'quotify')}
                    </p>
                    <a
                        target="_blank"
                        rel="noopener noreferrer"
                        href="https://wordpress.org/support/plugin/product-quotation-for-woocommerce/"
                        className="button button-primary"
                    >
                        {__('Get Support', 'quotify')}
                    </a>
                </div>

                {/* Bug Reporting */}
                <div className="quotify-help-block quotify-card">
                    <img
                        className="quotify-card-thumbnail"
                        src={BugsIcon}
                        alt={__('Bug Reporting', 'quotify')}
                    />
                    <h3 className="quotify-card-title">
                        {__('Found a Bug?', 'quotify')}
                    </h3>
                    <p className="message">
                        {__('Help us improve the plugin by reporting issues directly on GitHub. We\'ll address them as quickly as possible.', 'quotify')}
                    </p>
                    <a
                        target="_blank"
                        rel="noopener noreferrer"
                        href="https://github.com/mahafuz/product-quotation-for-woocommerce/issues/new"
                        className="button button-primary"
                    >
                        {__('Report on GitHub', 'quotify')}
                    </a>
                </div>

                {/* Customization */}
                <div className="quotify-help-block quotify-card">
                    <img
                        className="quotify-card-thumbnail"
                        src={CustomizationIcon}
                        alt={__('Customization', 'quotify')}
                    />
                    <h3 className="quotify-card-title">
                        {__('Want Custom Features?', 'quotify')}
                    </h3>
                    <p className="message">
                        {__('We\'d love to hear your ideas for new integrations or custom solutions tailored to your store.', 'quotify')}
                    </p>
                    <a
                        // target="_blank"
                        // rel="noopener noreferrer"
                        href="mailto:m.mahfuz.me@gmail.com"
                        className="button button-primary"
                    >
                        {__('Request Customization', 'quotify')}
                    </a>
                </div>

                {/* Reviews */}
                <div className="quotify-help-block quotify-card">
                    <img
                        className="quotify-card-thumbnail"
                        src={LikeIcon}
                        alt={__('Review Plugin', 'quotify')}
                    />
                    <h3 className="quotify-card-title">
                        {__('Enjoying the Plugin?', 'quotify')}
                    </h3>
                    <p className="message">
                        {__('Your feedback means a lot to us and helps the plugin grow. Share your experience on WordPress.org.', 'quotify')}
                    </p>
                    <a
                        target="_blank"
                        rel="noopener noreferrer"
                        href="https://wordpress.org/support/plugin/product-quotation-for-woocommerce/reviews/#new-post"
                        className="button button-primary"
                    >
                        {__('Leave a Review', 'quotify')}
                    </a>
                </div>

                {/* Developer Section */}
                {/* <div className="quotify-help-block quotify-card">
                    <img
                        className="quotify-card-thumbnail"
                        src={CogIcon}
                        alt={__('Developer', 'quotify')}
                    />
                    <h3 className="quotify-card-title">
                        {__('About the Developer', 'quotify')}
                    </h3>
                    <p className="message">
                        {__('Product Quotation for WooCommerce is built and maintained by Mahafuz. I\'m passionate about creating tools that make WooCommerce stores more powerful and user-friendly.', 'quotify')}
                    </p>
                    <a
                        target="_blank"
                        rel="noopener noreferrer"
                        href="#"
                        className="button button-primary"
                    >
                        {__('Visit My Website', 'quotify')}
                    </a>
                </div> */}
            </div>
        </>
    );
}

export default HelpPage;