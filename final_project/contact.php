<?php
$page_title = 'Contact - ExploreWorld';
$active_nav = 'contact';
require_once __DIR__ . '/includes/header.php';

if (isset($_POST['send'])) {
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $subject = trim((string)($_POST['subject'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        set_flash('error', 'Please fill in all required fields.');
        redirect('contact.php');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Please enter a valid email.');
        redirect('contact.php');
    }

    $user_id = is_logged_in() ? (int)$_SESSION['user']['id'] : null;
    $types = $user_id ? 'isssss' : 'sssss';
    $params = $user_id ? [$user_id, $name, $email, $phone, $subject, $message] : [$name, $email, $phone, $subject, $message];

    $sql = $user_id
        ? "INSERT INTO contact_messages (user_id, name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())"
        : "INSERT INTO contact_messages (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())";

    $ok = execute_query($sql, $types, $params);
    if ($ok === false) {
        set_flash('error', 'Could not send message right now.');
        redirect('contact.php');
    }

    set_flash('success', 'Message sent! We will contact you soon.');
    redirect('contact.php');
}
?>

<section class="destinations-page">
  <div class="container">
    <h1 class="section-title">Contact Us</h1>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
        <h2 style="margin-top:0;">Get in touch</h2>
        <p style="color: var(--light-text); line-height:1.8;">
          Have a question about tours, booking, or custom plans? Send us a message — we’ll reply soon.
        </p>

        <div style="display:grid; gap: 10px; margin-top: 14px; color: var(--light-text);">
          <div><i class="fas fa-map-marker-alt"></i> Kathmandu, Nepal</div>
          <div><i class="fas fa-phone"></i> +91 7068912457</div>
          <div><i class="fas fa-envelope"></i> info@exploreworld.com</div>
          <div><i class="fas fa-clock"></i> Mon–Fri 9:00 AM – 6:00 PM</div>
        </div>
      </div>

      <div style="background:#fff; border-radius:10px; box-shadow: var(--shadow); padding:18px;">
        <h2 style="margin-top:0;">Send a message</h2>
        <form method="post">
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="filter-group">
              <label for="name">Name *</label>
              <input id="name" name="name" type="text" required>
            </div>
            <div class="filter-group">
              <label for="email">Email *</label>
              <input id="email" name="email" type="email" required>
            </div>
          </div>
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px;">
            <div class="filter-group">
              <label for="phone">Phone</label>
              <input id="phone" name="phone" type="tel">
            </div>
            <div class="filter-group">
              <label for="subject">Subject *</label>
              <input id="subject" name="subject" type="text" required>
            </div>
          </div>
          <div class="filter-group" style="margin-top: 12px;">
            <label for="message">Message *</label>
            <textarea id="message" name="message" rows="6" required style="padding:10px; border:2px solid var(--border-color); border-radius:8px; font-size:14px;"></textarea>
          </div>
          <button type="submit" name="send" class="btn-book" style="margin-top: 12px;">Send</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

