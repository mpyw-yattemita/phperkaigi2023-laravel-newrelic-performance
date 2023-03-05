declare module "http-build-query" {
    export default function (params: any): string;
}

declare module 'process' {
    global {
        namespace NodeJS {
            interface ProcessEnv {
                VITE_GITHUB_URL: string
                VITE_NEW_RELIC_URL: string
            }
        }
    }
}
