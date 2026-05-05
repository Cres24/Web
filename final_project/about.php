<?php
$page_title = 'About - ExploreWorld';
$active_nav = 'about';
require_once __DIR__ . '/includes/header.php';
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title">About Us</h1>

    <div style="display:grid; grid-template-columns: 1.1fr 0.9fr; gap: 24px;">
      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
        <h2 style="margin-top:0;">Our Story</h2>
        <p style="color: var(--light-text); line-height:1.8;">
          ExploreWorld was founded to make travel planning simple, reliable, and fun. We curate destinations,
          tours, and experiences so you can focus on exploring — not stressing.
        </p>
        <p style="color: var(--light-text); line-height:1.8;">
          This demo website includes real login/signup, dynamic destinations and tours loaded from MySQL,
          and a booking dashboard.
        </p>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-top: 14px;">
          <div style="border:1px solid var(--border-color); border-radius:10px; padding:12px;">
            <div style="font-weight:700;"><i class="fas fa-shield-alt"></i> Secure accounts</div>
            <div style="color: var(--light-text); font-size: 14px; margin-top:6px;">Passwords are stored as hashes.</div>
          </div>
          <div style="border:1px solid var(--border-color); border-radius:10px; padding:12px;">
            <div style="font-weight:700;"><i class="fas fa-globe"></i> Dynamic content</div>
            <div style="color: var(--light-text); font-size: 14px; margin-top:6px;">Tours/destinations come from DB.</div>
          </div>
          <div style="border:1px solid var(--border-color); border-radius:10px; padding:12px;">
            <div style="font-weight:700;"><i class="fas fa-calendar-check"></i> Booking flow</div>
            <div style="color: var(--light-text); font-size: 14px; margin-top:6px;">Book, view, cancel bookings.</div>
          </div>
        </div>
      </div>

      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); overflow:hidden;">
        <img src="<?php echo e(url_path('assets/images/hero-bg.svg')); ?>" alt="ExploreWorld" style="width:100%; height:220px; object-fit:cover;">
        <div style="padding:18px;">
          <h3 style="margin-top:0;">Quick facts</h3>
          <ul style="margin:0; padding-left: 18px; color: var(--light-text); line-height:1.9;">
            <li>Built with PHP + MySQL + JS + HTML + CSS</li>
            <li>Responsive UI with reusable header/footer</li>
            <li>Newsletter & contact forms persist to DB</li>
          </ul>
          <div style="margin-top: 14px;">
            <a class="btn-book" href="<?php echo e(url_path('tours.php')); ?>">Explore tours</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

