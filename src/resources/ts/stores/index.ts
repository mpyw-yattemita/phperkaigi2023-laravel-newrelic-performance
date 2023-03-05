export { config } from "./config";
export { errors } from "./errors";
export { prefectures } from "./prefectures";

import { errors } from "./errors";
import { config } from "./config";
import { prefectures } from "./prefectures";

import { configureStore } from "@reduxjs/toolkit";

export const store = configureStore({
    reducer: {
        errors: errors.reducer,
        config: config.reducer,
        prefectures: prefectures.reducer,
    },
});
export type RootState = ReturnType<typeof store.getState>;
