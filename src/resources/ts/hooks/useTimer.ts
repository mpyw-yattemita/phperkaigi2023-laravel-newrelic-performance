import { TaskTimer } from "tasktimer";
import { useCallback, useEffect, useState } from "react";

export const useTimer = () => {
    const [elapsed, setElapsed] = useState(0);
    const [running, setRunning] = useState(false);
    const timer = new TaskTimer(10);
    useEffect(() => {
        timer.on(TaskTimer.Event.STARTED, () => {
            setRunning(true);
        });
        timer.on(TaskTimer.Event.TICK, () => {
            setElapsed(timer.time.elapsed);
        });
        timer.on(TaskTimer.Event.STOPPED, () => {
            setRunning(false);
        });
        timer.on(TaskTimer.Event.RESET, () => {
            setElapsed(0);
        });
        return () => {
            timer.removeAllListeners();
        };
    }, []);
    return {
        start: useCallback(() => timer.start(), []),
        stop: useCallback(() => timer.stop(), []),
        reset: useCallback(() => timer.reset(), []),
        elapsed,
        running,
    };
};
