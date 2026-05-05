<?php
require_once __DIR__ . '/../includes/init.php';

if (is_logged_in()) {
    redirect('dashboard.php');
    exit();
}

// Prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

if (isset($_POST['login'])) {
    $username = sanitize_input((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        set_flash('error', 'Please fill in all fields.');
        redirect('Login/login.php');
        exit();
    }

    $stmt = execute_query("SELECT id, name, username, email, password, profile_picture, role, is_active
                           FROM users
                           WHERE username = ? OR email = ?
                           LIMIT 1", "ss", [$username, $username]);

    $rows = $stmt ? get_results($stmt) : [];
    if (!$rows) {
        set_flash('error', 'User not found.');
        redirect('Login/login.php');
        exit();
    }

    $user = $rows[0];
    if ((int)$user['is_active'] !== 1) {
        set_flash('error', 'Your account is disabled.');
        redirect('Login/login.php');
        exit();
    }

    if (!password_verify($password, (string)$user['password'])) {
        set_flash('error', 'Invalid password.');
        redirect('Login/login.php');
        exit();
    }

    // Update last login (column exists in exploreworld_db schema)
    execute_query("UPDATE users SET last_login = NOW() WHERE id = ?", "i", [(int)$user['id']]);

    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => (string)$user['name'],
        'username' => (string)$user['username'],
        'email' => (string)$user['email'],
        'profile_picture' => $user['profile_picture'] ? (string)$user['profile_picture'] : null,
        'role' => (string)$user['role']
    ];

    set_flash('success', 'Login successful. Welcome back, ' . (string)$user['name'] . '!');
    redirect('dashboard.php');
    exit();
}

// Basic login UI (self-contained)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ExploreWorld</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root { --primary:#667eea; --secondary:#764ba2; --text:#333; --bg:#f8f9fa; --border:#ddd; }
    *{box-sizing:border-box; font-family:Poppins, sans-serif;}
    body{margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--primary),var(--secondary)); padding:20px;}
    .card{width:100%; max-width:460px; background:rgba(255,255,255,.95); border-radius:14px; padding:34px; box-shadow:0 10px 30px rgba(0,0,0,.12);}
    h1{margin:0 0 8px; color:var(--primary); font-size:24px; text-align:center;}
    p{margin:0 0 22px; color:#666; font-size:14px; text-align:center;}
    .msg{padding:10px 12px; border-radius:8px; margin-bottom:14px; font-weight:500;}
    .msg.error{background:#f8d7da; color:#721c24;}
    .msg.success{background:#d4edda; color:#155724;}
    .group{position:relative; margin-bottom:14px;}
    .group i{position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#666;}
    input{width:100%; padding:12px 14px 12px 44px; border:2px solid var(--border); border-radius:10px; background:var(--bg); font-size:15px;}
    input:focus{outline:none; border-color:var(--primary); background:#fff;}
    button{width:100%; border:none; border-radius:10px; padding:12px; font-size:16px; color:#fff; cursor:pointer;
      background:linear-gradient(135deg,var(--primary),var(--secondary));}
    .links{margin-top:14px; text-align:center; font-size:14px;}
    .links a{color:var(--primary); text-decoration:none; font-weight:600;}
  </style>
</head>
<body>
  <div class="card">
    <h1>Welcome back</h1>
    <p>Login to book tours and manage bookings</p>
    <?php if(isset($_SESSION['message'])): ?>
      <div class="msg <?php echo e($_SESSION['message_type'] ?? 'error'); ?>">
        <?php echo e((string)$_SESSION['message']); unset($_SESSION['message'], $_SESSION['message_type']); ?>
      </div>
    <?php endif; ?>
    <form method="post" action="login.php">
      <div class="group">
        <i class="fa-solid fa-user"></i>
        <input type="text" name="username" placeholder="Username or Email" autocomplete="username" required>
      </div>
      <div class="group">
        <i class="fa-solid fa-key"></i>
        <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
      </div>
      <button type="submit" name="login">Login</button>
      <div class="links">
        New here? <a href="signup.php">Create an account</a> ·
        <a href="<?php echo e(url_path('index.php')); ?>">Back to Home</a>
      </div>
    </form>
  </div>
</body>
</html>

