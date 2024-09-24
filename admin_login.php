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
            <form id="admin-login-form" method="post" action="login.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
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