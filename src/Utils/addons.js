// import { __ } from '@wordpress/i18n';

// import config from '@Utils/config';
// import { admin_url } from "@Utils/global";

// export const { addons } = config;

// export const getAllAddons = () => {
//     if (typeof addons != 'string') {
//         return addons;
//     }
//
//     return JSON.parse(addons);
// };

// export const getAddonActiveStatus = (name, isPro = false) => {
//     const allAddons = getAllAddons();
//
//     return allAddons?.[name] ?? false;
// };

// export const getAddonInfo = (name) => {
//     return [
//         {
//             label: __('Contact Form 7', 'quotify'),
//             name: 'contact-form-7',
//             is_pro: false,
//             required_plugin: true,
//             details: __(
//                 'Use contact form 7 as quotation submission form.',
//                 'quotify'
//             ),
//             icon: 'https://ps.w.org/contact-form-7/assets/icon.svg',
//             url: `${admin_url}admin.php?page=forms`,
//             docsUrl: `https://wpindiedev.xyz/docs/contact-form-7/`,
//         },
//         {
//             label: __('WPForms', 'quotify'),
//             name: 'wpforms',
//             is_pro: false,
//             required_plugin: false,
//             details: __('Use WPForms as quotation submission form.', 'quotify'),
//             icon: 'https://ps.w.org/contact-form-7/assets/icon.svg',
//             url: `${admin_url}admin.php?page=forms`,
//             docsUrl: `https://wpindiedev.xyz/docs/wpforms/`,
//         },
//     ].find((item) => name === item.name);
// }
