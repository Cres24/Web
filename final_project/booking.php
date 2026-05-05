<?php
require_once __DIR__ . '/includes/init.php';

$tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
if ($tour_id <= 0) {
  set_flash('error', 'Invalid tour.');
  redirect('tours.php');
}

// Load tour
$stmt = execute_query("SELECT t.*, d.name AS destination_name, d.country
                      FROM tours t
                      LEFT JOIN destinations d ON d.id = t.destination_id
                      WHERE t.id = ? LIMIT 1", "i", [$tour_id]);
$rows = $stmt ? get_results($stmt) : [];
if (!$rows) {
  set_flash('error', 'Tour not found.');
  redirect('tours.php');
}
$tour = $rows[0];

if (!is_logged_in()) {
  set_flash('error', 'Please login to book this tour.');
  redirect('Login/login.php');
}

// Handle booking submit
if (isset($_POST['book'])) {
  $booking_date = (string)($_POST['booking_date'] ?? '');
  $people = (int)($_POST['number_of_people'] ?? 0);
  if ($booking_date === '' || $people <= 0) {
    set_flash('error', 'Please provide a valid date and number of people.');
    redirect('booking.php?tour_id=' . urlencode((string)$tour_id));
  }

  $price = (float)$tour['price'];
  $total = $price * $people;

  $ok = execute_query("INSERT INTO bookings (user_id, tour_id, booking_date, number_of_people, total_price, status, created_at)
                       VALUES (?, ?, ?, ?, ?, 'Pending', NOW())",
                      "iisid",
                      [(int)$_SESSION['user']['id'], $tour_id, $booking_date, $people, $total]);

  if ($ok) {
    set_flash('success', 'Booking created! You can view it in your dashboard.');
    redirect('dashboard.php');
  }

  set_flash('error', 'Booking failed. Please try again.');
  redirect('booking.php?tour_id=' . urlencode((string)$tour_id));
}

$page_title = 'Booking - ' . (string)$tour['name'];
$active_nav = 'tours';
require_once __DIR__ . '/includes/header.php';
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title">Confirm booking</h1>

    <div style="display:grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px;">
      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
        <h3 style="margin-top:0;">Traveler details</h3>
        <form method="post">
          <div class="filter-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap: 14px;">
            <div class="filter-group">
              <label for="booking_date">Travel date</label>
              <input type="date" id="booking_date" name="booking_date" required>
            </div>
            <div class="filter-group">
              <label for="number_of_people">People</label>
              <input type="number" id="number_of_people" name="number_of_people" min="1" value="1" required>
            </div>
          </div>
          <button class="btn-book" type="submit" name="book" style="margin-top:14px;">Book now</button>
        </form>
      </div>

      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); overflow:hidden;">
        <img src="<?php echo e(url_path($tour['image_url'] ?: 'assets/images/everest.svg')); ?>" alt="" style="width:100%; height:200px; object-fit:cover;">
        <div style="padding: 18px;">
          <div style="font-weight:700; font-size:18px;"><?php echo e((string)$tour['name']); ?></div>
          <div style="color: var(--light-text); font-size: 14px; margin-top: 6px;">
            <?php if (!empty($tour['country'])): ?><?php echo e((string)$tour['country']); ?> · <?php endif; ?>
            <?php echo e((string)$tour['duration']); ?> days · <?php echo e((string)$tour['difficulty']); ?>
          </div>
          <div style="margin-top: 12px; font-size: 26px; font-weight:800; color: var(--primary-color);">
            $<?php echo e(number_format((float)$tour['price'], 0)); ?> <span style="font-size:14px; color: var(--light-text); font-weight:500;">per person</span>
          </div>
          <div style="margin-top: 10px; color: var(--light-text); font-size: 13px;">
            Status will be <strong>Pending</strong> until confirmed.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

