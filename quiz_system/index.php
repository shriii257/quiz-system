<?php $styleVersion = filemtime(__DIR__ . '/style.css'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz System</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $styleVersion; ?>">
</head>
<body>
    <div class="page-wrap">
        <div class="card hero-card">
            <div class="hero-layout">
                <div class="hero-main">
                    <div class="eyebrow">Frontend Practice Arena</div>
                    <h1>Online Quiz System</h1>
                    <p class="subtitle">A cleaner practice space for HTML, CSS, JavaScript, and the core web basics that interviews love.</p>
                    <p>Practice multiple-choice questions, stay aware of the timer, and get an instant answer review without changing anything in your existing database.</p>

                    <div class="feature-grid">
                        <div class="feature-pill">
                            <span class="feature-value">50+</span>
                            <span class="feature-label">Questions</span>
                        </div>
                        <div class="feature-pill">
                            <span class="feature-value">3</span>
                            <span class="feature-label">Core Topics</span>
                        </div>
                        <div class="feature-pill">
                            <span class="feature-value">10</span>
                            <span class="feature-label">Minute Timer</span>
                        </div>
                    </div>

                    <div class="action-row">
                        <a class="btn" href="register.php">Create Account</a>
                        <a class="btn btn-secondary" href="login.php">Login</a>
                    </div>
                </div>

                <aside class="hero-panel">
                    <div class="panel-card panel-card-accent">
                        <span class="panel-kicker">What You Get</span>
                        <h3>Fast practice loop</h3>
                        <ul class="panel-list">
                            <li>Quick signup and login flow</li>
                            <li>One-screen quiz experience</li>
                            <li>Instant result breakdown</li>
                        </ul>
                    </div>

                    <div class="panel-card">
                        <span class="panel-kicker">Focus Areas</span>
                        <div class="tag-row">
                            <span>HTML</span>
                            <span>CSS</span>
                            <span>JavaScript</span>
                            <span>Web Basics</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</body>
</html>
