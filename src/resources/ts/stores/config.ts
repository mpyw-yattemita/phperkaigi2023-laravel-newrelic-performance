import type { PayloadAction } from "@reduxjs/toolkit";
import type { ViewOption } from "../api";

import { createSlice } from "@reduxjs/toolkit";

type Config = {
    option: Required<ViewOption>;
    disabled: Required<ViewOption>;
};
export const config = createSlice({
    name: "config",
    initialState: {
        option: {
            withLazyChildrenCities: false,
            withLazyChildrenDistricts: false,
            withEagerChildrenCities: false,
            withEagerChildrenDistricts: false,
            withEagerParent: false,
        },
        disabled: {
            withLazyChildrenCities: false,
            withLazyChildrenDistricts: true,
            withEagerChildrenCities: false,
            withEagerChildrenDistricts: true,
            withEagerParent: true,
        },
    } as Config,
    reducers: {
        toggleField: (
            state,
            action: PayloadAction<keyof ViewOption>
        ): Config => {
            const newValue = !state.option[action.payload];
            switch (action.payload) {
                case "withLazyChildrenCities":
                    return {
                        option: {
                            ...state.option,
                            withLazyChildrenCities: newValue,
                            withLazyChildrenDistricts: false,
                            withEagerChildrenCities: false,
                            withEagerChildrenDistricts: false,
                            withEagerParent: false,
                        },
                        disabled: {
                            ...state.disabled,
                            withLazyChildrenDistricts: !newValue,
                            withEagerChildrenDistricts: true,
                            withEagerParent: true,
                        },
                    };
                case "withLazyChildrenDistricts":
                    return {
                        ...state,
                        option: {
                            ...state.option,
                            withLazyChildrenDistricts: newValue,
                        },
                    };
                case "withEagerChildrenCities":
                    return {
                        option: {
                            ...state.option,
                            withLazyChildrenCities: false,
                            withLazyChildrenDistricts: false,
                            withEagerChildrenCities: newValue,
                            withEagerChildrenDistricts: false,
                            withEagerParent: false,
                        },
                        disabled: {
                            ...state.disabled,
                            withLazyChildrenDistricts: true,
                            withEagerChildrenDistricts: !newValue,
                            withEagerParent: !newValue,
                        },
                    };
                case "withEagerChildrenDistricts":
                    return {
                        option: {
                            ...state.option,
                            withEagerChildrenDistricts: newValue,
                        },
                        disabled: {
                            ...state.disabled,
                            withLazyChildrenDistricts: true,
                            withEagerChildrenDistricts: false,
                        },
                    };
                case "withEagerParent":
                    return {
                        ...state,
                        option: {
                            ...state.option,
                            withEagerParent: newValue,
                        },
                    };
            }
        },
    },
});
