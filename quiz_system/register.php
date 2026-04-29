<?php
session_start();
include 'db.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $message = "Please fill all fields.";
        $messageType = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
        $messageType = "error";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        if (!$stmt) {
            $message = "Database error. Check your `users` table.";
            $messageType = "error";
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $existing = $stmt->get_result();

            if ($existing && $existing->num_rows > 0) {
                $message = "Username already exists. Please login.";
                $messageType = "error";
            } else {
                // Make sure password column can store hashed passwords (bcrypt is ~60 chars).
                $lenRes = $conn->query("SELECT CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='quiz_db' AND TABLE_NAME='users' AND COLUMN_NAME='password' LIMIT 1");
                $lenRow = $lenRes ? $lenRes->fetch_assoc() : null;
                $maxLen = $lenRow && isset($lenRow["CHARACTER_MAXIMUM_LENGTH"]) ? (int) $lenRow["CHARACTER_MAXIMUM_LENGTH"] : 0;

                if ($maxLen > 0 && $maxLen < 255) {
                    // This helps avoid truncation of password hashes.
                    $conn->query("ALTER TABLE users MODIFY password VARCHAR(255)");
                }

                $hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                if (!$insert) {
                    $message = "Database error while registering. Try again.";
                    $messageType = "error";
                } else {
                    $insert->bind_param("ss", $username, $hash);

                    if ($insert->execute()) {
                        $message = "Registration successful. You can now login.";
                        $messageType = "success";
                    } else {
                        $message = "Something went wrong. Please try again.";
                        $messageType = "error";
                    }
                    $insert->close();
                }
            }
            $stmt->close();
        }
    }
}
?>
<?php $styleVersion = filemtime(__DIR__ . '/style.css'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Quiz System</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $styleVersion; ?>">
</head>
<body>
    <div class="page-wrap">
        <div class="card auth-card">
            <div class="auth-layout">
                <div class="auth-main">
                    <div class="eyebrow">Start Practicing</div>
                    <h2>Create Account</h2>
                    <p class="subtitle">Set up your account and start practicing in under a minute.</p>

                    <?php if ($message !== ""): ?>
                        <div class="alert <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="mini-banner">
                        <span>Fast Signup</span>
                        <span>Timed Quiz</span>
                        <span>Instant Results</span>
                    </div>

                    <form method="post" action="">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Your username" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required>

                        <button class="btn" type="submit">Register</button>
                    </form>

                    <p class="small-text">Already have an account? <a href="login.php">Login here</a></p>
                    <p class="small-text"><a href="index.php">Back to Home</a></p>
                </div>

                <aside class="auth-side">
                    <span class="panel-kicker">New Here</span>
                    <h3>Build your practice routine</h3>
                    <ul class="panel-list">
                        <li>Create your account in one step</li>
                        <li>Solve topic-based quiz questions</li>
                        <li>Review mistakes immediately</li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
</body>
</html>
