<?php
if (isset($_POST['contact'])) {
  $name = htmlspecialchars($_POST['name']);
  $whatsapp = htmlspecialchars($_POST['whatsapp']);
  $message = htmlspecialchars($_POST['message']);

  $sql = "INSERT INTO contact (name, whatsapp, message) VALUES (?, ?, ?)";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("sss", $name, $whatsapp, $message);
  $stmt->execute();
  if ($stmt) {
    echo "<script>
            alert('Success');
            window.location.href = 'main.php';
          </script>";
  } else {
    echo "<script>
            alert('Error');
            window.location.href = 'main.php';
          </script>";
  }
}
?>

<section class="hero-section" id="hero">
  <div class="container text-center py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <img class="img-fluid rounded-4 mb-4 hero-img" src="assets/img/hero.png" alt="Fresh Flowers">
        <span class="badge bg-soft-primary text-primary px-3 py-2 mb-2 rounded-pill fw-semibold">Natural & Pretty
          Flowers</span>
        <h1 class="display-4 fw-bold text-dark mb-3">Fresh Flowers</h1>
        <p class="text-muted leading-relaxed max-w-600 mx-auto">
         We offer a handpicked collection of fresh, premium flowers straight from the farm. Professionally arranged to elevate your special moments and convey your heartfelt feelings to those who matter most.
        </p>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light-section" id="categories">
  <div class="text-center mb-5">
    <h2 class="section-title">Categories</h2>
    <p class="text-muted">Explore our wide selection of fresh blooms</p>
  </div>
  <div class="row g-4 justify-content-center">
    <?php
    $sql = "SELECT * FROM category ORDER BY name ASC";
    $query = $con->query($sql);
    while ($row = $query->fetch_assoc()) {
      ?>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="category-card text-center p-3 h-100">
          <div class="category-img-wrapper mb-3">
            <img src="admin/assets/images/category/<?= $row['photo'] ?>" alt="<?= $row['name'] ?>" class="img-fluid">
          </div>
          <h6 class="fw-semibold text-dark mb-0"><?= $row['name'] ?></h6>
        </div>
      </div>
    <?php } ?>
  </div>
</section>

