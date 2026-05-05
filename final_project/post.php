<?php
$active_nav = 'blog';
require_once __DIR__ . '/includes/header.php';

$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
if ($slug === '') {
  set_flash('error', 'Invalid post.');
  redirect('blog.php');
}

$stmt = execute_query("SELECT title, category, author, excerpt, content, image_url, published_at
                       FROM blog_posts
                       WHERE slug = ? AND published_at IS NOT NULL
                       LIMIT 1", "s", [$slug]);
$rows = $stmt ? get_results($stmt) : [];
if (!$rows) {
  set_flash('error', 'Post not found.');
  redirect('blog.php');
}
$post = $rows[0];

$page_title = (string)$post['title'] . ' - ExploreWorld';
?>

<header class="page-header">
  <div class="container">
    <h1><?php echo e((string)$post['title']); ?></h1>
    <p>
      <?php if (!empty($post['category'])): ?><?php echo e((string)$post['category']); ?> · <?php endif; ?>
      <?php if (!empty($post['author'])): ?>By <?php echo e((string)$post['author']); ?> · <?php endif; ?>
      <?php if (!empty($post['published_at'])): ?><?php echo e(date('M d, Y', strtotime((string)$post['published_at']))); ?><?php endif; ?>
    </p>
  </div>
</header>

<section class="destinations-page">
  <div class="container">
    <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); overflow:hidden;">
      <img src="<?php echo e(url_path($post['image_url'] ?: 'assets/images/hero-bg.svg')); ?>" alt="" style="width:100%; height:320px; object-fit:cover;">
      <div style="padding: 18px;">
        <?php if (!empty($post['excerpt'])): ?>
          <p style="color: var(--light-text); font-size: 14px; line-height: 1.8;">
            <?php echo e((string)$post['excerpt']); ?>
          </p>
          <hr style="border:none; border-top:1px solid rgba(0,0,0,0.08); margin: 14px 0;">
        <?php endif; ?>
        <div style="white-space: pre-wrap; line-height:1.9; color: var(--text-color);">
          <?php echo e((string)($post['content'] ?? '')); ?>
        </div>
        <div style="margin-top: 14px;">
          <a href="<?php echo e(url_path('blog.php')); ?>">← Back to blog</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

