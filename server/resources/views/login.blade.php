<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #e8e7ff;
            border-radius: 10px;
            width: 100%;
            max-width: 400px; /* Limit maximum width */
            padding: 40px;
            margin: 0 20px; /* Add horizontal margins */
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
        <h2 class="text-center mb-5">Login</h2>
        <form id="loginForm">
            <div class="mb-3 text-start">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
                <div class="text-danger" id="emailError"></div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
                <div class="text-danger" id="passwordError"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
            <p class="text-center mt-2">
                Don't have an account? <a href="/registration">Register here</a>
            </p>
        </form>
    </div>

    <!-- Bootstrap 5 JS & dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const jsonData = JSON.stringify(Object.fromEntries(formData.entries()));

            fetch('http://localhost:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: jsonData,
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                localStorage.setItem('token', data.token);
                alert(data.message);
                window.location.href = '/profile';
            })
            .catch(error => {
                console.error('Error:', error);

                // Display errors if any
                document.getElementById('emailError').textContent = error.response?.data?.errors?.email || '';
                document.getElementById('passwordError').textContent = error.response?.data?.errors?.password || '';

                if (!error.response || !error.response.data.errors) {
                    alert('Login Failed!');
                }
            });
        });
    </script>
</body>
</html>
