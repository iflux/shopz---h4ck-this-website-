<?php
require_once '../includes/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($method === 'GET') {
    if ($id) {
        $query = "SELECT id, username, email, role, address, created_at FROM users WHERE id = $id";
    } else {
        $query = "SELECT id, username, email, role, address, created_at FROM users";
    }
    
    $result = mysqli_query($conn, $query);
    $users = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    if ($id && count($users) === 1) {
        echo json_encode($users[0]);
    } else {
        echo json_encode(array(
            'flag' => 'FLAG{api_no_authentication}',
            'users' => $users
        ));
    }
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $username = $data['username'];
    $email = $data['email'];
    $password = md5($data['password']);
    $role = isset($data['role']) ? $data['role'] : 'user';
    
    $insert = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    
    if (mysqli_query($conn, $insert)) {
        echo json_encode(array(
            'success' => true,
            'id' => mysqli_insert_id($conn),
            'flag' => 'FLAG{mass_assignment_admin}'
        ));
    } else {
        echo json_encode(array('error' => mysqli_error($conn)));
    }
}

if ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($id) {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = "$key = '$value'";
        }
        $update = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = $id";
        
        if (mysqli_query($conn, $update)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => mysqli_error($conn)));
        }
    }
}
?>
