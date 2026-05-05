<?php
$page_title = 'Dashboard - ExploreWorld';
$active_nav = 'dashboard';
require_once __DIR__ . '/includes/header.php';
require_login();

$u = $_SESSION['user'];

$stmt = execute_query("SELECT b.id, b.booking_date, b.number_of_people, b.total_price, b.status, b.created_at,
                              t.name AS tour_name, t.id AS tour_id, t.image_url
                       FROM bookings b
                       JOIN tours t ON t.id = b.tour_id
                       WHERE b.user_id = ?
                       ORDER BY b.created_at DESC, b.id DESC
                       LIMIT 10", "i", [(int)$u['id']]);
$bookings = $stmt ? get_results($stmt) : [];
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title">Hi, <?php echo e((string)$u['name']); ?></h1>

    <div style="display:grid; grid-template-columns: 1fr; gap: 18px;">
      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
        <h3 style="margin:0 0 8px;">Your account</h3>
        <div style="color: var(--light-text); font-size: 14px;">
          <div><strong>Username:</strong> <?php echo e((string)$u['username']); ?></div>
          <div><strong>Email:</strong> <?php echo e((string)$u['email']); ?></div>
        </div>
      </div>

      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
        <div style="display:flex; justify-content:space-between; align-items:center; gap: 10px; flex-wrap: wrap;">
          <h3 style="margin:0;">Recent bookings</h3>
          <a class="btn-book" href="<?php echo e(url_path('tours.php')); ?>">Book a tour</a>
        </div>

        <?php if (!$bookings): ?>
          <p style="margin-top: 12px; color: var(--light-text);">No bookings yet.</p>
        <?php else: ?>
          <div style="margin-top: 12px; display:grid; gap: 12px;">
            <?php foreach ($bookings as $b): ?>
              <div style="display:flex; gap: 12px; align-items:center; border:1px solid var(--border-color); border-radius:10px; padding:12px;">
                <img src="<?php echo e(url_path($b['image_url'] ?: 'assets/images/everest.svg')); ?>" alt="" style="width:90px; height:64px; object-fit:cover; border-radius:8px;">
                <div style="flex:1;">
                  <div style="font-weight:600;"><?php echo e((string)$b['tour_name']); ?></div>
                  <div style="color: var(--light-text); font-size: 13px;">
                    Date: <?php echo e((string)$b['booking_date']); ?> · People: <?php echo e((string)$b['number_of_people']); ?> · Total: $<?php echo e(number_format((float)$b['total_price'], 0)); ?>
                  </div>
                  <div style="margin-top:6px; font-size:12px; color: var(--light-text);">
                    Status: <strong><?php echo e((string)$b['status']); ?></strong>
                  </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:8px;">
                  <a class="btn-explore" style="margin:0;" href="<?php echo e(url_path('tour.php?id=' . urlencode((string)$b['tour_id']))); ?>">View</a>
                  <?php if ($b['status'] !== 'Cancelled'): ?>
                    <form method="post" action="<?php echo e(url_path('my-bookings.php')); ?>" style="margin:0;">
                      <input type="hidden" name="cancel_id" value="<?php echo e((string)$b['id']); ?>">
                      <button type="submit" class="btn-book" style="background:#dc3545;">Cancel</button>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div style="margin-top: 12px;">
            <a href="<?php echo e(url_path('my-bookings.php')); ?>">View all bookings →</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

