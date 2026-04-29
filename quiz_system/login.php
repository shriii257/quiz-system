<?php
session_start();
include 'db.php';

if (isset($_SESSION["user_id"])) {
    header("Location: quiz.php");
    exit;
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $message = "Please enter username and password.";
        $messageType = "error";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        if (!$stmt) {
            $message = "Database error. Check your `users` table columns.";
            $messageType = "error";
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $stored = (string) $user["password"];

                // Supports both old plain-text passwords and new hashed passwords.
                if (password_verify($password, $stored) || $password === $stored) {
                    $_SESSION["user_id"] = (int) $user["id"];
                    $_SESSION["user_name"] = $user["username"];
                    header("Location: quiz.php");
                    exit;
                }
            }

            $message = "Invalid username or password.";
            $messageType = "error";
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
    <title>Login - Quiz System</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $styleVersion; ?>">
</head>
<body>
    <div class="page-wrap">
        <div class="card auth-card">
            <div class="auth-layout">
                <div class="auth-main">
                    <div class="eyebrow">Welcome Back</div>
                    <h2>Login</h2>
                    <p class="subtitle">Pick up right where you left off and continue your quiz run.</p>

                    <?php if ($message !== ""): ?>
                        <div class="alert <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="mini-banner">
                        <span>HTML</span>
                        <span>CSS</span>
                        <span>JavaScript</span>
                    </div>

                    <form method="post" action="">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Your username" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Your password" required>

                        <button class="btn" type="submit">Login</button>
                    </form>

                    <p class="small-text">New user? <a href="register.php">Create account</a></p>
                    <p class="small-text"><a href="index.php">Back to Home</a></p>
                </div>

                <aside class="auth-side">
                    <span class="panel-kicker">Session Flow</span>
                    <h3>Jump back in quickly</h3>
                    <ul class="panel-list">
                        <li>Secure login with existing account</li>
                        <li>Timed practice environment</li>
                        <li>Answer review after submission</li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
</body>
</html>
