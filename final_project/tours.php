<?php
$page_title = 'Tours - ExploreWorld';
$active_nav = 'tours';
require_once __DIR__ . '/includes/header.php';

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$country = isset($_GET['country']) ? trim((string)$_GET['country']) : '';

$where = [];
$types = '';
$params = [];
if ($country !== '') { $where[] = 'd.country = ?'; $types .= 's'; $params[] = $country; }
if ($q !== '') {
  $where[] = '(t.name LIKE CONCAT("%", ?, "%") OR t.description LIKE CONCAT("%", ?, "%") OR d.name LIKE CONCAT("%", ?, "%") OR d.country LIKE CONCAT("%", ?, "%"))';
  $types .= 'ssss';
  $params[] = $q; $params[] = $q; $params[] = $q; $params[] = $q;
}

$sql = "SELECT t.id, t.name, t.description, t.duration, t.difficulty, t.price, t.image_url,
               d.name AS destination_name, d.country
        FROM tours t
        LEFT JOIN destinations d ON d.id = t.destination_id";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY t.created_at DESC, t.id DESC";

$stmt = execute_query($sql, $types, $params);
$tours = $stmt ? get_results($stmt) : [];

$countriesStmt = execute_query("SELECT DISTINCT country FROM destinations WHERE country IS NOT NULL AND country <> '' ORDER BY country ASC");
$countries = $countriesStmt ? array_map(fn($r) => $r['country'], get_results($countriesStmt)) : [];
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title">Tours</h1>

    <div class="filter-section">
      <form method="get" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
        <div class="filter-group">
          <label for="country">Country</label>
          <select id="country" name="country">
            <option value="">All Countries</option>
            <?php foreach ($countries as $c): ?>
              <option value="<?php echo e($c); ?>" <?php echo $c === $country ? 'selected' : ''; ?>><?php echo e($c); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <label for="q">Search</label>
          <input type="text" id="q" name="q" placeholder="Search tours..." value="<?php echo e($q); ?>">
        </div>
        <div class="filter-group" style="align-self:end;">
          <button class="btn-search" type="submit" style="border-radius:8px;">Apply</button>
        </div>
      </form>
    </div>

    <div class="tour-grid">
      <?php if (!$tours): ?>
        <div style="grid-column: 1/-1; background: #fff; padding: 18px; border-radius: 10px; box-shadow: var(--shadow);">
          No tours found.
        </div>
      <?php endif; ?>

      <?php foreach ($tours as $t): ?>
        <div class="tour-card">
          <img src="<?php echo e(url_path($t['image_url'] ?: 'assets/images/everest.svg')); ?>" alt="<?php echo e($t['name']); ?>">
          <div class="card-content">
            <h3><?php echo e($t['name']); ?></h3>
            <p><?php echo e((string)$t['duration']); ?> Days | <?php echo e((string)$t['difficulty']); ?><?php if (!empty($t['country'])): ?> | <?php echo e((string)$t['country']); ?><?php endif; ?></p>
            <div class="price">$<?php echo e(number_format((float)$t['price'], 0)); ?></div>
            <a href="<?php echo e(url_path('tour.php?id=' . urlencode((string)$t['id']))); ?>" class="btn-book">View & Book</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

