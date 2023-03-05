import type { FC, HTMLAttributes } from "react";

import { useId, memo } from "react";
import styled from "@emotion/styled";
import { isEqual } from "../utils";

export const NewRelicIcon = memo(({ width, height, ...props }) => {
    const filterId = useId();
    const Component = styled(
        (
            props: HTMLAttributes<SVGPathElement> & {
                width?: number;
                height?: number;
            }
        ) => (
            <svg
                viewBox="0 0 832.8 959.8"
                xmlns="http://www.w3.org/2000/svg"
                width={width}
                height={height}
            >
                <filter id={filterId}>
                    <feColorMatrix
                        type="matrix"
                        values="
                    0.1    0    0    0    0
                      0  0.4    0    0    0
                      0    0  1.8    0    0
                      0    0    0  0.9    0
                   "
                    />
                </filter>
                <path
                    d="M672.6 332.3l160.2-92.4v480L416.4 959.8V775.2l256.2-147.6z"
                    fill="#00ac69"
                    {...props}
                />
                <path
                    d="M416.4 184.6L160.2 332.3 0 239.9 416.4 0l416.4 239.9-160.2 92.4z"
                    fill="#1ce783"
                    {...props}
                />
                <path
                    d="M256.2 572.3L0 424.6V239.9l416.4 240v479.9l-160.2-92.2z"
                    fill="#1d252c"
                    {...props}
                />
            </svg>
        )
    )`
        filter: grayscale(100%) url(#${filterId});
    `;
    return <Component width={width} height={height} {...props} />;
}, isEqual) satisfies FC<
    HTMLAttributes<SVGPathElement> & { width?: number; height?: number }
>;
