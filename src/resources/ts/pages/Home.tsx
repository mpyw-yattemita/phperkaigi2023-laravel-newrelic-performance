import type { FC } from "react";
import type { UpdateOption } from "../api";
import type { RootState } from "../stores";

import { useCallback, useState } from "react";
import Grid from "@mui/material/Unstable_Grid2";
import FormGroup from "@mui/material/FormGroup";
import {
    Box,
    Button,
    CircularProgress,
    Container,
    FormControlLabel,
    Paper,
    Switch,
    Typography,
} from "@mui/material";
import { update, view } from "../api";
import { useTimer } from "../hooks/useTimer";
import { CardsPanel } from "../components/EntityCards";
import { useDispatch, useSelector } from "react-redux";
import {
    config as configSlice,
    errors as errorsSlice,
    prefectures as prefecturesSlice,
} from "../stores";
import { denormalize } from "../stores/prefectures";

export const Home: FC = () => {
    const dispatch = useDispatch();
    const {
        prefectures: { data: prefectureIds, entities },
        config,
        errors,
    } = useSelector((state: RootState) => state);
    const [queryCount, setQueryCount] = useState(0);
    const { start, stop, reset, elapsed, running } = useTimer();
    const toggleWithLazyChildrenCities = useCallback(
        () =>
            dispatch(configSlice.actions.toggleField("withLazyChildrenCities")),
        []
    );
    const toggleWithLazyChildrenDistricts = useCallback(
        () =>
            dispatch(
                configSlice.actions.toggleField("withLazyChildrenDistricts")
            ),
        []
    );
    const toggleWithEagerChildrenCities = useCallback(
        () =>
            dispatch(
                configSlice.actions.toggleField("withEagerChildrenCities")
            ),
        []
    );
    const toggleWithEagerChildrenDistricts = useCallback(
        () =>
            dispatch(
                configSlice.actions.toggleField("withEagerChildrenDistricts")
            ),
        []
    );
    const toggleWithEagerParent = useCallback(
        () => dispatch(configSlice.actions.toggleField("withEagerParent")),
        []
    );
    const load = useCallback(() => {
        (async () => {
            reset();
            start();
            dispatch(prefecturesSlice.actions.clear());
            dispatch(errorsSlice.actions.clearError());
            try {
                const { queryCount, ...response } = await view(config.option);
                setQueryCount(queryCount);
                dispatch(prefecturesSlice.actions.load(response));
            } catch (e: any) {
                dispatch(
                    errorsSlice.actions.setErrorFromUnknownResponseData(
                        e?.response?.data
                    )
                );
                console.error(e);
            }
            stop();
        })();
    }, [config]);

    const edit = useCallback(
        (updateOption: UpdateOption) => {
            (async () => {
                reset();
                start();
                dispatch(errorsSlice.actions.clearError());
                try {
                    const { queryCount } = await update(
                        denormalize(prefectureIds, entities),
                        updateOption
                    );
                    setQueryCount(queryCount);
                } catch (e: any) {
                    dispatch(
                        errorsSlice.actions.setErrorFromUnknownResponseData(
                            e?.response?.data
                        )
                    );
                    console.error(e);
                }
                stop();
            })();
        },
        [prefectureIds, entities]
    );
    const editWithoutValidation = useCallback(() => edit({}), [edit]);
    const editWithLaravelValidation = useCallback(
        () => edit({ withLaravelValidation: true }),
        [edit]
    );
    const editWithWildcardLessLaravelValidation = useCallback(
        () => edit({ withWildcardLessLaravelValidation: true }),
        [edit]
    );
    const editWithPurePhpValidation = useCallback(
        () => edit({ withPurePhpValidation: true }),
        [edit]
    );
    const dryRunLaravelValidation = useCallback(
        () => edit({ withLaravelValidation: true, dryRun: true }),
        [edit]
    );
    const dryRunWildcardLessLaravelValidation = useCallback(
        () => edit({ withWildcardLessLaravelValidation: true, dryRun: true }),
        [edit]
    );
    const dryRunPurePhpValidation = useCallback(
        () => edit({ withPurePhpValidation: true, dryRun: true }),
        [edit]
    );

    return (
        <>
            <Grid container spacing={2} alignItems="stretch">
                <Grid xs={7}>
                    <Paper sx={{ p: 2, height: "100%" }}>
                        <Typography variant="h5" component="div">
                            Config
                        </Typography>
                        <Grid container spacing={1}>
                            <Grid xs={6}>
                                <FormGroup>
                                    <FormControlLabel
                                        label="Eager Load: Children Cities"
                                        control={<Switch />}
                                        checked={
                                            config.option
                                                .withEagerChildrenCities
                                        }
                                        disabled={
                                            running ||
                                            config.disabled
                                                .withEagerChildrenCities
                                        }
                                        onChange={toggleWithEagerChildrenCities}
                                    />
                                    <FormControlLabel
                                        label="Eager Load: Children Districts"
                                        control={<Switch />}
                                        checked={
                                            config.option
                                                .withEagerChildrenDistricts
                                        }
                                        disabled={
                                            running ||
                                            config.disabled
                                                .withEagerChildrenDistricts
                                        }
                                        onChange={
                                            toggleWithEagerChildrenDistricts
                                        }
                                    />
                                </FormGroup>
                            </Grid>
                            <Grid xs={6}>
                                <FormGroup>
                                    <FormControlLabel
                                        label="Lazy Load: Children Cities"
                                        control={<Switch />}
                                        checked={
                                            config.option.withLazyChildrenCities
                                        }
                                        disabled={
                                            running ||
                                            config.disabled
                                                .withLazyChildrenCities
                                        }
                                        onChange={toggleWithLazyChildrenCities}
                                    />
                                    <FormControlLabel
                                        label="Lazy Load: Children Districts"
                                        control={<Switch />}
                                        checked={
                                            config.option
                                                .withLazyChildrenDistricts
                                        }
                                        disabled={
                                            running ||
                                            config.disabled
                                                .withLazyChildrenDistricts
                                        }
                                        onChange={
                                            toggleWithLazyChildrenDistricts
                                        }
                                    />
                                </FormGroup>
                            </Grid>
                            <Grid xs={12}>
                                <FormGroup>
                                    <FormControlLabel
                                        label="Eager Load: Parent Prefecture, Parent City"
                                        control={<Switch />}
                                        checked={config.option.withEagerParent}
                                        disabled={
                                            running ||
                                            config.disabled.withEagerParent
                                        }
                                        onChange={toggleWithEagerParent}
                                    />
                                </FormGroup>
                            </Grid>
                        </Grid>
                    </Paper>
                </Grid>
                <Grid xs={5}>
                    <Paper sx={{ p: 2, height: "100%" }}>
                        <Typography variant="h5" component="div">
                            Action
                        </Typography>
                        <Grid
                            container
                            flexDirection="column"
                            justifyContent="space-evenly"
                            p={2}
                        >
                            <Typography variant="h6" component="div">
                                Elapsed Time: {elapsed / 1000}
                            </Typography>
                            <Typography variant="h6" component="div">
                                Query Count:{" "}
                                {elapsed > 0 && !running ? queryCount : ""}
                            </Typography>
                            <FormGroup>
                                <Grid
                                    container
                                    flexDirection="column"
                                    justifyContent="space-evenly"
                                    p={2}
                                    gap={1}
                                >
                                    <Button
                                        variant="contained"
                                        onClick={load}
                                        disabled={running}
                                    >
                                        Load
                                    </Button>
                                    <Button
                                        variant="contained"
                                        onClick={editWithoutValidation}
                                        disabled={running}
                                        color="error"
                                    >
                                        Update without Validation
                                    </Button>
                                    <Button
                                        variant="contained"
                                        onClick={editWithLaravelValidation}
                                        disabled={running}
                                        color="error"
                                    >
                                        Update with Laravel Validation
                                    </Button>
                                    <Button
                                        variant="contained"
                                        onClick={editWithWildcardLessLaravelValidation}
                                        disabled={running}
                                        color="error"
                                    >
                                        Update with Wildcard-Less Laravel Validation
                                    </Button>
                                    <Button
                                        variant="contained"
                                        onClick={editWithPurePhpValidation}
                                        disabled={running}
                                        color="error"
                                    >
                                        Update with Pure PHP Validation
                                    </Button>
                                    <Button
                                        variant="contained"
                                        onClick={dryRunLaravelValidation}
                                        disabled={running}
                                        color="warning"
                                    >
                                        Dry-Run Laravel Validation
                                    </Button>
                                    <Button
                                        variant="contained"
                                        onClick={dryRunWildcardLessLaravelValidation}
                                        disabled={running}
                                        color="warning"
                                    >
                                        Dry-Run Wildcard-Less Laravel Validation
                                    </Button>
                                    <Button
                                        variant="contained"
                                        onClick={dryRunPurePhpValidation}
                                        disabled={running}
                                        color="warning"
                                    >
                                        Dry-Run Pure PHP Validation
                                    </Button>
                                </Grid>
                            </FormGroup>
                        </Grid>
                    </Paper>
                </Grid>
            </Grid>
            <Container>
                {errors.error ? (
                    <Box paddingY="24px">
                        <Typography
                            variant="h5"
                            color="error.main"
                            component="div"
                        >
                            Error: {errors.error.message}
                        </Typography>
                        <Box>
                            <ul>
                                {errors.error.validationErrors.map((msg) => (
                                    <li>{msg}</li>
                                ))}
                            </ul>
                        </Box>
                    </Box>
                ) : null}
                <Box paddingY="24px">
                    {running ? (
                        <CircularProgress
                            sx={{ display: "block", marginX: "auto" }}
                        />
                    ) : (
                        <CardsPanel prefectureIds={prefectureIds} />
                    )}
                </Box>
            </Container>
        </>
    );
};
