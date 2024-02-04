import React from "react";
import { Modal, Typography, TextField, Button, Box } from "@mui/material";
import { useForm } from "react-hook-form";
import axios from "axios";

const style = {
  position: "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: 600,
  backgroundColor: "background.paper",
  border: "2px solid #000",
  boxShadow: 24,
  p: 4,
};

function AppointmentModal({
  isOpen = false,
  onClose = () => {},
  doctorId,
  dateTime,
  onSuccess = () => {},
  onFailure = () => {},
}) {
  const {
    register,
    handleSubmit,
    formState: { errors },
    reset,
  } = useForm();

  const onSubmit = async (data) => {
    try {
      // Append doctorId and dateTime from props to the form data
      const fullData = {
        ...data,
        doctorId: parseInt(doctorId),
        dateTime: dateTime,
      };

      const response = await axios.post(
        "http://localhost/appointments/create",
        fullData,
      );

      onSuccess();

      reset(); // Reset the form fields
      onClose(); // Close modal after submission
    } catch (error) {
      onFailure();
      console.error("Error submitting the form: ", error);
      // Handle error
    }
  };

  return (
    <Modal
      keepMounted
      open={isOpen}
      onClose={onClose}
      aria-labelledby="keep-mounted-modal-title"
      aria-describedby="keep-mounted-modal-description"
    >
      <Box sx={style}>
        <Typography variant="h5">Book an Appointment</Typography>
        <form onSubmit={handleSubmit(onSubmit)} noValidate autoComplete="off">
          <TextField
            {...register("patientFirstName", {
              required: "First name is required",
            })}
            error={!!errors.patientFirstName}
            helperText={errors.patientFirstName?.message}
            label="First Name"
            fullWidth
            margin="normal"
          />
          <TextField
            {...register("patientLastName", {
              required: "Last name is required",
            })}
            error={!!errors.patientLastName}
            helperText={errors.patientLastName?.message}
            label="Last Name"
            fullWidth
            margin="normal"
          />
          <TextField
            {...register("email", {
              required: "Email is required",
              pattern: {
                value: /^[^@ ]+@[^@ ]+\.[^@ .]{2,}$/,
                message: "Email is not valid",
              },
            })}
            error={!!errors.email}
            helperText={errors.email?.message}
            label="Email"
            type="email"
            fullWidth
            margin="normal"
          />
          <TextField
            {...register("phoneNumber", {
              required: "Phone number is required",
              pattern: {
                value: /^\+?([0-9]{1,3})\)?([0-9]{6,12})$/,
                message: "Phone number is not valid",
              },
            })}
            error={!!errors.phoneNumber}
            helperText={errors.phoneNumber?.message}
            label="Phone Number"
            fullWidth
            margin="normal"
          />
          <Button type="submit" variant="contained" sx={{ mt: 2 }}>
            Book Appointment
          </Button>
        </form>
      </Box>
    </Modal>
  );
}

export default AppointmentModal;
