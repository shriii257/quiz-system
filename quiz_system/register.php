<?php
session_start();
include 'db.php';
$message = "";
$messageType = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";
    if ($username === "" || $password === "" || $confirm === "") {
        $message = "Please fill all fields.";
        $messageType = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
        $messageType = "error";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
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
                $lenRes = $conn->query("SELECT CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='quiz_db' AND TABLE_NAME='users' AND COLUMN_NAME='password' LIMIT 1");
                $lenRow = $lenRes ? $lenRes->fetch_assoc() : null;
                $maxLen = $lenRow && isset($lenRow["CHARACTER_MAXIMUM_LENGTH"]) ? (int) $lenRow["CHARACTER_MAXIMUM_LENGTH"] : 0;
                if ($maxLen > 0 && $maxLen < 255) {
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
    <style>
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrap input {
            width: 100%;
            padding-right: 2.6rem;
            box-sizing: border-box;
        }
        .toggle-eye {
            position: absolute;
            right: 0.7rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            color: #888;
            transition: color 0.2s;
        }
        .toggle-eye:hover { color: #444; }
        .toggle-eye svg { width: 20px; height: 20px; display: block; }
    </style>
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
                        <div class="input-wrap">
                            <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required>
                            <button type="button" class="toggle-eye" onclick="toggleEye('password', 'eye-pass')" aria-label="Show/hide password">
                                <svg id="eye-pass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>

                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-wrap">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                            <button type="button" class="toggle-eye" onclick="toggleEye('confirm_password', 'eye-confirm')" aria-label="Show/hide confirm password">
                                <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>

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

    <script>
        // Eye SVG paths
        const eyeOpen  = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                           <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                           <line x1="1" y1="1" x2="23" y2="23"/>`;

        function toggleEye(inputId, svgId) {
            const input = document.getElementById(inputId);
            const svg   = document.getElementById(svgId);
            const isHidden = input.type === 'password';
            input.type  = isHidden ? 'text' : 'password';
            svg.innerHTML = isHidden ? eyeClosed : eyeOpen;
        }
    </script>
</body>
</html>
