import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import { Modal } from "react-bootstrap";

const Profile = () => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [userData, setUserData] = useState({});
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUser = async () => {
      const token = localStorage.getItem("token");

      if (!token) {
        alert("Please Login First!");
        navigate("/login");
        return;
      }

      try {
        const response = await axios.get("http://localhost:8000/api/user", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setUser(response.data);
        setUserData({ ...response.data }); // Initialize userData with user data
      } catch (error) {
        console.error("Failed to fetch user", error);
        navigate("/login");
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
      localStorage.removeItem("token");
      navigate("/login");
    } catch (error) {
      console.error("Logout failed", error);
    }
  };

  const handleDelete = async () => {
    try {
      const token = localStorage.getItem("token");
      await axios.delete("http://localhost:8000/api/user", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      localStorage.removeItem("token");
      alert("Account deleted successfully!");
      navigate("/");
    } catch (error) {
      console.error("Delete failed", error);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setUserData({ ...userData, [name]: value });
  };

  const handleFileChange = (e) => {
    setUserData({ ...userData, profile: e.target.files[0] });
  };

  const handleUpdate = async () => {
    const token = localStorage.getItem("token");
    const id = user.id;

    const formData = new FormData();
    if (userData.profile) {
      formData.append("profile", userData.profile);
    }
    formData.append("name", userData.name);
    formData.append("email", userData.email);
    formData.append("mobile", userData.mobile);
    formData.append("company_position", userData.company_position);

    try {
      const response = await fetch(`http://localhost:8000/api/user/${id}`, {
        method: "POST",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: formData,
      });

      if (!response.ok) {
        throw new Error("Error updating user: " + response.statusText);
      }

      const data = await response.json();
      alert("User updated successfully");
      setUserData(data.user); // Update userData with the new user data
      setShowModal(false);
      window.location.reload();
    } catch (error) {
      alert("Error updating user: " + error.message);
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
        <span
          style={{
            float: "right",
            cursor: "pointer",
            fontSize: "1.5rem",
          }}
          onClick={() => setShowModal(true)}
        >
          &#x022EE;
        </span>
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

      {/* Modal for updating user data */}
      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Update Profile</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <form>
            <div className="mb-3">
              <label className="form-label">Name</label>
              <input
                type="text"
                name="name"
                className="form-control"
                value={userData.name}
                onChange={handleChange}
                required
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Email</label>
              <input
                type="email"
                name="email"
                className="form-control"
                value={userData.email}
                onChange={handleChange}
                required
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Mobile</label>
              <input
                type="text"
                name="mobile"
                className="form-control"
                value={userData.mobile}
                onChange={handleChange}
                required
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Company Position</label>
              <input
                type="text"
                name="company_position"
                className="form-control"
                value={userData.company_position}
                onChange={handleChange}
                required
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Profile Image</label>
              <input
                type="file"
                name="profile"
                className="form-control"
                onChange={handleFileChange}
              />
            </div>
          </form>
        </Modal.Body>
        <Modal.Footer>
          <button
            className="btn btn-secondary"
            onClick={() => setShowModal(false)}
          >
            Close
          </button>
          <button className="btn btn-primary" onClick={handleUpdate}>
            Save Changes
          </button>
          <button className="btn btn-danger" onClick={handleDelete}>
            Delete Account
          </button>
        </Modal.Footer>
      </Modal>
    </div>
  );
};

export default Profile;
