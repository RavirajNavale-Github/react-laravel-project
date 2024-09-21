import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

const Profile = () => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get("http://localhost:8000/api/user", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setUser(response.data);
      } catch (error) {
        console.error("Failed to fetch user", error);
        navigate("/login"); // Redirect to login if there's an error
      } finally {
        setLoading(false);
      }
    };

    fetchUser();
  }, [navigate]);

  const handleLogout = async () => {
    try {
      const token = localStorage.getItem("token");
      await axios.post(
        "http://localhost:8000/api/logout",
        {},
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      localStorage.removeItem("token"); // Remove token from local storage
      navigate("/login"); // Redirect to login page
    } catch (error) {
      console.error("Logout failed", error);
    }
  };

  if (loading) {
    return <div>Loading...</div>;
  }

  if (!user) {
    return <div>No user found!</div>;
  }

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
        className="text-center mb-4"
        style={{
          color: "#1b076e",
          fontWeight: "600",
        }}
      >
        User Profile
      </h2>
      <img
        src={
          user.profile
            ? `http://localhost:8000/storage/${user.profile}`
            : "placeholder.jpg"
        }
        alt="Profile"
        style={{
          width: "150px",
          height: "150px",
          borderRadius: "75px",
          display: "block",
          margin: "0 auto",
        }}
      />
      <div className="mb-3 text-start mt-4">
        <strong>Name:</strong> {user.name}
      </div>
      <div className="mb-3 text-start">
        <strong>Email:</strong> {user.email}
      </div>
      <div className="mb-3 text-start">
        <strong>Mobile:</strong> {user.mobile}
      </div>
      <div className="mb-3 text-start">
        <strong>Company Position:</strong> {user.company_position}
      </div>
      <button
        onClick={handleLogout}
        className="btn btn-danger w-100 mt-4"
        style={{
          background: "linear-gradient(to right, #ff4d4d, #ff1a1a)",
          border: "none",
        }}
      >
        Logout
      </button>
    </div>
  );
};

export default Profile;
