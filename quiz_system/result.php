<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$submittedAnswers = $_POST["answers"] ?? [];
$score = 0;
$total = 0;
$review = [];

$result = $conn->query("SELECT id, question, answer FROM questions ORDER BY id ASC");

while ($row = $result->fetch_assoc()) {
    $qid = (int) $row["id"];
    $total++;
    $userAnswer = isset($submittedAnswers[$qid]) ? trim($submittedAnswers[$qid]) : "Not answered";
    $correct = trim($row["answer"]);
    $isCorrect = ($userAnswer === $correct);
    if ($isCorrect) {
        $score++;
    }

    $review[] = [
        "question" => $row["question"],
        "user_answer" => $userAnswer,
        "correct_answer" => $correct,
        "is_correct" => $isCorrect
    ];
}

$percentage = $total > 0 ? round(($score / $total) * 100) : 0;
$styleVersion = filemtime(__DIR__ . '/style.css');
$performanceLabel = $percentage >= 80 ? "Strong Performance" : ($percentage >= 50 ? "Good Progress" : "Keep Practicing");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result - Online Quiz System</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $styleVersion; ?>">
</head>
<body>
    <div class="page-wrap">
        <div class="card result-card">
            <div class="eyebrow">Quiz Summary</div>
            <h2>Your Result</h2>
            <p class="subtitle"><?php echo htmlspecialchars($_SESSION["user_name"]); ?>, here is your performance:</p>

            <div class="score-box">
                <span class="score-badge"><?php echo $performanceLabel; ?></span>
                <h3><?php echo $score; ?> / <?php echo $total; ?></h3>
                <p>Score: <?php echo $percentage; ?>%</p>
            </div>

            <h3>Answer Review</h3>
            <?php foreach ($review as $item): ?>
                <div class="review-box <?php echo $item["is_correct"] ? "correct" : "wrong"; ?>">
                    <p><strong>Question:</strong> <?php echo htmlspecialchars($item["question"]); ?></p>
                    <p><strong>Your Answer:</strong> <?php echo htmlspecialchars($item["user_answer"]); ?></p>
                    <p><strong>Correct Answer:</strong> <?php echo htmlspecialchars($item["correct_answer"]); ?></p>
                </div>
            <?php endforeach; ?>

            <div class="action-row">
                <a href="quiz.php" class="btn">Try Again</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
