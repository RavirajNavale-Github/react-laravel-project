import React, { useState } from "react";
import axios from "axios";

const UpdateModal = ({ user, setShowModal, setUser }) => {
  const [formData, setFormData] = useState({
    profile: null,
    name: user.name,
    email: user.email,
    mobile: user.mobile,
    company_position: user.company_position,
    password: "",
    password_confirmation: "",
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name === "profile") {
      setFormData({ ...formData, profile: e.target.files[0] });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleUpdate = async () => {
    const token = localStorage.getItem("token");
    const form = new FormData();
    for (const key in formData) {
      form.append(key, formData[key]);
    }

    try {
      const response = await axios.put("http://localhost:8000/api/user", form, {
        headers: {
          "Content-Type": "multipart/form-data",
          Authorization: `Bearer ${token}`,
        },
      });
      alert("Profile updated successfully!");
      setUser(response.data.user);
      setShowModal(false);
    } catch (error) {
      console.error("Error updating profile:", error);
    }
  };

  return (
    <div
      className="modal show"
      style={{
        display: "block",
        position: "fixed",
        top: "0",
        left: "0",
        width: "100%",
        height: "100%",
      }}
    >
      <div className="modal-dialog">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title">Update Profile</h5>
            <button
              type="button"
              className="close"
              onClick={() => setShowModal(false)}
            >
              &times;
            </button>
          </div>
          <div className="modal-body">
            <div className="mb-3">
              <label className="form-label">Profile Picture</label>
              <input
                type="file"
                name="profile"
                className="form-control"
                onChange={handleChange}
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Name</label>
              <input
                type="text"
                name="name"
                className="form-control"
                value={formData.name}
                onChange={handleChange}
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Email</label>
              <input
                type="email"
                name="email"
                className="form-control"
                value={formData.email}
                onChange={handleChange}
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Mobile</label>
              <input
                type="text"
                name="mobile"
                className="form-control"
                value={formData.mobile}
                onChange={handleChange}
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Company Position</label>
              <input
                type="text"
                name="company_position"
                className="form-control"
                value={formData.company_position}
                onChange={handleChange}
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Password</label>
              <input
                type="password"
                name="password"
                className="form-control"
                onChange={handleChange}
              />
            </div>
            <div className="mb-3">
              <label className="form-label">Confirm Password</label>
              <input
                type="password"
                name="password_confirmation"
                className="form-control"
                onChange={handleChange}
              />
            </div>
          </div>
          <div className="modal-footer">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setShowModal(false)}
            >
              Cancel
            </button>
            <button
              type="button"
              className="btn btn-primary"
              onClick={handleUpdate}
            >
              Update
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default UpdateModal;
