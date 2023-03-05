import type { Draft, PayloadAction } from "@reduxjs/toolkit";
import type {
    City,
    District,
    NumericCommonFields,
    PrefectureLoadResponse,
} from "../api";

import { createSlice } from "@reduxjs/toolkit";
import {
    denormalize as applyDenormalize,
    normalize as applyNormalize,
    schema as defineSchema,
} from "normalizr";

type UpdatePayload<K extends keyof NumericCommonFields> = {
    fieldKey: K;
    fieldValue: number | null;
};
type PrefectureUpdatePayload<K extends keyof NumericCommonFields> =
    UpdatePayload<K> & {
        prefectureId: number;
    };
type CityUpdatePayload<K extends keyof NumericCommonFields> =
    UpdatePayload<K> & {
        cityId: number;
    };
type DistrictUpdatePayload<K extends keyof NumericCommonFields> =
    UpdatePayload<K> & {
        districtId: number;
    };

export const schema = (() => {
    const district = new defineSchema.Entity("districts", {});
    const city = new defineSchema.Entity("cities", {
        districts: [district],
    });
    const prefecture = new defineSchema.Entity("prefectures", {
        cities: [city],
    });
    return new defineSchema.Array(prefecture);
})();

export type Normalized = {
    data: number[]; // prefectureIds
    entities: {
        districts: {
            [id: number]: District;
        };
        cities: {
            [id: number]: Omit<City, "districts"> & {
                districts?: number[];
            };
        };
        prefectures: {
            [id: number]: Omit<City, "cities"> & {
                cities?: number[];
            };
        };
    };
};

export const normalize = (response: PrefectureLoadResponse): Normalized => {
    const { result: data, entities } = applyNormalize(response.data, schema);
    return {
        data,
        entities,
    } as Normalized;
};

export const denormalize = (
    input: number[],
    entities: Normalized["entities"]
): PrefectureLoadResponse => {
    return { data: applyDenormalize(input, schema, entities) };
};

export const prefectures = createSlice({
    name: "prefectures",
    initialState: { data: [] as number[], entities: {} } as Normalized,
    reducers: {
        clear: () => {
            return { data: [] as number[], entities: {} } as Normalized;
        },
        load: (state, action: PayloadAction<PrefectureLoadResponse>) => {
            return normalize(action.payload);
        },
        updatePrefectureField: function <K extends keyof NumericCommonFields>(
            state: Draft<Normalized>,
            {
                payload: { prefectureId, fieldKey, fieldValue },
            }: PayloadAction<PrefectureUpdatePayload<K>>
        ) {
            state.entities.prefectures[prefectureId][fieldKey] = fieldValue;
        },
        updateCityField: function <K extends keyof NumericCommonFields>(
            state: Draft<Normalized>,
            {
                payload: { cityId, fieldKey, fieldValue },
            }: PayloadAction<CityUpdatePayload<K>>
        ) {
            state.entities.cities[cityId][fieldKey] = fieldValue;
        },
        updateDistrictField: function <K extends keyof NumericCommonFields>(
            state: Draft<Normalized>,
            {
                payload: { districtId, fieldKey, fieldValue },
            }: PayloadAction<DistrictUpdatePayload<K>>
        ) {
            state.entities.districts[districtId][fieldKey] = fieldValue;
        },
    },
});
