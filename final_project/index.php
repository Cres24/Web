<?php
$page_title = 'ExploreWorld - Your Ultimate Travel Companion';
$active_nav = 'home';
require_once __DIR__ . '/includes/header.php';

$destStmt = execute_query("SELECT id, name, description, location, country, image_url, rating FROM destinations ORDER BY rating DESC, id ASC LIMIT 6");
$destinations = $destStmt ? get_results($destStmt) : [];

$tourStmt = execute_query("SELECT t.id, t.name, t.duration, t.difficulty, t.price, t.image_url, d.name AS destination_name
                          FROM tours t
                          LEFT JOIN destinations d ON d.id = t.destination_id
                          ORDER BY t.created_at DESC, t.id DESC
                          LIMIT 6");
$tours = $tourStmt ? get_results($tourStmt) : [];
?>

<section class="hero">
  <div class="container">
    <div class="hero-content">
      <h1>Discover the World</h1>
      <p>Explore breathtaking destinations and create unforgettable memories</p>
      <div class="search-box">
        <input id="homeSearch" type="text" placeholder="Search tours or destinations...">
        <button id="homeSearchBtn" class="btn-search">Search</button>
      </div>
    </div>
  </div>
</section>

<section class="destinations">
  <div class="container">
    <h2 class="section-title">Popular Destinations</h2>
    <div class="destination-grid">
      <?php foreach ($destinations as $d): ?>
        <div class="destination-card">
          <img src="<?php echo e(url_path($d['image_url'] ?: 'assets/images/nepal.svg')); ?>" alt="<?php echo e($d['name']); ?>">
          <div class="card-content">
            <h3><?php echo e($d['name']); ?></h3>
            <p><?php echo e($d['location'] ?: $d['country']); ?></p>
            <a href="<?php echo e(url_path('destinations.php?country=' . urlencode((string)$d['country']))); ?>" class="btn-explore">Explore</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="tours">
  <div class="container">
    <h2 class="section-title">Featured Tours</h2>
    <div class="tour-grid">
      <?php foreach ($tours as $t): ?>
        <div class="tour-card">
          <img src="<?php echo e(url_path($t['image_url'] ?: 'assets/images/everest.svg')); ?>" alt="<?php echo e($t['name']); ?>">
          <div class="card-content">
            <h3><?php echo e($t['name']); ?></h3>
            <p><?php echo e((string)$t['duration']); ?> Days | <?php echo e((string)$t['difficulty']); ?></p>
            <div class="price">$<?php echo e(number_format((float)$t['price'], 0)); ?></div>
            <a href="<?php echo e(url_path('tour.php?id=' . urlencode((string)$t['id']))); ?>" class="btn-book">View</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="why-us">
  <div class="container">
    <h2 class="section-title">Why Choose Us</h2>
    <div class="features-grid">
      <div class="feature">
        <i class="fas fa-globe"></i>
        <h3>Worldwide Coverage</h3>
        <p>Explore destinations across the globe</p>
      </div>
      <div class="feature">
        <i class="fas fa-hand-holding-usd"></i>
        <h3>Best Price Guarantee</h3>
        <p>Get the best deals on your travels</p>
      </div>
      <div class="feature">
        <i class="fas fa-headset"></i>
        <h3>24/7 Support</h3>
        <p>We’re here to help you anytime</p>
      </div>
      <div class="feature">
        <i class="fas fa-shield-alt"></i>
        <h3>Safe & Secure</h3>
        <p>Your safety is our priority</p>
      </div>
    </div>
  </div>
</section>

<?php
$extra_scripts = "<script>
  (function(){
    const input = document.getElementById('homeSearch');
    const btn = document.getElementById('homeSearchBtn');
    function go(){
      const q = (input.value || '').trim();
      if(!q) return;
      window.location.href = '" . e(url_path('tours.php')) . "?q=' + encodeURIComponent(q);
    }
    btn && btn.addEventListener('click', go);
    input && input.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ e.preventDefault(); go(); }});
  })();
</script>";
require_once __DIR__ . '/includes/footer.php';
?>

