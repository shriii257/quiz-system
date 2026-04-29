<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT id, question, option1, option2, option3, option4 FROM questions ORDER BY id ASC");
$styleVersion = filemtime(__DIR__ . '/style.css');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - Online Quiz System</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $styleVersion; ?>">
</head>
<body>
    <div class="page-wrap">
        <div class="card quiz-card">
            <div class="top-bar">
                <div>
                    <div class="eyebrow">Skill Check</div>
                    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?></h2>
                    <p class="subtitle">Stay focused, answer each question, and submit before the timer ends.</p>
                </div>
                <div class="quiz-meta">
                    <div class="meta-box">
                        <span class="meta-label">Mode</span>
                        <strong>Practice</strong>
                    </div>
                    <div class="timer-box">Time left: <span id="timer">10:00</span></div>
                </div>
            </div>

            <form id="quizForm" action="result.php" method="post">
                <?php
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="question-box">
                        <div class="question-head">
                            <span class="question-number">Q<?php echo $i; ?></span>
                            <p><?php echo htmlspecialchars($row["question"]); ?></p>
                        </div>

                        <label><input type="radio" name="answers[<?php echo (int) $row["id"]; ?>]" value="<?php echo htmlspecialchars($row["option1"]); ?>"> <?php echo htmlspecialchars($row["option1"]); ?></label>
                        <label><input type="radio" name="answers[<?php echo (int) $row["id"]; ?>]" value="<?php echo htmlspecialchars($row["option2"]); ?>"> <?php echo htmlspecialchars($row["option2"]); ?></label>
                        <label><input type="radio" name="answers[<?php echo (int) $row["id"]; ?>]" value="<?php echo htmlspecialchars($row["option3"]); ?>"> <?php echo htmlspecialchars($row["option3"]); ?></label>
                        <label><input type="radio" name="answers[<?php echo (int) $row["id"]; ?>]" value="<?php echo htmlspecialchars($row["option4"]); ?>"> <?php echo htmlspecialchars($row["option4"]); ?></label>
                    </div>
                    <?php
                    $i++;
                }
                ?>

                <div class="action-row">
                    <button type="submit" class="btn">Submit Quiz</button>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let totalSeconds = 10 * 60;
        const timerEl = document.getElementById("timer");
        const quizForm = document.getElementById("quizForm");

        function updateTimer() {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            timerEl.textContent = String(minutes).padStart(2, "0") + ":" + String(seconds).padStart(2, "0");

            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                alert("Time is up. Quiz will be submitted automatically.");
                quizForm.submit();
                return;
            }
            totalSeconds--;
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</body>
</html>
