<?php
session_start();
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT id, first_name, last_name, password FROM accounts WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['user_name'] = $result['first_name'] . ' ' . $result['last_name'];
        echo '<script>alert("Login successful!"); window.location.href="students.php";</script>';
    } else {
        echo '<script>alert("Invalid email or password!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Login</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>
</head>
<body>
<div class='container mt-5'>
    <h2>Login</h2>
    <form method='POST'>
        <input type='email' name='email' class='form-control' placeholder='Email' required><br>
        <input type='password' name='password' class='form-control' placeholder='Password' required><br>
        <button type='submit' class='btn btn-primary'>Login</button>
    </form>
    <p>Don't have an account? <a href='signup.php'>Sign up here</a></p>
</div>
</body>
</html>
