import { configureStore } from "@reduxjs/toolkit";
import settingsReducer from "./reducers/settings.reducers";
import quotationsReducer from "./reducers/quotations.reducers";
import adminmenu from './reducers/adminmenu.reducers'

const store = configureStore({
    reducer: {
        adminmenu: adminmenu,
        settings: settingsReducer,
        quotations: quotationsReducer
    },
    middleware: (getDefaultMiddleware) =>
        getDefaultMiddleware({
            serializableCheck: true,
        }),
});

// store.subscribe(() => console.log(store.getState()));

export default store;