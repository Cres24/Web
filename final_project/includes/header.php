<?php
require_once __DIR__ . '/init.php';
$flash = get_flash();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? e($page_title) : 'ExploreWorld'; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(url_path('assets/css/style.css')); ?>">
  <?php if (!empty($extra_head)) echo $extra_head; ?>
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <div class="logo">
        <a href="<?php echo e(url_path('index.php')); ?>">ExploreWorld</a>
      </div>
      <div class="nav-links" id="primary-navigation">
        <a href="<?php echo e(url_path('index.php')); ?>" class="<?php echo ($active_nav ?? '') === 'home' ? 'active' : ''; ?>">Home</a>
        <a href="<?php echo e(url_path('destinations.php')); ?>" class="<?php echo ($active_nav ?? '') === 'destinations' ? 'active' : ''; ?>">Destinations</a>
        <a href="<?php echo e(url_path('tours.php')); ?>" class="<?php echo ($active_nav ?? '') === 'tours' ? 'active' : ''; ?>">Tours</a>
        <a href="<?php echo e(url_path('packages.php')); ?>" class="<?php echo ($active_nav ?? '') === 'packages' ? 'active' : ''; ?>">Packages</a>
        <a href="<?php echo e(url_path('gallery.php')); ?>" class="<?php echo ($active_nav ?? '') === 'gallery' ? 'active' : ''; ?>">Gallery</a>
        <a href="<?php echo e(url_path('blog.php')); ?>" class="<?php echo ($active_nav ?? '') === 'blog' ? 'active' : ''; ?>">Blog</a>
        <a href="<?php echo e(url_path('about.php')); ?>" class="<?php echo ($active_nav ?? '') === 'about' ? 'active' : ''; ?>">About</a>
        <a href="<?php echo e(url_path('contact.php')); ?>" class="<?php echo ($active_nav ?? '') === 'contact' ? 'active' : ''; ?>">Contact</a>
        <?php if ($user): ?>
          <a href="<?php echo e(url_path('dashboard.php')); ?>" class="<?php echo ($active_nav ?? '') === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
          <a href="<?php echo e(url_path('logout.php')); ?>" class="btn-login">Logout</a>
        <?php else: ?>
          <a href="<?php echo e(url_path('Login/login.php')); ?>" class="btn-login">Login</a>
        <?php endif; ?>
      </div>
      <div class="mobile-menu" role="button" tabindex="0" aria-label="Toggle navigation" aria-controls="primary-navigation" aria-expanded="false">
        <i class="fas fa-bars"></i>
      </div>
    </div>
  </nav>

  <?php if ($flash): ?>
    <div class="container">
      <div class="message <?php echo e($flash['type']); ?>" style="margin: 10px 0 0;">
        <?php echo e($flash['message']); ?>
      </div>
    </div>
  <?php endif; ?>
