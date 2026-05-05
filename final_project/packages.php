<?php
$page_title = 'Packages - ExploreWorld';
$active_nav = 'packages';
require_once __DIR__ . '/includes/header.php';

$category = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

$where = [];
$types = '';
$params = [];
if ($category !== '') { $where[] = "category = ?"; $types .= 's'; $params[] = $category; }
if ($q !== '') {
  $where[] = "(title LIKE CONCAT('%', ?, '%') OR highlights LIKE CONCAT('%', ?, '%'))";
  $types .= 'ss';
  $params[] = $q; $params[] = $q;
}

$sql = "SELECT id, title, category, duration_days, duration_nights, price_per_person, highlights,
               includes_flights, includes_hotel, includes_meals, includes_activities, image_url
        FROM travel_packages";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY created_at DESC, id DESC LIMIT 60";

$stmt = execute_query($sql, $types, $params);
$packages = $stmt ? get_results($stmt) : [];

$catsStmt = execute_query("SELECT category, COUNT(*) AS cnt FROM travel_packages GROUP BY category ORDER BY cnt DESC");
$cats = $catsStmt ? get_results($catsStmt) : [];
?>

<header class="page-header">
  <div class="container">
    <h1>Travel Packages</h1>
    <p>Choose from curated travel experiences</p>
  </div>
</header>

<section class="destinations-page">
  <div class="container">
    <div class="filter-section">
      <form method="get" style="display:grid; grid-template-columns: 1fr 220px 120px; gap: 10px; align-items:end;">
        <div class="filter-group">
          <label for="q">Search</label>
          <input id="q" name="q" value="<?php echo e($q); ?>" placeholder="Search packages...">
        </div>
        <div class="filter-group">
          <label for="category">Category</label>
          <select id="category" name="category">
            <option value="">All</option>
            <?php foreach ($cats as $c): ?>
              <option value="<?php echo e((string)$c['category']); ?>" <?php echo ((string)$c['category'] === $category) ? 'selected' : ''; ?>>
                <?php echo e((string)$c['category']); ?> (<?php echo e((string)$c['cnt']); ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button class="btn-search" type="submit" style="border-radius:8px;">Apply</button>
      </form>
    </div>

    <?php if (!$packages): ?>
      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
        No packages found.
      </div>
    <?php endif; ?>

    <div class="grid cols-3" style="margin-top: 18px;">
      <?php foreach ($packages as $p): ?>
        <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); overflow:hidden;">
          <img src="<?php echo e(url_path($p['image_url'] ?: 'assets/images/hero-bg.svg')); ?>" alt="<?php echo e((string)$p['title']); ?>" style="width:100%; height:200px; object-fit:cover;">
          <div style="padding: 14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; gap: 10px;">
              <div style="font-weight:800;"><?php echo e((string)$p['title']); ?></div>
              <div style="color: var(--primary-color); font-weight:800;">$<?php echo e(number_format((float)$p['price_per_person'], 0)); ?></div>
            </div>
            <div style="margin-top: 6px; color: var(--light-text); font-size: 13px;">
              <i class="fas fa-tag"></i> <?php echo e((string)$p['category']); ?> ·
              <i class="fas fa-clock"></i> <?php echo e((string)$p['duration_days']); ?>D/<?php echo e((string)$p['duration_nights']); ?>N
            </div>
            <p style="margin-top: 10px; color: var(--light-text); font-size: 13px; line-height:1.7;">
              <?php echo e(mb_strimwidth((string)($p['highlights'] ?? ''), 0, 160, '...')); ?>
            </p>
            <div style="display:flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; color: var(--light-text); font-size: 12px;">
              <?php if ((int)$p['includes_flights'] === 1): ?><span><i class="fas fa-plane"></i> Flights</span><?php endif; ?>
              <?php if ((int)$p['includes_hotel'] === 1): ?><span><i class="fas fa-hotel"></i> Hotel</span><?php endif; ?>
              <?php if ((int)$p['includes_meals'] === 1): ?><span><i class="fas fa-utensils"></i> Meals</span><?php endif; ?>
              <?php if ((int)$p['includes_activities'] === 1): ?><span><i class="fas fa-map-marked-alt"></i> Activities</span><?php endif; ?>
            </div>
            <div style="margin-top: 12px;">
              <a class="btn-book" href="<?php echo e(url_path('contact.php')); ?>">Enquire</a>
              <a class="btn-explore" href="<?php echo e(url_path('tours.php')); ?>">Browse tours</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

