import React, { useState, useRef } from 'react';
import { ReactFormBuilder } from 'react-form-builder2';

var items = [
    {
        key   : 'TextInput',
        canHaveAnswer : true,
        name  : 'Full Name',
        label : 'Full Name',
        icon  : 'fa fa-user'
    },
    {
        key   : 'TextInput',
        canHaveAnswer : true,
        name  : 'Organization',
        label : 'Organization',
        icon  : 'fa fa-building'
    },
    {
        key   : 'TextInput',
        canHaveAnswer : true,
        name  : 'Address',
        label : 'Address',
        icon  : 'fa fa-map-marker'
    },
    {
        key   : 'TextInput',
        canHaveAnswer : true,
        name  : 'Phone/Mobile',
        label : 'Phone/Mobile',
        icon  : 'fa fa-mobile'
    },
    {
        key   : 'TextInput',
        canHaveAnswer : true,
        name  : 'Subject',
        label : 'Subject',
        icon  : 'fa fa-book'
    },
    {
        key   : 'TextArea',
        canHaveAnswer : true,
        name  : 'Comments',
        label : 'Comments',
        icon  : 'fa fa-paragraph'
    },
    {
        key   : 'TextInput',
        canHaveAnswer : true,
        name  : 'Website URL',
        label : 'Website URL',
        icon  : 'fa fa-globe'
    },
    {
        key   : 'TextInput',
        canHaveAnswer : true,
        name  : 'Email',
        label : 'Email',
        icon  : 'fa fa-envelope'
    },
];

const Page = () => {
    return(
        <div className={`pqfw-form-page tab-content`}>
            <ReactFormBuilder
                url={`${window.PQFW_FORM_SCRIPT.restUrl}pqfw/v1/form`}
                toolbarItems={items}
                saveUrl={`${window.PQFW_FORM_SCRIPT.restUrl}pqfw/v1/form`}
            />
        </div>
    );
}

export default Page;