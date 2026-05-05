  <section class="newsletter">
    <div class="container">
      <h2>Subscribe to Our Newsletter</h2>
      <p>Get travel deals and updates</p>
      <form class="newsletter-form" method="post" action="<?php echo e(url_path('subscribe.php')); ?>">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" class="btn-subscribe">Subscribe</button>
      </form>
      <div id="newsletterResult" style="margin-top:12px;"></div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <h3>ExploreWorld</h3>
          <p>Your ultimate travel companion for unforgettable adventures around the world.</p>
          <div class="social-links">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
        <div class="footer-col">
          <h3>Quick Links</h3>
          <ul>
            <li><a href="<?php echo e(url_path('index.php')); ?>">Home</a></li>
            <li><a href="<?php echo e(url_path('destinations.php')); ?>">Destinations</a></li>
            <li><a href="<?php echo e(url_path('tours.php')); ?>">Tours</a></li>
            <li><a href="<?php echo e(url_path('packages.php')); ?>">Packages</a></li>
            <li><a href="<?php echo e(url_path('gallery.php')); ?>">Gallery</a></li>
            <li><a href="<?php echo e(url_path('blog.php')); ?>">Blog</a></li>
            <li><a href="<?php echo e(url_path('about.php')); ?>">About</a></li>
            <li><a href="<?php echo e(url_path('contact.php')); ?>">Contact</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h3>Contact Us</h3>
          <ul class="contact-info">
            <li><i class="fas fa-phone"></i> +91 7068912457</li>
            <li><i class="fas fa-envelope"></i> info@exploreworld.com</li>
            <li><i class="fas fa-map-marker-alt"></i> Kathmandu, Nepal</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> ExploreWorld. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="<?php echo e(url_path('assets/js/main.js')); ?>"></script>
  <?php if (!empty($extra_scripts)) echo $extra_scripts; ?>
</body>
</html>

