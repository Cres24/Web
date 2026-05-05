<?php
$page_title = 'My Bookings - ExploreWorld';
$active_nav = 'dashboard';
require_once __DIR__ . '/includes/header.php';
require_login();

$u = $_SESSION['user'];

// Cancel booking (simple: mark Cancelled)
if (isset($_POST['cancel_id'])) {
    $cancel_id = (int)$_POST['cancel_id'];
    if ($cancel_id > 0) {
        execute_query("UPDATE bookings SET status = 'Cancelled' WHERE id = ? AND user_id = ?", "ii", [$cancel_id, (int)$u['id']]);
        set_flash('success', 'Booking cancelled.');
    }
    redirect('my-bookings.php');
}

$stmt = execute_query("SELECT b.id, b.booking_date, b.number_of_people, b.total_price, b.status, b.created_at,
                              t.name AS tour_name, t.id AS tour_id, t.image_url
                       FROM bookings b
                       JOIN tours t ON t.id = b.tour_id
                       WHERE b.user_id = ?
                       ORDER BY b.created_at DESC, b.id DESC", "i", [(int)$u['id']]);
$bookings = $stmt ? get_results($stmt) : [];
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title">My Bookings</h1>

    <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
      <?php if (!$bookings): ?>
        <p style="color: var(--light-text);">No bookings yet. <a href="<?php echo e(url_path('tours.php')); ?>">Browse tours</a>.</p>
      <?php else: ?>
        <div style="display:grid; gap: 12px;">
          <?php foreach ($bookings as $b): ?>
            <div style="display:flex; gap: 12px; align-items:center; border:1px solid var(--border-color); border-radius:10px; padding:12px;">
              <img src="<?php echo e(url_path($b['image_url'] ?: 'assets/images/everest.svg')); ?>" alt="" style="width:110px; height:78px; object-fit:cover; border-radius:8px;">
              <div style="flex:1;">
                <div style="font-weight:700;"><?php echo e((string)$b['tour_name']); ?></div>
                <div style="color: var(--light-text); font-size: 13px; margin-top: 4px;">
                  Date: <?php echo e((string)$b['booking_date']); ?> · People: <?php echo e((string)$b['number_of_people']); ?> · Total: $<?php echo e(number_format((float)$b['total_price'], 0)); ?>
                </div>
                <div style="margin-top: 6px; font-size: 12px;">
                  Status: <strong><?php echo e((string)$b['status']); ?></strong>
                </div>
              </div>
              <div style="display:flex; flex-direction:column; gap:8px;">
                <a class="btn-explore" style="margin:0;" href="<?php echo e(url_path('tour.php?id=' . urlencode((string)$b['tour_id']))); ?>">View</a>
                <?php if ($b['status'] !== 'Cancelled'): ?>
                  <form method="post" style="margin:0;">
                    <input type="hidden" name="cancel_id" value="<?php echo e((string)$b['id']); ?>">
                    <button type="submit" class="btn-book" style="background:#dc3545;">Cancel</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