<section class="py-5" id="products">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Our Products</h2>
      <p class="text-muted">Handpicked arrangements crafted with love</p>
    </div>
    <div class="row g-4">
      <?php
      $sql = "SELECT product.*, category.name as category_name FROM product LEFT JOIN category ON product.product_category = category.id ORDER BY product_name ASC";
      $query = $con->query($sql);
      while ($row = $query->fetch_assoc()) {
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="product-card h-100">
            <div class="product-img-holder">
              <?php if (!empty($row['category_name'])): ?>
                <span class="category-badge"><?= $row['category_name'] ?></span>
              <?php endif; ?>
              <img src="admin/assets/images/product/<?= $row['product_photo'] ?>" alt="<?= $row['product_name'] ?>">
            </div>
            <div class="product-body p-3">
              <h5 class="product-title"><?= $row['product_name'] ?></h5>
              <small class="text-muted d-block mb-2"><?= $row['product_description'] ?></small>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="product-price">Rp <?= number_format($row['product_price'], 0, ',', '.') ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <div class="text-center mt-5">
      <a href="index.php?page=products" class="btn btn-custom px-4 py-2">Show All Products</a>
    </div>
  </div>
</section>

<section class="features-section py-5" id="services">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-6 col-md-3">
        <div class="feature-box p-3">
          <div class="icon-wrapper mb-3">
            <i class="bi bi-trophy"></i>
          </div>
          <h6 class="fw-bold mb-1">Best Products</h6>
          <p class="text-muted small mb-0">Premium quality flowers</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-box p-3">
          <div class="icon-wrapper mb-3">
            <i class="bi bi-stopwatch"></i>
          </div>
          <h6 class="fw-bold mb-1">Fast Response</h6>
          <p class="text-muted small mb-0">Ready to help anytime</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-box p-3">
          <div class="icon-wrapper mb-3">
            <i class="bi bi-truck"></i>
          </div>
          <h6 class="fw-bold mb-1">Free Shipping</h6>
          <p class="text-muted small mb-0">On selected orders</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-box p-3">
          <div class="icon-wrapper mb-3">
            <i class="bi bi-cash-coin"></i>
          </div>
          <h6 class="fw-bold mb-1">Easy Payment</h6>
          <p class="text-muted small mb-0">Secure transactions</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light-section" id="contact">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Contact Us</h2>
      <p class="text-muted">We'd love to hear from you</p>
    </div>
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <div class="contact-form-card p-4 p-md-5">
          <form action="" method="POST">
            <div class="mb-3">
              <label class="form-label text-dark fw-medium">Your Name</label>
              <input type="text" class="form-control custom-input" name="name" placeholder="John Doe" required>
            </div>
            <div class="mb-3">
              <label class="form-label text-dark fw-medium">WhatsApp Number</label>
              <input type="number" class="form-control custom-input" name="whatsapp" placeholder="08123456789" required>
            </div>
            <div class="mb-4">
              <label class="form-label text-dark fw-medium">Your Message</label>
              <textarea class="form-control custom-input" name="message" rows="4" placeholder="How can we help you?"
                required></textarea>
            </div>
            <button type="submit" class="btn btn-custom w-100 py-2 fw-semibold" name="contact">
              <i class="bi bi-send-fill me-2"></i>Send Message
            </button>
          </form>

          <div class="row pt-4 mt-4 border-top">
            <div class="col-6">
              <a href="#" class="social-link text-decoration-none">
                <i class="bi bi-instagram me-2"></i>@florist.co
              </a>
            </div>
            <div class="col-6">
              <a href="#" class="social-link text-decoration-none">
                <i class="bi bi-facebook me-2"></i>@florist.co
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <img src="assets/img/contact.png" alt="Contact Us" class="img-fluid rounded-4 shadow-sm">
      </div>
    </div>
</section>

<style>
  :root {
    --primary-color: #67d132;
    --primary-hover: #1b5e20;
    --primary-soft: rgba(46, 125, 50, 0.08);
    --bg-light: #f8faf8;
    --text-dark: #1a202c;
    --border-color: #e2e8f0;
  }

  body {
    color: var(--text-dark);
  }

  .section-title {
    font-weight: 700;
    color: var(--text-dark);
    position: relative;
    display: inline-block;
  }

  .bg-light-section {
    background-color: var(--bg-light);
  }

  .bg-soft-primary {
    background-color: var(--primary-soft);
  }

  .text-primary {
    color: var(--primary-color) !important;
  }

  .hero-img {
    max-height: 400px;
    object-fit: cover;
    width: 100%;
  }

  #categories {
    background-color: #67d132;
  }

  .category-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
  }

  .category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border-color: var(--primary-color);
  }

  .category-img-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    background: var(--bg-light);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .category-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .product-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
  }

  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
  }

  .product-img-holder {
    height: 220px;
    width: 100%;
    overflow: hidden;
    background: var(--bg-light);
    position: relative;
  }

  .product-img-holder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .product-card:hover .product-img-holder img {
    transform: scale(1.05);
  }

  .category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    background-color: rgba(255, 255, 255, 0.9);
    color: var(--primary-color);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(4px);
  }

  .product-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
  }

  .product-price {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--primary-color);
  }

  .btn-custom {
    background-color: var(--primary-color);
    color: #ffffff;
    border-radius: 10px;
    border: none;
    transition: all 0.2s ease;
  }

  .btn-custom:hover {
    background-color: var(--primary-hover);
    color: #ffffff;
  }

  .btn-outline-custom {
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
    border-radius: 8px;
    transition: all 0.2s ease;
  }

  .btn-outline-custom:hover {
    background-color: var(--primary-color);
    color: #ffffff;
  }

  .feature-box {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--border-color);
  }

  #services {
    background-color: #67d132;
  }

  .icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary-soft);
    color: var(--primary-color);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
  }

  .contact-form-card {
    background: #67d13243;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
  }

  .custom-input {
    border-radius: 10px;
    border: 1px solid var(--border-color);
    padding: 0.75rem 1rem;
    background-color: #ffffff;
  }

  .custom-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--primary-soft);
  }

  .social-link {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    transition: color 0.2s ease;
  }

  .social-link:hover {
    color: var(--primary-color);
  }

  .social-link i {
    font-size: 1.25rem;
    color: var(--primary-color);
  }
</style>