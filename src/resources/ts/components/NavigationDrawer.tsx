import type { FC } from "react";

import { useCallback, useState } from "react";
import { Link } from "react-router-dom";
import styled from "@emotion/styled";
import {
    AppBar,
    Box,
    Divider,
    Drawer,
    IconButton,
    ListItemButton,
    ListItemIcon,
    ListItemText,
} from "@mui/material";
import {
    Close as CloseIcon,
    GitHub as GitHubIcon,
    Home as HomeIcon,
    Menu as MenuIcon,
} from "@mui/icons-material";
import { NewRelicIcon } from "./NewRelicIcon";
import { theme } from "../theme";

const DrawerLink = styled(Link)`
    text-decoration: none;
    color: ${theme.palette.text.secondary};
`;

export const NavigationDrawer: FC = () => {
    const [drawerOpen, setDrawerOpen] = useState(false);

    const toggleDrawer = useCallback(() => {
        setDrawerOpen(!drawerOpen);
    }, [drawerOpen]);
    const closeDrawer = useCallback(() => {
        setDrawerOpen(false);
    }, []);

    return (
        <>
            <AppBar position="sticky" sx={{ display: "flex" }}>
                <IconButton edge="start" color="inherit" onClick={toggleDrawer}>
                    <MenuIcon />
                </IconButton>
            </AppBar>

            <Drawer
                anchor="left"
                open={drawerOpen}
                onClose={closeDrawer}
                sx={{ p: 2 }}
            >
                <Box p={2} height={1}>
                    <IconButton sx={{ mb: 2 }} onClick={closeDrawer}>
                        <CloseIcon />
                    </IconButton>

                    <Divider sx={{ mb: 2 }} />

                    <Box mb={2}>
                        <DrawerLink to="/">
                            <ListItemButton sx={{ minWidth: "50px" }}>
                                <ListItemIcon>
                                    <HomeIcon sx={{ color: "primary.main" }} />
                                </ListItemIcon>
                                <ListItemText primary="Home" />
                            </ListItemButton>
                        </DrawerLink>
                        <DrawerLink to={process.env.VITE_GITHUB_URL}>
                            <ListItemButton sx={{ minWidth: "50px" }}>
                                <ListItemIcon>
                                    <GitHubIcon
                                        sx={{ color: "primary.main" }}
                                    />
                                </ListItemIcon>
                                <ListItemText primary="GitHub" />
                            </ListItemButton>
                        </DrawerLink>
                        <DrawerLink to={process.env.VITE_NEW_RELIC_URL}>
                            <ListItemButton sx={{ minWidth: "50px" }}>
                                <ListItemIcon>
                                    <NewRelicIcon width={22} height={22} />
                                </ListItemIcon>
                                <ListItemText primary="New Relic" />
                            </ListItemButton>
                        </DrawerLink>
                    </Box>
                </Box>
            </Drawer>
        </>
    );
};
