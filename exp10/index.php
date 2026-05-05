<?php
require_once __DIR__ . '/db.php';

$errors = [];
success:
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $content = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Please enter your name.';
    }
    if ($email === '') {
        $errors[] = 'Please enter your email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($content === '') {
        $errors[] = 'Please enter a message.';
    } elseif (mb_strlen($content) > 500) {
        $errors[] = 'Message must be 500 characters or fewer.';
    }

    if (empty($errors)) {
        $stmt = $mysqli->prepare('INSERT INTO messages (name, email, message) VALUES (?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('sss', $name, $email, $content);
            if ($stmt->execute()) {
                $message = 'Thanks! Your message was posted successfully.';
                $name = $email = $content = '';
            } else {
                $errors[] = 'Unable to save message. Please try again later.';
            }
            $stmt->close();
        } else {
            $errors[] = 'Database error: could not prepare statement.';
        }
    }
}

$result = $mysqli->query('SELECT name, message, created_at FROM messages ORDER BY created_at DESC LIMIT 10');
$messages = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $result->free();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Express Message Wall</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-wrap">
        <header class="site-header">
            <h1>Express Message Wall</h1>
            <p>Share a short message and see the latest posts below.</p>
        </header>

        <main class="content">
            <section class="form-card">
                <h2>Leave a message</h2>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif (!empty($message)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES) ?></div>
                <?php endif; ?>

                <form id="messageForm" method="post" action="">
                    <label>
                        Name
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($name ?? '', ENT_QUOTES) ?>" maxlength="100" required>
                    </label>

                    <label>
                        Email
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES) ?>" maxlength="150" required>
                    </label>

                    <label>
                        Message
                        <textarea name="message" id="message" rows="5" maxlength="500" required><?= htmlspecialchars($content ?? '', ENT_QUOTES) ?></textarea>
                    </label>

                    <div class="form-footer">
                        <span id="charCount">0 / 500</span>
                        <button type="submit">Post Message</button>
                    </div>
                </form>
            </section>

            <section class="history-card">
                <div class="history-header">
                    <h2>Recent messages</h2>
                    <p>Latest 10 posts from the wall</p>
                </div>
                <?php if (empty($messages)): ?>
                    <div class="empty-state">No messages yet. Be the first to post!</div>
                <?php else: ?>
                    <ul class="message-list">
                        <?php foreach ($messages as $item): ?>
                            <li>
                                <p class="message-text"><?= nl2br(htmlspecialchars($item['message'], ENT_QUOTES)) ?></p>
                                <div class="message-meta">
                                    <span><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></span>
                                    <time><?= htmlspecialchars(date('M j, Y H:i', strtotime($item['created_at'])), ENT_QUOTES) ?></time>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>
