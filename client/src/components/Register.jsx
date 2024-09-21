import React, { useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";

const Register = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    profile: null,
    name: "",
    email: "",
    mobile: "+91 ",
    company_position: "",
    password: "",
    password_confirmation: "",
  });

  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name === "profile") {
      setFormData({ ...formData, profile: e.target.files[0] });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const mobileWithoutPrefix = formData.mobile.replace("+91 ", "");

    const validatedData = {
      ...formData,
      mobile: `+91${mobileWithoutPrefix}`,
    };

    const form = new FormData();
    for (const key in validatedData) {
      form.append(key, validatedData[key]);
    }

    try {
      const response = await axios.post(
        "http://localhost:8000/api/register",
        form,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      console.log(response.data);
      alert("Registration Successful!");
      navigate("/login");
    } catch (error) {
      if (error.response && error.response.data.errors) {
        setErrors(error.response.data.errors);
      } else {
        alert("Registration Failed!");
        console.error("Registration failed", error);
      }
    }
  };

  return (
    <div
      className="container mt-5 p-5"
      style={{
        background: "#e8e7ff",
        borderRadius: "10px",
        width: "50%",
      }}
    >
      <h2
        className="text-center mb-5"
        style={{
          color: "#1b076e",
          fontWeight: "600",
        }}
      >
        Register
      </h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-3 text-start">
          <label className="form-label" style={{ color: "#4916eb" }}>
            Profile Picture
          </label>
          <input
            type="file"
            name="profile"
            className="form-control"
            onChange={handleChange}
          />
          {errors.profile && (
            <div className="text-danger">{errors.profile[0]}</div>
          )}
        </div>
        <div className="mb-3 text-start">
          <label className="form-label" style={{ color: "#4916eb" }}>
            Name
          </label>
          <input
            type="text"
            name="name"
            className="form-control"
            onChange={handleChange}
            required
          />
          {errors.name && <div className="text-danger">{errors.name[0]}</div>}
        </div>
        <div className="mb-3 text-start">
          <label className="form-label" style={{ color: "#4916eb" }}>
            Email
          </label>
          <input
            type="email"
            name="email"
            className="form-control"
            onChange={handleChange}
            required
          />
          {errors.email && <div className="text-danger">{errors.email[0]}</div>}
        </div>
        <div className="mb-3 text-start">
          <label className="form-label" style={{ color: "#4916eb" }}>
            Mobile
          </label>
          <input
            type="text"
            name="mobile"
            className="form-control"
            value={formData.mobile}
            onChange={handleChange}
            required
          />
          {errors.mobile && (
            <div className="text-danger">{errors.mobile[0]}</div>
          )}
        </div>
        <div className="mb-3 text-start">
          <label className="form-label" style={{ color: "#4916eb" }}>
            Company Position
          </label>
          <input
            type="text"
            name="company_position"
            className="form-control"
            onChange={handleChange}
          />
          {errors.company_position && (
            <div className="text-danger">{errors.company_position[0]}</div>
          )}
        </div>
        <div className="mb-3 text-start">
          <label className="form-label" style={{ color: "#4916eb" }}>
            Password
          </label>
          <input
            type="password"
            name="password"
            className="form-control"
            onChange={handleChange}
            required
          />
          {errors.password && (
            <div className="text-danger">{errors.password[0]}</div>
          )}
        </div>
        <div className="mb-3 text-start">
          <label className="form-label" style={{ color: "#4916eb" }}>
            Confirm Password
          </label>
          <input
            type="password"
            name="password_confirmation"
            className="form-control"
            onChange={handleChange}
            required
          />
        </div>
        <button
          type="submit"
          className="btn btn-primary w-100 t-2"
          style={{
            background: "linear-gradient(to right, #4916eb, #3d12c5)",
            border: "none",
          }}
        >
          Register
        </button>
        <p className="text-center mt-2">
          Already have an account? <Link to="/login">Login here</Link>
        </p>
      </form>
    </div>
  );
};

export default Register;
