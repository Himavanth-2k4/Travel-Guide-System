<?php
session_start();
include 'db_connect.php';

$msg = '';
$msgClass = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['name'];
            $msg = "Login successful! Welcome, " . $_SESSION['user'];
            $msgClass = "success";
            // Redirect to dashboard after 2 seconds
            header("refresh:2; url=home.php");
        } else {
            $msg = "Incorrect password.";
            $msgClass = "error";
        }
    } else {
        $msg = "No user found with that email.";
        $msgClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=2021&q=80');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            background-color: rgba(249, 249,249,0.7);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        label {
            text-align: left;
            font-weight: bold;
        }
        input {
            width: 350px;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .forgot-password{
            margin-left: 10px;
            align-self: flex-end;
            font-size: 14px;
        }
        button {
            width: 370px;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
            background-color: #FF6B6B;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #FF5252;
        }
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 15px 0;
            color: black;
            font-weight: bold;
            width: 60%;
        }
        .separator::before,
        .separator::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid black;
            margin: 0 10px;
        }
        .google-login button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 250px;
            padding: 10px;
            background-color: rgba(249, 249,249,0.6);
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: black;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
        .sign-up{
            margin-top: 15px;
        }
        .google-login button img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        .message {
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WELCOME TO HOVI&JAVI</h1>
        <h3>Login to your account</h3>
        
        <?php if (!empty($msg)): ?>
            <div class="message <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <label for="username">Email</label>
                <input type="email" id="username" name="username" placeholder="Enter your email" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required maxlength="16">
            </div>
            <div class="forgot-password">
                <a href="forgot-password.php" style="text-decoration: none;">Forgot password?</a>
            </div>
            <button type="submit">Login</button>
            <div class="separator">or</div>
            <div class="google-login">
                <button type="button">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iIzQyODVmNCIgZD0iTTIyLjU2IDEyLjI1Yy0uNzgtLjc4LS4wNy0xLjUzLS4yLTIuMjVIMTJ2NC4yNmg1LjkyYy0uMjYgMS4zNy0xLjA0IDIuNTMtMi4yMSAzLjMxdjIuNzdoMy41N2MyLjA4LTEuOTIgMy4yOC00Ljc0IDMuMjgtOC4wOXoiLz48cGF0aCBmaWxsPSIjMzRhODUzIiBkPSJNMTIgMjNjMi45NyAwIDUuNDYtLjk4IDcuMjgtMi42NmwtMy41Ny0yLjc3Yy0uOTguNjYtMi4yMyAxLjA2LTMuNzEgMS4wNi0yLjg2IDAtNS4yOS0xLjkzLTYuMTYtNC41M0gyLjE4djIuODRDMy45OSAyMC41MyA3LjcgMjMgMTIgMjN6Ii8+PHBhdGggZmlsbD0iI2ZiYmMwNSIgZD0iTTUuODQgMTQuMDljLS4yMi0uNjYtLjM1LTEuMzYtLjM1LTIuMDlzLjEzLTEuNDMuMzUtMi4wOVY3LjA3SDIuMThDMS40MyA4LjU1IDEgMTAuMjIgMSAxMnMuNDMgMy40NSAxLjE4IDQuOTNsMi44NS0yLjIyLjgxLS42MnoiLz48cGF0aCBmaWxsPSIjZWE0MzM1IiBkPSJNMTIgNS4zOGMxLjYyIDAgMy4wNi41NiA0LjIxIDEuNjRsMy4xNS0zLjE1QzE3LjQ1IDIuMDkgMTQuOTcgMSAxMiAxIDcuNyAxIDMuOTkgMy40NyAyLjE4IDcuMDdsMy42NiAyLjg0Yy44Ny0yLjYgMy4zLTQuNTMgNi4xNi00LjUzeiIvPjwvc3ZnPg==" alt="Google Logo">
                    Login with Google
                </button>
            </div>
            <div class="sign-up">
                <p>Are you new? <a href="signup.php">Create an Account</a></p>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");
            
            form.addEventListener("submit", function (event) {
                const email = document.getElementById("username").value;
                const password = document.getElementById("password").value;
                
                if (!validateEmail(email)) {
                    alert("Please enter a valid email address.");
                    event.preventDefault();
                    return;
                }
                
                if (password.length < 6) {
                    alert("Password must be at least 6 characters long.");
                    event.preventDefault();
                    return;
                }
            });
            
            function validateEmail(email) {
                const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return re.test(email);
            }
        });
    </script>
</body>
</html>