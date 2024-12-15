<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .login-container {
        margin: 50px auto;
        max-width: 400px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #003580;
    }

    .login-container form {
        display: flex;
        flex-direction: column;
    }

    .login-container input {
        margin-bottom: 15px;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .login-container button {
        background-color: #003580;
        color: white;
        padding: 10px;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .login-container button:hover {
        background-color: #002a60;
    }

    .login-container p {
        text-align: center;
        margin-top: 10px;
    }

    .login-container a {
        color: #003580;
        text-decoration: none;
    }

    .login-container a:hover {
        text-decoration: underline;
    }
</style>
<?php
require_once "./navigator.php";
?>

<div class="login-container">
    <h2>Login to your Account</h2>
    <form action="database/login.php" method="POST">
        <input name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>You don't have an account? <a href="/signup.php">Sign Up now!</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>