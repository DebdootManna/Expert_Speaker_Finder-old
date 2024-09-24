<?php
// Database connection details (same as in api.php)
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

// Fetch the speaker submissions from the database
$sql = "SELECT * FROM speakers";
$result = $conn->query($sql);
$speakers = array();
while ($row = $result->fetch_assoc()) {
    $speakers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Speaker Finder - Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Expert Speaker Finder</h1>
            <p>Admin Dashboard</p>
            <a href="index.php" class="home-link">Back to Home</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </header>

    <main class="container">
        <section id="admin-dashboard" class="admin-section">
            <h2>Speaker Submissions</h2>
            <div id="speaker-submissions">
                <?php foreach ($speakers as $speaker) { ?>
                    <div class="submission">
                        <h3><?php echo $speaker['name']; ?></h3>
                        <p><strong>Expertise:</strong> <?php echo $speaker['expertise']; ?></p>
                        <p><strong>Background:</strong> <?php echo $speaker['background']; ?></p>
                        <p><strong>Status:</strong> <?php echo $speaker['status']; ?></p>
                        <div class="submission-actions">
                            <a href="?action=accept&id=<?php echo $speaker['id']; ?>" class="accept-btn">Accept</a>
                            <a href="?action=reject&id=<?php echo $speaker['id']; ?>" class="reject-btn">Reject</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 University Name. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>