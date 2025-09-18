import { Modal } from '@wordpress/components';
import PropTypes from 'prop-types';
import React from 'react';
import { modalFullWidthStyles } from '@Utils/helper';

import './index.scss';

const propTypes = {
	title: PropTypes.string,
	isOpen: PropTypes.bool,
	onRequestClose: PropTypes.func,
	isFullScreen: PropTypes.bool,
	disableFooter: PropTypes.bool,
	disableButtonCancel: PropTypes.bool,
	shouldCloseOnClickOutside: PropTypes.bool,
	buttonCancel: PropTypes.object,
	buttonUpdate: PropTypes.object,
	style: PropTypes.object,
	suffix: PropTypes.string,
	size: PropTypes.string,
	footer: PropTypes.jsx,
};

const defaultProps = {
	title: '',
	isOpen: false,
	onRequestClose: () => {},
	disableFooter: false,
	isFullScreen: false,
	shouldCloseOnClickOutside: false,
	disableButtonCancel: false,
	style: modalFullWidthStyles,
	size: 'medium',
	footer: '',
};

export default function WPModal({
	title,
	isOpen,
	onRequestClose,
	children,
	isFullScreen,
	size,
	suffix,
	shouldCloseOnClickOutside,
	footer
}) {
	return (
		<>
			{isOpen && (
				<Modal
					title={title}
					onRequestClose={onRequestClose}
					contentLabel={title}
					isFullScreen={isFullScreen}
					shouldCloseOnClickOutside={shouldCloseOnClickOutside}
					className={`quotify-wp-modal quotify-wp-modal--${size} ${
						suffix && ' quotify-wp-modal--' + suffix
					}`}
				>
					<div className="quotify-wp-modal__content">{children}</div>
					{footer && (
						<div className="quotify-wp-modal__footer">{footer()}</div>
					)}
				</Modal>
			)}
		</>
	);
}

WPModal.propTypes = propTypes;
WPModal.defaultProps = defaultProps;
