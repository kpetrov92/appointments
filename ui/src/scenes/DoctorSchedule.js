import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import axios from "axios";
import { LocalizationProvider, StaticDatePicker } from "@mui/x-date-pickers";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import {
  Grid,
  Box,
  Typography,
  Button,
  List,
  ListItem,
  ListItemText,
  TextField,
} from "@mui/material";
import dayjs from "dayjs";
import { useNavigate } from "react-router-dom";
import Layout from "../components/Layout";
import AppointmentModal from "../components/AppointmentModal";

function DoctorSchedule() {
  const { doctorId } = useParams();
  const navigate = useNavigate();
  const [selectedDate, setSelectedDate] = useState(new Date());
  const [timeSlots, setTimeSlots] = useState([]);
  const [slotTime, setSlotTime] = useState("");
  const [loading, setLoading] = useState(true);

  const [chosenTimeSlot, setChosenTimeSlot] = useState("");
  const [isModalOpen, setIsModalOpen] = useState(false); // Modal state

  useEffect(() => {
    const formattedDate = selectedDate.toISOString().split("T")[0]; // Format date as YYYY-MM-DD
    axios
      .get(
        `http://localhost/doctor/available-slots?doctorId=${doctorId}&dateTime=${formattedDate}`,
      )
      .then((response) => {
        setTimeSlots(response.data);
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error fetching available slots", error);
      });
  }, [selectedDate, doctorId]);

  const handleAppointmentClick = (slot) => {
    setSlotTime(slot.time);
    const formattedDateTime = `${dayjs(selectedDate).format("YYYY-MM-DD")} ${slot.time}:00`;
    setChosenTimeSlot(formattedDateTime);

    setIsModalOpen(true);
  };

  const updateTimeSlotsAfterBooking = () => {
    setTimeSlots((prevTimeSlots) =>
      prevTimeSlots.map((slot) =>
        slot.time === slotTime ? { ...slot, status: 1 } : slot,
      ),
    );
  };

  return (
    <Layout callback={() => navigate("/")}>
      <LocalizationProvider dateAdapter={AdapterDayjs}>
        <Grid container spacing={2} sx={{ marginTop: 5 }}>
          <Grid item xs={12} md={6}>
            <StaticDatePicker
              displayStaticWrapperAs="desktop"
              openTo="day"
              defaultValue={dayjs(selectedDate)}
              onChange={(newValue) => {
                setSelectedDate(newValue.$d);
              }}
              renderInput={(params) => <TextField {...params} />}
            />
          </Grid>
          {!loading && (
            <Grid item xs={12} md={4}>
              <Box sx={{ mx: 4 }}>
                <Typography variant="h6">Available Time Slots</Typography>
                <List>
                  {timeSlots && timeSlots.length > 0 ? (
                    timeSlots.map((slot, index) => (
                      <ListItem key={index} disablePadding>
                        <Button
                          fullWidth
                          variant="outlined"
                          sx={{
                            backgroundColor:
                              slot.status === 0 ? "#5CB85C" : "#969696",
                            my: 1,
                            color: "#fff",
                            "&:hover": {
                              backgroundColor:
                                slot.status === 0 ? "#449D44" : "#7F7F7F",
                            },
                          }}
                          disabled={slot.status === 1}
                          onClick={() => handleAppointmentClick(slot)}
                        >
                          <ListItemText primary={slot.time} />
                        </Button>
                      </ListItem>
                    ))
                  ) : (
                    <Box sx={{ padding: 3, backgroundColor: "#969696" }}>
                      <Typography>Non-working day for the clinic</Typography>
                    </Box>
                  )}
                </List>
              </Box>
            </Grid>
          )}
        </Grid>
      </LocalizationProvider>
      <AppointmentModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        doctorId={doctorId}
        dateTime={chosenTimeSlot}
        updateTimeSlots={updateTimeSlotsAfterBooking}
      />
    </Layout>
  );
}

export default DoctorSchedule;
