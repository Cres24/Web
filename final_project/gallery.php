<?php
$page_title = 'Gallery - ExploreWorld';
$active_nav = 'gallery';
require_once __DIR__ . '/includes/header.php';

$stmtFeat = execute_query("SELECT id, title, subtitle, country, image_url FROM gallery_items WHERE is_featured = TRUE ORDER BY created_at DESC, id DESC LIMIT 6");
$featured = $stmtFeat ? get_results($stmtFeat) : [];

$stmtAll = execute_query("SELECT id, title, subtitle, country, image_url FROM gallery_items ORDER BY created_at DESC, id DESC LIMIT 60");
$items = $stmtAll ? get_results($stmtAll) : [];
?>

<header class="page-header">
  <div class="container">
    <h1>Gallery</h1>
    <p>Explore visuals from around the world</p>
  </div>
</header>

<section class="destinations-page">
  <div class="container">
    <?php if ($featured): ?>
      <h2 style="margin: 0 0 14px;">Featured</h2>
      <div class="grid cols-3">
        <?php foreach ($featured as $f): ?>
          <div class="destination-card">
            <img src="<?php echo e(url_path($f['image_url'])); ?>" alt="<?php echo e((string)$f['title']); ?>">
            <div class="card-content">
              <h3><?php echo e((string)$f['title']); ?></h3>
              <p><?php echo e((string)($f['subtitle'] ?? '')); ?></p>
              <div style="color: var(--light-text); font-size: 13px; margin-top: 6px;">
                <i class="fas fa-map-marker-alt"></i> <?php echo e((string)($f['country'] ?? '')); ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="height: 24px;"></div>
    <?php endif; ?>

    <h2 style="margin: 0 0 14px;">All photos</h2>
    <div class="grid cols-4">
      <?php foreach ($items as $it): ?>
        <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); overflow:hidden;">
          <img src="<?php echo e(url_path($it['image_url'])); ?>" alt="<?php echo e((string)$it['title']); ?>" style="width:100%; height: 170px; object-fit:cover;">
          <div style="padding: 12px;">
            <div style="font-weight: 700;"><?php echo e((string)$it['title']); ?></div>
            <div style="color: var(--light-text); font-size: 13px; margin-top: 4px;">
              <?php echo e((string)($it['subtitle'] ?? '')); ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

