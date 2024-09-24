<?php
header("Content-Type: application/json");

// Database connection details
$servername = "localhost";
$username = "admin";
$password = "password";
$database = "expert_speaker_finder";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Handle POST request to submit a new speaker
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/speakers') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO speakers (name, expertise, background, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $data['name'], $data['expertise'], $data['background']);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Speaker submitted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to submit speaker: ' . $stmt->error]);
    }
    $stmt->close();
}

// Handle GET request to retrieve all speakers (admin only)
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/speakers') {
    // In a real application, you would check for admin authentication here
    $sql = "SELECT * FROM speakers";
    $result = $conn->query($sql);
    $speakers = array();
    while ($row = $result->fetch_assoc()) {
        $speakers[] = $row;
    }
    echo json_encode($speakers);
}

// Handle POST request to accept a speaker
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('/\/api\/speakers\/(.+)\/accept/', $_SERVER['REQUEST_URI'], $matches)) {
    $speaker_id = $matches[1];
    $sql = "UPDATE speakers SET status = 'accepted' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $speaker_id);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Speaker accepted']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to accept speaker: ' . $stmt->error]);
    }
    $stmt->close();
}

// Handle POST request to reject a speaker
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('/\/api\/speakers\/(.+)\/reject/', $_SERVER['REQUEST_URI'], $matches)) {
    $speaker_id = $matches[1];
    $sql = "UPDATE speakers SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $speaker_id);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Speaker rejected']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to reject speaker: ' . $stmt->error]);
    }
    $stmt->close();
}

// Handle POST request for admin login
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/admin/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $data['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($data['password'], $user['password_hash'])) {
            echo json_encode(['token' => 'fake_admin_token']);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
    $stmt->close();
}

// Handle 404 for undefined routes
else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}

$conn->close();