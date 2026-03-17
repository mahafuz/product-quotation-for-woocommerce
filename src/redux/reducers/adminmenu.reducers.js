import { FETCH_ADMIN_MENU } from '../types/adminmenu.types';
import { adminMenu } from '@Utils/config';

import { createSlice } from "@reduxjs/toolkit";

const initialState = {
	...adminMenu()
};

const adminmenuReducer = createSlice({
    name: "adminmenu",
    initialState,
    reducers: {
        getAdminmenu: (state, action) => {
			const payload = action.payload;
			switch (action.type) {
				case FETCH_ADMIN_MENU:
					return {
						...payload,
					};
				default:
					return state;
			}
        }
    },
});

export const {getAdminmenu} = adminmenuReducer.actions;
export default adminmenuReducer.reducer;
