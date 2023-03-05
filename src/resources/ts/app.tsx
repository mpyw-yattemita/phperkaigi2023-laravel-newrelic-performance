import type { FC } from "react";

import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { createBrowserRouter, Outlet, RouterProvider } from "react-router-dom";
import { Provider } from "react-redux";
import styled from "@emotion/styled";
import { Container, CssBaseline } from "@mui/material";
import { NavigationDrawer } from "./components/NavigationDrawer";
import { Home } from "./pages/Home";
import { store } from "./stores";

const App: FC = (props) => (
    <>
        <CssBaseline />
        <NavigationDrawer />
        <StyledContainer>
            <StyledOutlet {...props} />
        </StyledContainer>
    </>
);

const StyledContainer = styled(Container)`
    height: 100%;
    padding-top: 48px;
    padding-bottom: 48px;
`;

const StyledOutlet = styled(Outlet)`
    font-family: "Roboto", sans-serif;
    -webkit-font-smoothing: antialiased;
    font-weight: 400;
    line-height: 1.42857;
    text-rendering: optimizeLegibility;
    width: 100%;
    height: 100%;
`;

const router = createBrowserRouter([
    {
        path: "/",
        element: <App />,
        children: [
            {
                path: "/",
                element: <Home />,
            },
        ],
    },
]);

createRoot(document.getElementById("app")!).render(
    <StrictMode>
        <Provider store={store}>
            <RouterProvider router={router} />
        </Provider>
    </StrictMode>
);
