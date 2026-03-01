import { __ } from '@wordpress/i18n';
import TopBar from '@Components/TopBar';
import './index.scss';

const helpResources = [
	{
		id: 'support',
		title: __('Need Assistance?', 'quotify'),
		description: __(
			'Our expert support team is ready to help you with any issue or question.',
			'quotify'
		),
		icon: (
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
			</svg>
		),
		buttonLabel: __('Get Support', 'quotify'),
		buttonUrl: 'https://wordpress.org/support/plugin/product-quotation-for-woocommerce/',
		variant: 'primary',
	},
	{
		id: 'docs',
		title: __('Documentation', 'quotify'),
		description: __(
			'Explore our step-by-step guides and learn how to get the most out of Quotify.',
			'quotify'
		),
		icon: (
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
				<polyline points="14 2 14 8 20 8" />
				<line x1="16" y1="13" x2="8" y2="13" />
				<line x1="16" y1="17" x2="8" y2="17" />
				<polyline points="10 9 9 9 8 9" />
			</svg>
		),
		buttonLabel: __('Read Docs', 'quotify'),
		buttonUrl: 'https://wpindiedev.xyz/docs/',
		variant: 'secondary',
	},
	{
		id: 'bugs',
		title: __('Found a Bug?', 'quotify'),
		description: __(
			'Help us improve by reporting issues directly on GitHub. We address them quickly.',
			'quotify'
		),
		icon: (
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
				<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
				<line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
			</svg>
		),
		buttonLabel: __('Report Bug', 'quotify'),
		buttonUrl: 'https://github.com/mahafuz/product-quotation-for-woocommerce/issues/new',
		variant: 'danger',
	},
	{
		id: 'custom',
		title: __('Custom Features', 'quotify'),
		description: __(
			'Need custom integrations or features? Let us know your requirements.',
			'quotify'
		),
		icon: (
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
			</svg>
		),
		buttonLabel: __('Request Quote', 'quotify'),
		buttonUrl: 'mailto:m.mahafuz.me@gmail.com',
		variant: 'success',
	},
	{
		id: 'review',
		title: __('Leave a Review', 'quotify'),
		description: __(
			'Enjoying Quotify? Your feedback helps us grow and improve.',
			'quotify'
		),
		icon: (
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
				<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
			</svg>
		),
		buttonLabel: __('Write Review', 'quotify'),
		buttonUrl: 'https://wordpress.org/support/plugin/product-quotation-for-woocommerce/reviews/#new-post',
		variant: 'warning',
	},
];

const socialLinks = [
	{
		name: 'GitHub',
		url: 'https://github.com/mahafuz',
		icon: (
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
			</svg>
		),
	},
	{
		name: 'LinkedIn',
		url: 'https://www.linkedin.com/in/mahafuzur-rahaman-123852109/',
		icon: (
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
			</svg>
		),
	},
	{
		name: 'WordPress',
		url: 'https://profiles.wordpress.org/mahfuz01/#content-plugins',
		icon: (
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.044c.615-1.54.82-2.771.82-3.856 0-.405-.026-.78-.07-1.136l.004-.464zm-7.981 10.641c-.827.031-1.672.125-2.466.342l-1.038-2.854 2.921-8.471c.486 1.295.769 2.641.769 4.017 0 2.505-.739 4.832-1.995 6.766l-.191.2zm-3.058-7.566l-2.56 7.416c-1.534-1.404-2.5-3.424-2.5-5.69 0-.746.111-1.463.315-2.145l2.745.419zm6.57-6.9c1.197 0 2.35.217 3.419.615l-2.839 8.244-2.736-7.925c.697-.031 1.404-.094 2.156-.094zm-8.503 1.746c1.197-2.024 3.404-3.386 5.928-3.386.673 0 1.326.094 1.951.267l-1.531 4.451-6.348-1.332zm-5.497 9.554c0-4.805 2.975-8.917 7.182-10.609l-3.295 9.044c-.394 1.089-.615 2.085-.615 3.015 0 3.435 2.006 6.403 4.895 7.842-2.768-1.288-4.685-4.105-4.685-7.391 0-1.603.466-3.099 1.269-4.365l-3.751 2.464z" />
			</svg>
		),
	},
];

function HelpPage() {
	return (
		<>
			<TopBar
				render={() => (
					<div className="quotify-top-bar-left">
						<h4 className="quotify-top-bar-heading">{__('Help & Support', 'quotify')}</h4>
					</div>
				)}
			/>

			<div className="quotify-content-wrap quotify-help-content-wrap">
				<div className="quotify-help-wrapper">
					{/* Hero Section */}
					<div className="quotify-help-hero">
						<div className="quotify-help-hero__content">
							<h1 className="quotify-help-hero__title">
								{__('How can we help?', 'quotify')}
							</h1>
							<p className="quotify-help-hero__description">
								{__(
									'Find answers, documentation, and support for Quotify. Get the most out of your quotation system.',
									'quotify'
								)}
							</p>
						</div>
						<div className="quotify-help-hero__social">
							{socialLinks.map((link) => (
								<a
									key={link.name}
									href={link.url}
									target="_blank"
									rel="noopener noreferrer"
									className="quotify-social-link"
									aria-label={link.name}
								>
									{link.icon}
								</a>
							))}
						</div>
					</div>

					{/* Help Resources Grid */}
					<div className="quotify-help-grid">
						{helpResources.map((resource) => (
							<div key={resource.id} className={`quotify-help-card quotify-help-card--${resource.variant}`}>
								<div className="quotify-help-card__icon">{resource.icon}</div>
								<h3 className="quotify-help-card__title">{resource.title}</h3>
								<p className="quotify-help-card__description">{resource.description}</p>
								<a
									href={resource.buttonUrl}
									target="_blank"
									rel="noopener noreferrer"
									className={`quotify-help-card__button quotify-help-card__button--${resource.variant}`}
								>
									{resource.buttonLabel}
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
										<path d="M5 12h14M12 5l7 7-7 7" />
									</svg>
								</a>
							</div>
						))}
					</div>

					{/* Quick Links Section */}
					<div className="quotify-help-quicklinks">
						<div className="quotify-help-quicklinks__title">
							{__('Quick Links', 'quotify')}
						</div>
						<div className="quotify-help-quicklinks__grid">
							<a
								href="https://wordpress.org/support/plugin/product-quotation-for-woocommerce/"
								target="_blank"
								rel="noopener noreferrer"
								className="quotify-quicklink"
							>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
									<circle cx="12" cy="12" r="10" />
									<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
									<line x1="12" y1="17" x2="12.01" y2="17" />
								</svg>
								{__('Support Forums', 'quotify')}
							</a>
							<a
								href="https://wpindiedev.xyz/docs/"
								target="_blank"
								rel="noopener noreferrer"
								className="quotify-quicklink"
							>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
									<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
									<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
								</svg>
								{__('Documentation', 'quotify')}
							</a>
							<a
								href="https://github.com/mahafuz/product-quotation-for-woocommerce/issues"
								target="_blank"
								rel="noopener noreferrer"
								className="quotify-quicklink"
							>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
									<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
									<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
								</svg>
								{__('Changelog', 'quotify')}
							</a>
							<a
								href="mailto:m.mahafuz.me@gmail.com"
								className="quotify-quicklink"
							>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
									<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
									<polyline points="22,6 12,13 2,6" />
								</svg>
								{__('Contact Us', 'quotify')}
							</a>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

export default HelpPage;
