<?php
$page_title = 'Destinations - ExploreWorld';
$active_nav = 'destinations';
require_once __DIR__ . '/includes/header.php';

$country = isset($_GET['country']) ? trim((string)$_GET['country']) : '';
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

$where = [];
$types = '';
$params = [];
if ($country !== '') { $where[] = 'country = ?'; $types .= 's'; $params[] = $country; }
if ($q !== '') { $where[] = '(name LIKE CONCAT("%", ?, "%") OR description LIKE CONCAT("%", ?, "%") OR location LIKE CONCAT("%", ?, "%"))'; $types .= 'sss'; $params[] = $q; $params[] = $q; $params[] = $q; }
$sql = "SELECT id, name, description, location, country, image_url, rating FROM destinations";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY rating DESC, id ASC";

$stmt = execute_query($sql, $types, $params);
$destinations = $stmt ? get_results($stmt) : [];

$countriesStmt = execute_query("SELECT DISTINCT country FROM destinations WHERE country IS NOT NULL AND country <> '' ORDER BY country ASC");
$countries = $countriesStmt ? array_map(fn($r) => $r['country'], get_results($countriesStmt)) : [];
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title">Explore Our Destinations</h1>

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
          <input type="text" id="q" name="q" placeholder="Search destinations..." value="<?php echo e($q); ?>">
        </div>
        <div class="filter-group" style="align-self:end;">
          <button class="btn-search" type="submit" style="border-radius:8px;">Apply</button>
        </div>
      </form>
    </div>

    <div class="destination-grid">
      <?php if (!$destinations): ?>
        <div style="grid-column: 1/-1; background: #fff; padding: 18px; border-radius: 10px; box-shadow: var(--shadow);">
          No destinations found.
        </div>
      <?php endif; ?>

      <?php foreach ($destinations as $d): ?>
        <div class="destination-card">
          <div class="destination-image" style="position:relative; height:200px; overflow:hidden;">
            <img src="<?php echo e(url_path($d['image_url'] ?: 'assets/images/nepal.svg')); ?>" alt="<?php echo e($d['name']); ?>" style="width:100%; height:100%; object-fit:cover;">
            <?php if (!is_null($d['rating'])): ?>
              <div class="destination-rating" style="position:absolute; top:10px; right:10px; background:rgba(255,255,255,0.9); padding:5px 10px; border-radius:20px; font-size:14px; font-weight:600; color:var(--primary-color);">
                <i class="fas fa-star"></i> <?php echo e((string)$d['rating']); ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="destination-content" style="padding:20px;">
            <h3 style="margin-bottom:10px;"><?php echo e($d['name']); ?></h3>
            <div class="destination-location" style="display:flex; align-items:center; gap:6px; color:var(--light-text); font-size:14px; margin-bottom: 12px;">
              <i class="fas fa-map-marker-alt"></i> <?php echo e($d['location'] ?: $d['country']); ?>
            </div>
            <p class="destination-description" style="color:var(--light-text); font-size:14px; margin-bottom: 16px; line-height:1.6;">
              <?php echo e(mb_strimwidth((string)($d['description'] ?? ''), 0, 140, '...')); ?>
            </p>
            <div class="destination-actions" style="display:flex; justify-content:space-between; align-items:center;">
              <a href="<?php echo e(url_path('tours.php?country=' . urlencode((string)$d['country']))); ?>" class="btn-explore">View tours</a>
              <div class="destination-price" style="font-size:14px; color:var(--light-text);">
                <?php echo e($d['country'] ?: ''); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

