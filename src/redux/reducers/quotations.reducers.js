import { createSlice } from "@reduxjs/toolkit";

const initialState = {
	quotations: {},
};

const quotationsReducer = createSlice({
    name: "quotations",
    initialState,
    reducers: {
        setQuotations: (state, action) => {
            state.settings = action.payload;
        },
        getQuotations: (state, action) => {
			return state.quotations || {};
        }
    },
});

export const { setQuotations, getQuotations} = quotationsReducer.actions;
export default quotationsReducer.reducer;