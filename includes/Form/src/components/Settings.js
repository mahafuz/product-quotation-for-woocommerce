import React, { useState, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import swal from 'sweetalert';

import '../scss/settings.scss';



const Settings = () => {
    const strings = window?.PQFW_OBJECT?.strings;
    const [ formTitle, setFormTitle ]               = useState( 'Testimonial Submission Form' );
    const [ message, setMessage ]                   = useState( 'Thanks! Sent for admin approval.' );
    const [ buttonText, setButtonText ]             = useState( 'Submit' );
    const [ isReChecked, setRecaptchaChecked ]      = useState( false );
    const [ isCategoryChecked, setCategoryChecked ] = useState(false );

    useEffect( () => {
        apiFetch( {
            path: 'pqfw/v1/formSettings'
        } ).then( ( res ) => {
            if ( res ) {
                setFormTitle( res.formTitle );
                setMessage( res.message );
                setButtonText( res.buttonText );
                setRecaptchaChecked( res.isReChecked );
                setCategoryChecked( res.isCategoryChecked );
            } else {
                console.error( 'Something wen\'t wrong while fetching settings.' );
            }
        } );
    }, [] );

    const handleSubmit = (ev) => {
        ev.preventDefault();

        const data = {
            formTitle,
            message,
            buttonText,
            isReChecked,
            isCategoryChecked
        };

        apiFetch( {
            path: 'pqfw/v1/formSettings',
            method: 'POST',
            data: data,
        } ).then( ( res ) => {
            if ( res.success ) {
                swal( "Saved", res.data, "success" );
            }
        } );
    }

    return (<div className={`pqfw-form-settings-page tab-content`}>
        <form onSubmit={(ev) => handleSubmit(ev) }>
            <table className="form-table">
                <tr className="pqfw-form-successfull-submission-message">
                    <th>{strings?.form_title}</th>
                    <td>
                        <textarea
                            rows="3"
                            cols="40"
                            className=""
                            spellCheck="false"
                            value={formTitle}
                            onChange={ (ev) => setFormTitle( ev.target.value ) }
                        >
                        </textarea>
                    </td>
                </tr>
                <tr className="pqfw-form-successfull-submission-message">
                    <th>{strings?.messageToShow}</th>
                    <td>
                        <textarea
                            rows="3"
                            cols="40"
                            className=""
                            spellCheck="false"
                            value={message}
                            onChange={ (ev) => setMessage( ev.target.value ) }
                        >
                        </textarea>
                    </td>
                </tr>
                <tr className="pqfw-submit-btn-text">
                    <th>{ strings?.submitButtonText }</th>
                    <td>
                        <input
                            type="text"
                            className="regular-text"
                            value={buttonText}
                            onChange={ (ev) => setButtonText( ev.target.value )}
                        />
                    </td>
                </tr>

                <tr>
                    <th>{strings?.enableCategoryField}</th>
                    <td>
                        <span className={`components-form-toggle${ isCategoryChecked ? ' is-checked' : ''}`}>
                            <input
                                className="components-form-toggle__input"
                                id="inspector-toggle-control-3"
                                type="checkbox"
                                aria-describedby="inspector-toggle-control-3__help"
                                checked={isCategoryChecked}
                                onChange={() => setCategoryChecked( ! isCategoryChecked ) }
                            />

                            <span className="components-form-toggle__track"></span>
                            <span className="components-form-toggle__thumb"></span>
                        </span>
                    </td>
                </tr>

                <tr className="pqfw-submit-btn-text">
                    <button className="button button-primary">{strings?.saveSettings}</button>
                </tr>
            </table>
        </form>
    </div>);
}

export default Settings;