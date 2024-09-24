<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Speaker Finder - Suggest a Speaker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Expert Speaker Finder</h1>
            <p>Find the perfect speaker for university seminars</p>
            <a href="admin.html" class="admin-link">Admin Login</a>
        </div>
    </header>

    <main class="container">
        <section id="speaker-submission">
            <h2>Suggest a Speaker</h2>
            <form id="speaker-form">
                <div class="form-group">
                    <label for="speaker-name">Speaker Name:</label>
                    <input type="text" id="speaker-name" name="speaker-name" required>
                </div>
                <div class="form-group">
                    <label for="speaker-expertise">Field of Expertise:</label>
                    <input type="text" id="speaker-expertise" name="speaker-expertise" required>
                </div>
                <div class="form-group">
                    <label for="speaker-background">Background:</label>
                    <textarea id="speaker-background" name="speaker-background" required></textarea>
                </div>
                <button type="submit">Submit Speaker</button>
            </form>
        </section>

        <div id="submission-confirmation" class="hidden">
            <h2>Thank you for your submission!</h2>
            <p>Your speaker suggestion has been successfully submitted for review.</p>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Charotar University of Science and Technology. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>