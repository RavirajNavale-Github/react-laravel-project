<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #e8e7ff;
            border-radius: 10px;
            width: 100%;
            max-width: 500px; /* Limit maximum width */
            padding: 40px;
            margin: 20px; /* Add vertical and horizontal margins */
        }
        h2 {
            color: #1b076e;
            font-weight: 600;
        }
        .form-label {
            color: #4916eb;
        }
        .form-control:focus {
            border-color: #4916eb;
            box-shadow: none;
        }
        .btn-primary {
            background: linear-gradient(to right, #4916eb, #3d12c5);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #3d12c5, #4916eb);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-5">Register</h2>
        <form id="registerForm">
            <div class="mb-3 text-start">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile" class="form-control" id="profile">
                <div class="text-danger" id="profileError"></div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
                <div class="text-danger" id="nameError"></div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
                <div class="text-danger" id="emailError"></div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Mobile</label>
                <input type="text" name="mobile" class="form-control" id="mobile" value="+91 " required>
                <div class="text-danger" id="mobileError"></div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Company Position</label>
                <input type="text" name="company_position" class="form-control" id="company_position">
                <div class="text-danger" id="companyPositionError"></div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
                <div class="text-danger" id="passwordError"></div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <p class="text-center mt-2">
                Already have an account? <a href="/login">Login here</a>
            </p>
        </form>
    </div>

    <!-- Bootstrap 5 JS & dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Create FormData object from the form
            const formData = new FormData(this);
            let mobile = formData.get('mobile').replace('+91 ', '');
            formData.set('mobile', `+91${mobile}`);

            fetch('http://localhost:8000/api/register', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    document.getElementById('profileError').textContent = data.errors.profile ? data.errors.profile[0] : '';
                    document.getElementById('nameError').textContent = data.errors.name ? data.errors.name[0] : '';
                    document.getElementById('emailError').textContent = data.errors.email ? data.errors.email[0] : '';
                    document.getElementById('mobileError').textContent = data.errors.mobile ? data.errors.mobile[0] : '';
                    document.getElementById('companyPositionError').textContent = data.errors.company_position ? data.errors.company_position[0] : '';
                    document.getElementById('passwordError').textContent = data.errors.password ? data.errors.password[0] : '';
                } else {
                    alert('Registration successful');
                    window.location.href = '/login';
                }
            })
            .catch(error => {
                alert('Error during registration: ' + error.message);
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
