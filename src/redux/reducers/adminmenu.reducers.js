import { FETCH_ADMIN_MENU } from '../types/adminmenu.types';
import { menu } from '@Utils/helper';

import { createSlice } from "@reduxjs/toolkit";

const initialState = {
	...JSON.parse(menu),
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
