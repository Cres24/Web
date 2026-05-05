<?php
$active_nav = 'tours';
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  set_flash('error', 'Invalid tour.');
  redirect('tours.php');
}

$stmt = execute_query("SELECT t.*, d.name AS destination_name, d.country, d.location
                      FROM tours t
                      LEFT JOIN destinations d ON d.id = t.destination_id
                      WHERE t.id = ? LIMIT 1", "i", [$id]);
$rows = $stmt ? get_results($stmt) : [];
if (!$rows) {
  set_flash('error', 'Tour not found.');
  redirect('tours.php');
}
$tour = $rows[0];

$page_title = $tour['name'] . ' - ExploreWorld';
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title"><?php echo e((string)$tour['name']); ?></h1>

    <div style="display:grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px;">
      <div style="background:#fff; border-radius: 10px; box-shadow: var(--shadow); overflow:hidden;">
        <img src="<?php echo e(url_path($tour['image_url'] ?: 'assets/images/everest.svg')); ?>" alt="<?php echo e((string)$tour['name']); ?>" style="width:100%; height:320px; object-fit:cover;">
        <div style="padding: 18px;">
          <div style="display:flex; gap: 10px; flex-wrap: wrap; color: var(--light-text); font-size: 14px; margin-bottom: 12px;">
            <span><i class="fas fa-clock"></i> <?php echo e((string)$tour['duration']); ?> days</span>
            <span><i class="fas fa-signal"></i> <?php echo e((string)$tour['difficulty']); ?></span>
            <?php if (!empty($tour['country'])): ?><span><i class="fas fa-map"></i> <?php echo e((string)$tour['country']); ?></span><?php endif; ?>
            <?php if (!empty($tour['location'])): ?><span><i class="fas fa-map-marker-alt"></i> <?php echo e((string)$tour['location']); ?></span><?php endif; ?>
          </div>
          <p style="color: var(--light-text); line-height: 1.7;">
            <?php echo e((string)($tour['description'] ?? '')); ?>
          </p>
        </div>
      </div>

      <div style="background:#fff; border-radius: 10px; box-shadow: var(--shadow); padding: 18px;">
        <h3 style="margin-bottom: 10px;">Book this tour</h3>
        <div style="font-size: 28px; font-weight: 700; color: var(--primary-color); margin-bottom: 10px;">
          $<?php echo e(number_format((float)$tour['price'], 0)); ?>
        </div>
        <p style="color: var(--light-text); margin-bottom: 14px;">Secure checkout. Cancel anytime before confirmation.</p>
        <a class="btn-book" style="display:inline-block;" href="<?php echo e(url_path('booking.php?tour_id=' . urlencode((string)$tour['id']))); ?>">Continue</a>
        <?php if (!is_logged_in()): ?>
          <p style="margin-top: 12px; color: var(--light-text); font-size: 13px;">
            You’ll be asked to login before confirming.
          </p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

