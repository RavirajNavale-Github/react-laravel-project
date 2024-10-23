<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #1155cb, #a6c0fe);
            font-family: Arial, sans-serif;
            height: 100vh;
        }
        .container {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }
        .form-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #1155cb;
        }
        .btn-danger {
            background: linear-gradient(45deg, #ff4d4d, #ff1a1a);
            border: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4" style="color: #1b076e; font-weight: 600;">
                User Profile
                <span
                    style="float: right; cursor: pointer; font-size: 1.5rem;"
                    onclick="showModal()"
                >
                    &#x2630; <!-- Icon to open the modal -->
                </span>
            </h2>
            <img id="profile-image" src="placeholder.jpg" alt="Profile"
                 style="width: 150px; height: 150px; border-radius: 75px; border: 5px solid #4916eb; display: block; margin: 0 auto;" />
            <div class="mb-3 text-start mt-4">
                <strong>Name:</strong> <span id="user-name">John Doe</span>
            </div>
            <div class="mb-3 text-start">
                <strong>Email:</strong> <span id="user-email">johndoe@example.com</span>
            </div>
            <div class="mb-3 text-start">
                <strong>Mobile:</strong> <span id="user-mobile">+91 1234567890</span>
            </div>
            <div class="mb-3 text-start">
                <strong>Company Position:</strong> <span id="user-position">Developer</span>
            </div>
            <button onclick="handleLogout()" class="btn btn-danger w-100 mt-4">
                Logout
            </button>
        </div>
    </div>

    <!-- Modal for updating user data -->
    <div class="modal" tabindex="-1" id="updateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Profile</h5>
                    <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company Position</label>
                            <input type="text" name="company_position" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeModal()">Close</button>
                    <button class="btn btn-primary" onclick="handleUpdate()">Save Changes</button>
                    <button class="btn btn-danger" onclick="handleDelete()">Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS & dependencies (Popper & Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>

        let userId;
        // Fetch user data on page load
        document.addEventListener('DOMContentLoaded', fetchUser);

        async function fetchUser() {
            const token = localStorage.getItem("token");

            if (!token) {
                alert("Please Login First!");
                window.location.href = "/login"; // Redirect to login
                return;
            }

            try {
                const response = await fetch("http://localhost:8000/api/user", {
                    headers: {
                        "Authorization": `Bearer ${token}`
                    }
                });
                const user = await response.json();
                // console.log("UserId",user.id)
                userId = user.id; 
                document.getElementById("user-name").innerText = user.name;
                document.getElementById("user-email").innerText = user.email;
                document.getElementById("user-mobile").innerText = user.mobile;
                document.getElementById("user-position").innerText = user.company_position;
                document.getElementById("profile-image").src = `http://localhost:8000/storage/${user.profile || 'placeholder.jpg'}`;
            } catch (error) {
                console.error("Failed to fetch user", error);
                window.location.href = "/login"; // Redirect to login on error
            }
        }

        function showModal() {
    // Prepopulate the modal fields with current user data
    const userName = document.getElementById("user-name").innerText;
    const userEmail = document.getElementById("user-email").innerText;
    const userMobile = document.getElementById("user-mobile").innerText;
    const userPosition = document.getElementById("user-position").innerText;

    document.querySelector('input[name="name"]').value = userName;
    document.querySelector('input[name="email"]').value = userEmail;
    document.querySelector('input[name="mobile"]').value = userMobile;
    document.querySelector('input[name="company_position"]').value = userPosition;

    const modal = new bootstrap.Modal(document.getElementById('updateModal'));
    modal.show();
}

        function closeModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
            modal.hide();
        }

        async function handleLogout() {
            const token = localStorage.getItem("token");
            localStorage.removeItem("token");
            window.location.href = "/login"; // Redirect to login
        }

        async function handleUpdate() {
    const token = localStorage.getItem("token");
    const formData = new FormData(document.getElementById('update-form'));
    const id = userId;

    try {
        const response = await fetch(`http://localhost:8000/api/user/${id}`, {
            method: "POST", // Ensure you are using the correct HTTP method
            headers: {
                "Authorization": `Bearer ${token}`,
            },
            body: formData
        });

        if (!response.ok) throw new Error("Error updating user");
        alert("User updated successfully");
        closeModal();
        fetchUser(); // Refresh user data
    } catch (error) {
        alert("Error updating user: " + error.message);
    }
}

async function handleDelete() {
    const token = localStorage.getItem("token");
    const id = userId;

    if (confirm("Are you sure you want to delete your account?")) {
        try {
            const response = await fetch(`http://localhost:8000/api/user/${id}`, {
                method: "DELETE",
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });

            if (!response.ok) {
                const errorResponse = await response.json(); // Capture error message
                throw new Error(errorResponse.message || "Error deleting user");
            }

            alert("Account deleted successfully!");
            localStorage.removeItem("token");
            window.location.href = "/registration"; 
        } catch (error) {
            alert("Error deleting account: " + error.message);
        }
    }
}
    </script>
</body>
</html>
