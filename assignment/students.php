<?php
//include 'student_records.php'; //TEST POSTMAN
require_once 'connection.php'; // Include your database connection

// Handle POST Request (Add New User)
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone']);
    $stmt->execute();
}

// Handle PUT Request (Update User)
if (isset($_POST['PUT'])) {
    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("ssssi", $_POST['first_name'], $_POST['last_name'], $_POST['email'],$_POST['phone'], $_POST['id']);
    $stmt->execute();
}

// Handle DELETE Request (Remove User)
if (isset($_POST['DELETE'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM users WHERE id = $id");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Student Management System</h2>

        <!-- Add / Update User Form -->
        <form class="border p-3 mb-4" METHOD="POST" action="students.php">
            <input type="hidden" id="userId">
            <div class="mb-3">
                <label>First Name</label>
                <input type="text" id="firstName" class="form-control" required name="first_name">
            </div>
            <div class="mb-3">
                <label>Last Name</label>
                <input type="text" id="lastName" class="form-control" required name="last_name">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" id="email" class="form-control" required name="email">
            </div>
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" id="phone" class="form-control" required name="phone">
            </div>
            <button type="submit" class="btn btn-primary" name="add">Save User</button>
        </form>

        <!-- User Table -->
        <h2 class="text-center">Students</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php
                require_once 'connection.php';
                $result = $conn->query("SELECT * FROM users");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['first_name']}</td>
                            <td>{$row['last_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>
                                <form method='POST' action='students.php'>
                                    <input name=id value={$row['id']} type='hidden'>
                                    <input name=first_name value={$row['first_name']} type='hidden'>
                                    <input name=last_name value={$row['last_name']} type='hidden'>
                                    <input name=email value={$row['email']} type='hidden'>
                                    <input name=phone value={$row['phone']} type='hidden'>
                                    <button type='submit' class='btn btn-warning btn-sm' name='UPDATE'>Edit</button>
                                </form>
                                <form method='POST' action='students.php'>
                                    <input name=id value={$row['id']} type='hidden'>
                                    <button type='submit' class='btn btn-danger btn-sm' name='REMOVE'>Delete</button>
                                </form>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php 
        if(isset($_POST['UPDATE'])){
            echo "
                   <!-- Edit User Modal -->
                    <div class='modal fade' id='editUserModal' tabindex='-1' aria-labelledby='editUserModalLabel' aria-hidden='true'>
                        <div class='modal-dialog'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='editUserModalLabel'>Edit User</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    <form id='editUserForm' method='POST' action='students.php'>
                                        <input type='hidden' name=id value=".$_POST['id'].">
                                        <div class='mb-3'>
                                            <label for='firstName' class='form-label'>First Name</label>
                                            <input type='text' class='form-control' name='first_name' required value=".$_POST['first_name'].">
                                        </div>
                                        <div class='mb-3'>
                                            <label for='lastName' class='form-label'>Last Name</label>
                                            <input type='text' class='form-control' name='last_name' required value=".$_POST['last_name'].">
                                        </div>
                                        <div class='mb-3'>
                                            <label for='email' class='form-label'>Email</label>
                                            <input type='email' class='form-control' name='email' required value=".$_POST['email'].">
                                        </div>
                                        <div class='mb-3'>
                                            <label for='phone' class='form-label'>Phone</label>
                                            <input type='text' class='form-control' name='phone' required value=".$_POST['phone'].">
                                        </div>
                                        <button type='submit' class='btn btn-primary' name='PUT'>Update User</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
        }
    ?>

    <?php 
        if(isset($_POST['REMOVE'])){
            echo "
                <!-- Delete User Confirmation Modal -->
                <div class='modal fade' id='deleteUserModal' tabindex='-1' aria-labelledby='deleteUserModalLabel' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='deleteUserModalLabel'>Confirm Deletion</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <p>Are you sure you want to delete this user?</p>
                                <input type='hidden' id='deleteUserId'>
                            </div>
                            <div class='modal-footer'>
                                <button type='submit' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                <form method='POST' action='students.php'>
                                    <input type=hidden name='id' value=".$_POST['id'].">
                                    <button type='submit' class='btn btn-danger' name='DELETE'>Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                ";
        }
    ?>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>                

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        myModal.show();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myDeleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        myDeleteModal.show();
    });
</script>
 

</html>
