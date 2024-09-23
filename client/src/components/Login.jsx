import React, { useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";

const Login = () => {
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });

  const [errors, setErrors] = useState({});
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.post(
        "http://localhost:8000/api/login",
        formData
      );
      console.log("Responce Data", response.data);

      localStorage.setItem("token", response.data.token);

      alert(response.data.message);
      navigate("/profile");
    } catch (error) {
      console.error("Error response:", error.response);
      if (error.response && error.response.data.errors) {
        setErrors(error.response.data.errors);
      } else {
        alert("Login Failed!");
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
        Login
      </h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-3 text-start">
          <label
            className="form-label"
            style={{
              color: "#4916eb",
            }}
          >
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
          <label
            className="form-label"
            style={{
              color: "#4916eb",
            }}
          >
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
        <button
          type="submit"
          className="btn btn-primary w-100 mt-2"
          style={{
            background: "linear-gradient(to right, #4916eb, #3d12c5)",
            border: "none",
          }}
        >
          Login
        </button>
        <p className="text-center mt-2">
          Don't have an account? <Link to="/">Register here</Link>
        </p>
      </form>
    </div>
  );
};

export default Login;
