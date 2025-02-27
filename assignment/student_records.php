<?php // TO POSTMAN
//header("Content-Type: application/json");
require_once 'connection.php'; // Include your database connection

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getUser($_GET['id']);
        } else {
            getUsers();
        }
        break;
    case 'POST':
        createUser();
        break;
    case 'PUT':
        updateUser();
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteUser($_GET['id']);
        } else {
            echo json_encode(["status" => "error", "message" => "User ID required"]);
        }
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
        break;
}

function getUsers()
{
    global $conn;
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);
}

function getUser($id)
{
    global $conn;
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data ? $data : ["status" => "error", "message" => "User not found"]);
}

function createUser()
{
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['first_name']) || !isset($input['last_name']) || !isset($input['email']) || !isset($input['phone'])) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        return;
    }

    $query = "INSERT INTO users (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $input['first_name'], $input['last_name'], $input['email'], $input['phone']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User created"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create user"]);
    }
}

function updateUser()
{
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id']) || !isset($input['first_name']) || !isset($input['last_name']) || !isset($input['email']) || !isset($input['phone'])) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        return;
    }

    $query = "UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $input['first_name'], $input['last_name'], $input['email'], $input['phone'], $input['id']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user"]);
    }
}

function deleteUser($id)
{
    global $conn;
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete user"]);
    }
}
?>