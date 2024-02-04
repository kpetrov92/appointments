import React from "react";
import AppBar from "@mui/material/AppBar";
import Toolbar from "@mui/material/Toolbar";
import Button from "@mui/material/Button";
import Typography from "@mui/material/Typography";

function Layout({ callback, children }) {
  return (
    <>
      <AppBar position="static">
        <Toolbar>
          <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
            Appointment Booking App
          </Typography>
          {callback && (
            <Button
              sx={{ backgroundColor: "grey", color: "white" }}
              onClick={callback}
            >
              Back
            </Button>
          )}
        </Toolbar>
      </AppBar>
      {children}
    </>
  );
}

export default Layout;
