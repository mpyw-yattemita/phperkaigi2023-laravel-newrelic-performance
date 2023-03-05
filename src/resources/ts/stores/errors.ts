import type { PayloadAction } from "@reduxjs/toolkit";
import { createSlice } from "@reduxjs/toolkit";

type Errors = {
    error: {
        message: string;
        validationErrors: string[];
    } | null;
};
export const errors = createSlice({
    name: "errors",
    initialState: {
        error: null,
    } as Errors,
    reducers: {
        clearError: (state) => {
            state.error = null;
        },
        setErrorFromUnknownResponseData: (
            state,
            action: PayloadAction<unknown>
        ) => {
            const message = (action.payload as any)?.message;
            if (typeof message !== "string") {
                return;
            }
            state.error = { message, validationErrors: [] };
            const fields = (action.payload as any)?.errors;
            if (typeof fields !== "object" || fields === null) {
                return;
            }
            for (const values of Object.values(fields)) {
                if (!Array.isArray(values)) {
                    continue;
                }
                state.error.validationErrors.push(...values.map(String));
            }
        },
    },
});
