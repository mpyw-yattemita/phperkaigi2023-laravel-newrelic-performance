import type { FC, ForwardedRef } from "react";
import type { NumericCommonFields } from "../api";

import { forwardRef, memo, useDeferredValue, useRef } from "react";
import { useDispatch } from "react-redux";
import { useEditable } from "use-editable";
import Grid from "@mui/material/Unstable_Grid2";
import { Typography } from "@mui/material";
import { prefectures } from "../stores";
import { isEqual } from "../utils";

const Pair = forwardRef(
    ({ name, value }, ref: ForwardedRef<HTMLSpanElement>) => (
        <Grid container spacing={1}>
            <Grid xs={6}>
                <Typography fontSize="14px" fontWeight="bold">
                    {name}
                </Typography>
            </Grid>
            <Grid xs={6}>
                <Typography fontSize="14px" ref={ref}>
                    {value === null ? "" : value}
                </Typography>
            </Grid>
        </Grid>
    )
) satisfies FC<{
    name: string;
    value: number | null;
}>;

export const EditablePair = memo((props) => {
    const { name, fieldKey, fieldValue, entityType, entityId } = props;
    const editorRef = useRef(null);
    const dispatch = useDispatch();
    useEditable(editorRef, (newValue: string) => {
        const fieldValue = newValue === "" ? null : Number(newValue);
        switch (entityType) {
            case "districts":
                dispatch(
                    prefectures.actions.updateDistrictField({
                        districtId: entityId,
                        fieldKey,
                        fieldValue,
                    })
                );
                return;
            case "cities":
                dispatch(
                    prefectures.actions.updateCityField({
                        cityId: entityId,
                        fieldKey,
                        fieldValue,
                    })
                );
                return;
            case "prefectures":
                dispatch(
                    prefectures.actions.updatePrefectureField({
                        prefectureId: entityId,
                        fieldKey,
                        fieldValue,
                    })
                );
                return;
        }
    });

    return (
        <Pair
            ref={editorRef}
            name={name}
            value={useDeferredValue(fieldValue)}
        />
    );
}, isEqual) satisfies FC<{
    name: string;
    fieldKey: keyof NumericCommonFields;
    fieldValue: number | null;
    entityType: "prefectures" | "cities" | "districts";
    entityId: number;
}>;
