import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { CssBaseline, ThemeProvider, createTheme } from "@mui/material";
import Home from "./scenes/Home";
import DoctorSchedule from "./scenes/DoctorSchedule";

const defaultTheme = createTheme(); // Define your theme here (if you want to customize)

function App() {
  return (
    <ThemeProvider theme={defaultTheme}>
      <CssBaseline /> {/* Ensures consistent baseline styles */}
      <Router>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/doctor/:doctorId" element={<DoctorSchedule />} />
        </Routes>
      </Router>
    </ThemeProvider>
  );
}

export default App;
