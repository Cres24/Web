<?php
session_start();

if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
}

$answer = '';
$error = '';
$display = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear_history'])) {
        $_SESSION['history'] = [];
    } else {
        $display = trim($_POST['display'] ?? '');
        $expression = preg_replace('/[^0-9+\-*.\/() ]/', '', $display);

        if ($display === '') {
            $error = 'Enter a calculation first.';
        } elseif ($expression !== $display) {
            $error = 'Only numbers and + - * / ( ) . are allowed.';
        } elseif (!preg_match('/^[0-9+\-*.\/() ]+$/', $expression)) {
            $error = 'Invalid expression.';
        } else {
            try {
                $result = @eval('return ' . $expression . ';');
                if ($result === false && strpos($expression, '/0') !== false) {
                    $error = 'Cannot divide by zero.';
                } else {
                    $answer = $result;
                    $entry = htmlspecialchars($display, ENT_QUOTES) . ' = ' . htmlspecialchars((string)$answer, ENT_QUOTES);
                    array_unshift($_SESSION['history'], $entry);
                    $_SESSION['history'] = array_slice($_SESSION['history'], 0, 10);
                }
            } catch (Throwable $e) {
                $error = 'Calculation error.';
            }
        }
    }
}

$history = $_SESSION['history'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator with History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="calculator">
        <h1>Enhanced PHP Calculator</h1>

        <form id="calcForm" method="post" action="">
            <input type="text" name="display" id="display" value="<?= htmlspecialchars($display) ?>" autocomplete="off" placeholder="Enter expression" />

            <div class="buttons">
                <button type="button" onclick="appendValue('7')">7</button>
                <button type="button" onclick="appendValue('8')">8</button>
                <button type="button" onclick="appendValue('9')">9</button>
                <button type="button" onclick="appendValue('/')">/</button>

                <button type="button" onclick="appendValue('4')">4</button>
                <button type="button" onclick="appendValue('5')">5</button>
                <button type="button" onclick="appendValue('6')">6</button>
                <button type="button" onclick="appendValue('*')">*</button>

                <button type="button" onclick="appendValue('1')">1</button>
                <button type="button" onclick="appendValue('2')">2</button>
                <button type="button" onclick="appendValue('3')">3</button>
                <button type="button" onclick="appendValue('-')">-</button>

                <button type="button" onclick="appendValue('0')">0</button>
                <button type="button" onclick="appendValue('.')">.</button>
                <button type="submit">=</button>
                <button type="button" onclick="appendValue('+')">+</button>
            </div>

            <div class="actions">
                <button type="button" onclick="clearDisplay()">C</button>
                <button type="submit" name="clear_history" value="1">Clear History</button>
            </div>
        </form>

        <div class="result">
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php elseif ($answer !== ''): ?>
                <p><strong>Result:</strong> <?= htmlspecialchars($answer) ?></p>
            <?php endif; ?>
        </div>

        <div class="history">
            <h2>History</h2>
            <?php if (count($history) === 0): ?>
                <p class="empty">No calculations yet.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($history as $item): ?>
                        <li><?= $item ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
