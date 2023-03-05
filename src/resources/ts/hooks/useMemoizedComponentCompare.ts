import type { FC } from "react";

import { useEffect, useRef } from "react";

const FirstRender = Symbol();

export const useMemoizedComponentCompare = <T extends FC, D extends any[]>(
    next: T,
    nextDeps: D,
    compare: (a: D, b: D) => boolean
): T => {
    const previousRef = useRef<T | typeof FirstRender>(FirstRender);
    const previousDepsRef = useRef<D | typeof FirstRender>(FirstRender);

    const previous = previousRef.current;
    const previousDeps = previousDepsRef.current;

    const isEqual =
        previous !== FirstRender &&
        previousDeps !== FirstRender &&
        compare(previousDeps, nextDeps);

    const valueToReturn = isEqual ? previous : next;

    useEffect(() => {
        if (!isEqual) {
            previousRef.current = valueToReturn;
            previousDepsRef.current = nextDeps;
        }
    }, nextDeps);

    return valueToReturn;
};
