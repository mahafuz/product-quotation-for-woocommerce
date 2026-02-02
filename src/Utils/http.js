import axios from "axios";
import {getAjaxUrl, getRestUrl, getNonce} from '@Utils/global';

export const makeRequest = async (payload = {}, isRaw = false) => {
    let form_data = new FormData(); // eslint-disable-line
    form_data.append('security', getNonce());
    Object.entries(payload).forEach(([key, value]) => {
        if (!isRaw && typeof value === 'object' && value !== null) {
            form_data.append(key, JSON.stringify(value));
        } else {
            form_data.append(key, value);
        }
    });

    return await axios.post(getAjaxUrl(), form_data).then(
        (response) => {
            return response;
        },
        (error) => {
            console.log(error); // eslint-disable-line
        }
    );
};

export const API = axios.create({
    baseURL: getRestUrl(),
    headers: {
        'content-type': 'application/json',
        'X-WP-Nonce': getNonce(),
        'Cache-Control': 'no-cache', // Prevent caching
    },
});