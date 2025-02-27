<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash Password

    // Insert user into database
    $stmt = $conn->prepare('INSERT INTO accounts (first_name, last_name, email, password) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $first_name, $last_name, $email, $password);

    if ($stmt->execute()) {
        echo '<script>alert("Registration successful!"); window.location.href="index.php";</script>';
    } else {
        echo '<script>alert("Email already exists!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Sign Up</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>
</head>
<body>
<div class='container mt-5'>
    <h2>Sign Up</h2>
    <form method='POST'>
        <input type='text' name='first_name' class='form-control' placeholder='First Name' required><br>
        <input type='text' name='last_name' class='form-control' placeholder='Last Name' required><br>
        <input type='email' name='email' class='form-control' placeholder='Email' required><br>
        <input type='password' name='password' class='form-control' placeholder='Password' required><br>
        <button type='submit' class='btn btn-primary'>Register</button>
    </form>
    <p>Already have an account? <a href='login.php'>Login here</a></p>
</div>
</body>
</html>
