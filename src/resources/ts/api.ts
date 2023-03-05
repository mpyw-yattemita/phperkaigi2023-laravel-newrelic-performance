import axios from "axios";
import applyCaseMiddleware from "axios-case-converter";
import httpBuildQuery from "http-build-query";

export type ViewOption = {
    withLazyChildrenCities?: boolean;
    withLazyChildrenDistricts?: boolean;
    withEagerChildrenCities?: boolean;
    withEagerChildrenDistricts?: boolean;
    withEagerParent?: boolean;
};
export type UpdateOption = {
    withLaravelValidation?: boolean;
    withWildcardLessLaravelValidation?: boolean;
    withPurePhpValidation?: boolean;
    dryRun?: boolean;
};
export type CommonFields = {
    id: number;
    sortableId: string;
    name: string;
    all: number | null;
    male: number | null;
    female: number | null;
    at2015: number | null;
    comparedTo2015: number | null;
    percentageComparedTo2015: number | null;
    density: number | null;
    averageAge: number | null;
    medianAge: number | null;
    under14: number | null;
    under64: number | null;
    over65: number | null;
    percentageUnder14: number | null;
    percentageUnder64: number | null;
    percentageOver65: number | null;
    maleUnder14: number | null;
    maleUnder64: number | null;
    maleOver65: number | null;
    malePercentageUnder14: number | null;
    malePercentageUnder64: number | null;
    malePercentageOver65: number | null;
    femaleUnder14: number | null;
    femaleUnder64: number | null;
    femaleOver65: number | null;
    femalePercentageUnder14: number | null;
    femalePercentageUnder64: number | null;
    femalePercentageOver65: number | null;
    createdAt: string;
    updatedAt: string;
};
export type NumericCommonFields = Omit<
    {
        [K in keyof CommonFields as CommonFields[K] extends number | null
            ? K
            : never]: CommonFields[K];
    },
    "id"
>;
export type Prefecture = CommonFields & {
    cities?: City[];
};
export type City = CommonFields & {
    prefectureId: number;
    districts?: District[];
};
export type District = CommonFields & {
    cityId: number;
};
export type PrefectureLoadResponse = {
    data: Prefecture[];
};
export type PrefectureUpdateRequest = PrefectureLoadResponse;

const client = applyCaseMiddleware(axios.create(), {
    caseFunctions: {
        camel: (s) => s.replace(/_./g, (s) => s.charAt(1).toUpperCase()),
        snake: (s) => s.replace(/[A-Z]|\d+/g, (s) => `_${s.toLowerCase()}`),
    },
});
export const view = async (
    option?: ViewOption
): Promise<{ data: Prefecture[]; queryCount: number }> => {
    const response = await client.get<PrefectureLoadResponse>("/api/view", {
        params: option,
        paramsSerializer: {
            serialize: httpBuildQuery,
        },
    });
    return {
        data: response.data.data,
        queryCount: Number(response.headers["x-query-count"]),
    };
};

export const update = async (
    payload: PrefectureUpdateRequest,
    option: UpdateOption = {}
): Promise<{ queryCount: number }> => {
    const uri = option.dryRun ? '/api/validate' : '/api/update';
    const { dryRun, ...params } = option;
    const response = await client.post(uri, payload, {
        params,
        paramsSerializer: {
            serialize: httpBuildQuery,
        },
    });
    return {
        queryCount: Number(response.headers["x-query-count"]),
    };
};
