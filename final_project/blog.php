<?php
$page_title = 'Blog - ExploreWorld';
$active_nav = 'blog';
require_once __DIR__ . '/includes/header.php';

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$category = isset($_GET['category']) ? trim((string)$_GET['category']) : '';

$where = ["published_at IS NOT NULL"];
$types = '';
$params = [];
if ($category !== '') { $where[] = "category = ?"; $types .= 's'; $params[] = $category; }
if ($q !== '') {
  $where[] = "(title LIKE CONCAT('%', ?, '%') OR excerpt LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%'))";
  $types .= 'sss';
  $params[] = $q; $params[] = $q; $params[] = $q;
}

$sql = "SELECT id, title, slug, category, author, excerpt, image_url, published_at
        FROM blog_posts
        WHERE " . implode(' AND ', $where) . "
        ORDER BY published_at DESC, id DESC
        LIMIT 30";
$stmt = execute_query($sql, $types, $params);
$posts = $stmt ? get_results($stmt) : [];

$catsStmt = execute_query("SELECT category, COUNT(*) AS cnt FROM blog_posts WHERE published_at IS NOT NULL GROUP BY category ORDER BY cnt DESC");
$categories = $catsStmt ? get_results($catsStmt) : [];
?>

<header class="page-header">
  <div class="container">
    <h1>Travel Blog</h1>
    <p>Stories and tips from around the world</p>
  </div>
</header>

<section class="destinations-page">
  <div class="container">
    <div style="display:grid; grid-template-columns: 1.7fr 0.9fr; gap: 24px;">
      <div>
        <div class="filter-section" style="margin-bottom: 18px;">
          <form method="get" style="display:grid; grid-template-columns: 1fr 220px 120px; gap: 10px; align-items:end;">
            <div class="filter-group">
              <label for="q">Search</label>
              <input id="q" name="q" value="<?php echo e($q); ?>" placeholder="Search blog posts...">
            </div>
            <div class="filter-group">
              <label for="category">Category</label>
              <select id="category" name="category">
                <option value="">All</option>
                <?php foreach ($categories as $c): ?>
                  <option value="<?php echo e((string)$c['category']); ?>" <?php echo ((string)$c['category'] === $category) ? 'selected' : ''; ?>>
                    <?php echo e((string)$c['category']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <button class="btn-search" type="submit" style="border-radius:8px;">Apply</button>
          </form>
        </div>

        <?php if (!$posts): ?>
          <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
            No posts found.
          </div>
        <?php endif; ?>

        <div class="grid cols-3">
          <?php foreach ($posts as $p): ?>
            <article style="background:#fff; border-radius:10px; box-shadow: var(--shadow); overflow:hidden;">
              <img src="<?php echo e(url_path($p['image_url'] ?: 'assets/images/hero-bg.svg')); ?>" alt="<?php echo e((string)$p['title']); ?>" style="width:100%; height: 170px; object-fit:cover;">
              <div style="padding: 14px;">
                <div style="display:flex; gap:10px; flex-wrap: wrap; color: var(--light-text); font-size: 12px;">
                  <?php if (!empty($p['category'])): ?><span><i class="fas fa-tag"></i> <?php echo e((string)$p['category']); ?></span><?php endif; ?>
                  <?php if (!empty($p['author'])): ?><span><i class="fas fa-user"></i> <?php echo e((string)$p['author']); ?></span><?php endif; ?>
                  <?php if (!empty($p['published_at'])): ?><span><i class="fas fa-calendar"></i> <?php echo e(date('M d, Y', strtotime((string)$p['published_at']))); ?></span><?php endif; ?>
                </div>
                <h3 style="margin: 10px 0 8px; font-size: 16px;"><?php echo e((string)$p['title']); ?></h3>
                <p style="color: var(--light-text); font-size: 13px; line-height:1.7;">
                  <?php echo e(mb_strimwidth((string)($p['excerpt'] ?? ''), 0, 150, '...')); ?>
                </p>
                <a class="btn-explore" href="<?php echo e(url_path('post.php?slug=' . urlencode((string)$p['slug']))); ?>">Read more</a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>

      <aside>
        <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
          <h3 style="margin-top:0;">Categories</h3>
          <ul style="list-style:none; padding:0; margin: 10px 0 0;">
            <?php foreach ($categories as $c): ?>
              <li style="margin-bottom: 8px;">
                <a href="<?php echo e(url_path('blog.php?category=' . urlencode((string)$c['category']))); ?>">
                  <?php echo e((string)$c['category']); ?> (<?php echo e((string)$c['cnt']); ?>)
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

