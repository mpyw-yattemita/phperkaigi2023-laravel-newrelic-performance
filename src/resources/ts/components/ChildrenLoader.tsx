import type { FC } from "react";

import { memo, useCallback, useState, useTransition } from "react";
import { Button } from "@mui/material";
import { isEqual } from "../utils";

export const ChildrenLoader = memo(({ title, ChildrenComponent }) => {
    const [clicked, setClicked] = useState(false);
    const [isPending, startTransition] = useTransition();
    const onClick = useCallback(() => {
        startTransition(() => {
            setClicked(true);
        });
    }, []);

    if (!clicked) {
        return (
            <Button variant="outlined" onClick={onClick} disabled={isPending}>
                {isPending ? "Loading..." : title}
            </Button>
        );
    }

    return <ChildrenComponent />;
}, isEqual) satisfies FC<{
    title: string;
    ChildrenComponent: FC;
}>;
