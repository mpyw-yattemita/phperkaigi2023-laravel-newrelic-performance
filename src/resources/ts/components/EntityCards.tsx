import type { FC, ReactNode } from "react";
import type { Property } from "csstype";
import type { CardProps } from "@mui/material";
import type { CommonFields, NumericCommonFields } from "../api";
import type { RootState } from "../stores";

import { memo } from "react";
import { useSelector } from "react-redux";
import styled from "@emotion/styled";
import Grid from "@mui/material/Unstable_Grid2";
import { Card, CardContent, CardHeader, Typography } from "@mui/material";
import { EditablePair } from "./AttributePairs";
import { ChildrenLoader } from "./ChildrenLoader";
import { theme } from "../theme";
import { isEqual } from "../utils";
import { useMemoizedComponentCompare } from "../hooks/useMemoizedComponentCompare";
import { prefectures } from "../stores";

type Column<T> = {
    name: string;
    key: keyof T;
};

const columns: Column<NumericCommonFields>[] = [
    {
        name: "全人口",
        key: "all",
    },
    {
        name: "男性人口",
        key: "male",
    },
    {
        name: "女性人口",
        key: "female",
    },
    {
        name: "2015年時点での全人口",
        key: "at2015",
    },
    {
        name: "2015年比",
        key: "comparedTo2015",
    },
    {
        name: "2015年比[%]",
        key: "percentageComparedTo2015",
    },
    {
        name: "人口密度",
        key: "density",
    },
    {
        name: "平均年齢",
        key: "averageAge",
    },
    {
        name: "中央年齢",
        key: "medianAge",
    },
    {
        name: "14歳以下人口",
        key: "under14",
    },
    {
        name: "64歳以下人口",
        key: "under64",
    },
    {
        name: "65歳以上人口",
        key: "over65",
    },
    {
        name: "14歳以下人口比[%]",
        key: "percentageUnder14",
    },
    {
        name: "64歳以下人口比[%]",
        key: "percentageUnder64",
    },
    {
        name: "65歳以上人口比[%]",
        key: "percentageOver65",
    },
    {
        name: "14歳以下男性人口",
        key: "maleUnder14",
    },
    {
        name: "64歳以下男性人口",
        key: "maleUnder64",
    },
    {
        name: "65歳以上男性人口",
        key: "maleOver65",
    },
    {
        name: "14歳以下男性人口比[%]",
        key: "malePercentageUnder14",
    },
    {
        name: "64歳以下男性人口比[%]",
        key: "malePercentageUnder64",
    },
    {
        name: "65歳以上男性人口比[%]",
        key: "malePercentageOver65",
    },
    {
        name: "14歳以下女性人口",
        key: "femaleUnder14",
    },
    {
        name: "64歳以下女性人口",
        key: "femaleUnder64",
    },
    {
        name: "65歳以上女性人口",
        key: "femaleOver65",
    },
    {
        name: "14歳以下女性人口比[%]",
        key: "femalePercentageUnder14",
    },
    {
        name: "64歳以下女性人口比[%]",
        key: "femalePercentageUnder64",
    },
    {
        name: "65歳以上女性人口比[%]",
        key: "femalePercentageOver65",
    },
];

const CommonCardContentWithoutChildren = memo(
    ({ fields, entityType, entityId }) => (
        <Grid container>
            {columns.map((column) => (
                <Grid xs={6} key={column.key}>
                    <EditablePair
                        entityType={entityType}
                        entityId={entityId}
                        name={column.name}
                        fieldKey={column.key}
                        fieldValue={fields[column.key]}
                    />
                </Grid>
            ))}
        </Grid>
    ),
    isEqual
) satisfies FC<{
    fields: CommonFields;
    entityType: "prefectures" | "cities" | "districts";
    entityId: number;
}>;

type StyledCardProps = CardProps & { background?: Property.Background };
const StyledCard = styled(Card)(({ background }: StyledCardProps) => ({
    background,
}));
const CommonCard = memo(
    ({ fields, children, entityType, entityId, ...props }) => (
        <StyledCard variant="outlined" {...props}>
            <CardHeader title={`${fields.name} (No. ${fields.sortableId})`} />
            <CardContent>
                <CommonCardContentWithoutChildren
                    fields={fields}
                    entityType={entityType}
                    entityId={entityId}
                />
                {children}
            </CardContent>
        </StyledCard>
    ),
    isEqual
) satisfies FC<
    {
        fields: CommonFields;
        children?: ReactNode;
        entityType: "prefectures" | "cities" | "districts";
        entityId: number;
    } & StyledCardProps
>;

const PrefectureCard = memo(({ prefectureId, ...props }) => {
    const prefecture = useSelector(
        (state: RootState) =>
            state.prefectures.entities.prefectures[prefectureId],
        isEqual
    );
    const ChildrenComponent = useMemoizedComponentCompare(
        () => (
            <>
                {prefecture.cities?.map((cityId) => (
                    <CityCard key={cityId} cityId={cityId} />
                ))}
            </>
        ),
        [prefecture.cities],
        isEqual
    );
    return (
        <CommonCard
            entityType="prefectures"
            entityId={prefectureId}
            fields={prefecture}
            background={theme.palette.success["50"]}
            {...props}
        >
            {prefecture.cities?.length ? (
                <ChildrenLoader
                    title={"Open Cities"}
                    ChildrenComponent={ChildrenComponent}
                />
            ) : null}
        </CommonCard>
    );
}, isEqual) satisfies FC<{ prefectureId: number } & StyledCardProps>;

const CityCard = memo(({ cityId, ...props }) => {
    const city = useSelector(
        (state: RootState) => state.prefectures.entities.cities[cityId],
        isEqual
    );
    const ChildrenComponent = useMemoizedComponentCompare(
        () => (
            <>
                {city.districts?.map((districtId) => (
                    <DistrictCard key={districtId} districtId={districtId} />
                ))}
            </>
        ),
        [city.districts],
        isEqual
    );
    return (
        <CommonCard
            entityType="cities"
            entityId={cityId}
            fields={city}
            background={theme.palette.warning["50"]}
            {...props}
        >
            {city.districts?.length ? (
                <ChildrenLoader
                    title={"Open Districts"}
                    ChildrenComponent={ChildrenComponent}
                />
            ) : null}
        </CommonCard>
    );
}, isEqual) satisfies FC<
    {
        cityId: number;
    } & StyledCardProps
>;

const DistrictCard = memo(({ districtId, ...props }) => {
    const district = useSelector(
        (state: RootState) => state.prefectures.entities.districts[districtId],
        isEqual
    );
    return (
        <CommonCard
            entityType="districts"
            entityId={districtId}
            fields={district}
            background={theme.palette.error["50"]}
            {...props}
        />
    );
}, isEqual) satisfies FC<
    {
        districtId: number;
    } & StyledCardProps
>;

export const CardsPanel = memo(
    ({ prefectureIds }) =>
        prefectureIds.length < 1 ? (
            <Typography variant="h6" textAlign="center" color="text.secondary">
                Nothing to show
            </Typography>
        ) : (
            <Grid container spacing={2} direction="column">
                {prefectureIds.map((prefectureId) => (
                    <Grid key={prefectureId}>
                        <PrefectureCard prefectureId={prefectureId} />
                    </Grid>
                ))}
            </Grid>
        ),
    isEqual
) satisfies FC<{ prefectureIds: number[] }>;
