import { toast } from 'react-toastify';
import { useLocation } from 'react-router-dom';

export const modalFullWidthStyles = {
    overlay: {
        background: 'rgba(35, 40, 45, 0.62)',
        zIndex: 9999,
    },
    content: {
        top: '50%',
        left: '50%',
        right: 'auto',
        bottom: 'auto',
        width: '50%',
        marginRight: '-50%',
        padding: 0,
        transform: 'translate(-50%, -50%)',
    },
};

export const fireNotify = (message, type = '', position = 'top-right') => {
    switch (type) {
        case 'error':
            return toast.error(
                <div className="quotify-toasts">
                    <div className="quotify-toasts__icon">
                        <span className="quotify-icon quotify-icon--information quotify-icon--information-error"></span>
                    </div>
                    <p className="quotify-toasts-message">{message}</p>
                </div>,
                {
                    position,
                    hideProgressBar: true,
                }
            );
        case 'info':
            return toast.info(
                <div className="quotify-toasts">
                    <div className="quotify-toasts__icon">
                        <span className="quotify-icon quotify-icon--information"></span>
                    </div>
                    <p className="quotify-toasts-message">{message}</p>
                </div>,
                {
                    position,
                    hideProgressBar: true,
                }
            );
        case 'warning':
            return toast.warning(
                <div className="quotify-toasts">
                    <div className="quotify-toasts__icon">
                        <span className="quotify-icon quotify-icon--notification"></span>
                    </div>
                    <p className="quotify-toasts-message">{message}</p>
                </div>,
                {
                    position,
                    hideProgressBar: true,
                }
            );
        default:
            return toast.success(
                <div className="quotify-toasts">
                    <div className="quotify-toasts__icon">
                        <span className="quotify-icon quotify-icon--check"></span>
                    </div>
                    <p className="quotify-toasts-message">{message}</p>
                </div>,
                {
                    position,
                    hideProgressBar: true,
                }
            );
    }
}

export const renderError = (e) => {
    fireNotify(
        e?.response?.data?.message ? e?.response?.data?.message : e?.message,
        'error'
    );
};

export function useQuery() {
    return new URLSearchParams(useLocation().search);
};