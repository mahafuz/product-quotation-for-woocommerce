import { createSlice } from "@reduxjs/toolkit";

const initialState = {
	settings: {},
	activeTab: "general",
};

const settingsSlice = createSlice({
    name: "settings",
    initialState,
    reducers: {
        setSettings: (state, action) => {
            state.settings = action.payload;
        },
        setActiveTab: (state, action) => {
            state.activeTab = action.payload;
        }
    },
});

export const { setSettings, setActiveTab } = settingsSlice.actions;
export default settingsSlice.reducer;