<?php
// Start the session
session_start();

// Database connection details (same as in api.php and admin_dashboard.php)
$servername = "localhost";
$username = "admin";
$password = "password";
$database = "expert_speaker_finder";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the users table to check the credentials
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            // Set the admin flag in the session
            $_SESSION['is_admin'] = true;
            header('Location: admin_dashboard.php');
            exit;
        } else {
            // Display an error message
            $error_message = 'Invalid username or password.';
        }
    } else {
        // Display an error message
        $error_message = 'Invalid username or password.';
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Speaker Finder - Admin Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Expert Speaker Finder</h1>
            <p>Admin Login</p>
            <a href="index.php" class="home-link">Back to Home</a>
        </div>
    </header>

    <main class="container">
        <section id="admin-login" class="admin-section">
            <h2>Admin Login</h2>
            <form id="admin-login-form" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php if (isset($error_message)) { ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php } ?>
                <button type="submit">Login</button>
            </form>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 University Name. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>