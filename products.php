<?php if (isset($_GET['detail'])): ?>
  <?php
  $id_product = isset($_GET['id']) ? intval($_GET['id']) : 0;

  $sql = "SELECT * FROM product WHERE id = ? OR product_id = ? LIMIT 1";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("ii", $id_product, $id_product);
  $stmt->execute();
  $product = $stmt->get_result()->fetch_assoc();

  if (!$product) {
    $sql_fallback = "SELECT * FROM product ORDER BY product_name ASC LIMIT 1";
    $product = $con->query($sql_fallback)->fetch_assoc();
  }

  if (isset($_['detail'])) {
    $id_product = isset($_GET['id']) ? intval($_GET['id']) : 0;


  }

  ?>

  <div class="container py-5">
    <div class="row g-5 align-items-center mb-5">
      <div class="col-12 col-lg-6 text-center">
        <div class="detail-img-container p-4">
          <img class="img-fluid rounded-4 shadow-sm" src="admin/assets/images/product/<?= $product['product_photo'] ?>"
            alt="<?= $product['product_name'] ?>">
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="ps-lg-3">
          <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill fw-medium mb-3">
            <i class="bi bi-check-circle-fill me-1"></i> In Stock
          </span>
          <h1 class="fw-bold text-dark mb-2"><?= $product['product_name'] ?></h1>
          <p class="text-muted small mb-3"><i class="bi bi-bag-check me-1"></i>
            <?= isset($product['sold']) ? $product['sold'] : '100+' ?> Terjual</p>
          <div class="product-price-tag mb-4">
            <h3 class="fw-bold text-success mb-0">Rp <?= number_format($product['product_price'], 0, ',', '.') ?></h3>
          </div>

          <div class="mb-4">
            <label class="form-label text-dark fw-semibold mb-2">Pilihan Varian / Paket</label>
            <div class="row g-2 variant-selector">
              <div class="col-3">
                <button type="button" class="btn btn-variant w-100 active">5 pcs</button>
              </div>
              <div class="col-3">
                <button type="button" class="btn btn-variant w-100">10 pcs</button>
              </div>
              <div class="col-3">
                <button type="button" class="btn btn-variant w-100">15 pcs</button>
              </div>
              <div class="col-3">
                <button type="button" class="btn btn-variant w-100">20 pcs</button>
              </div>
            </div>
          </div>

          <form action="cart.php" method="POST" class="d-flex align-items-center gap-3 pt-2">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?? $product['product_id'] ?? '' ?>">
            <div class="quantity-control d-flex align-items-center">
              <button type="button" class="btn btn-qty" onclick="decrementQty()">-</button>
              <input type="number" id="qtyInput" name="qty"
                class="qty-number-input border-0 text-center fw-bold text-dark" value="1" min="1" readonly
                style="width: 40px; background: transparent;">
              <button type="button" class="btn btn-qty" onclick="incrementQty()">+</button>
            </div>
            <button type="submit" name="add_to_cart" class="btn btn-custom flex-grow-1 py-2 fw-semibold">
              <i class="bi bi-cart-plus me-2"></i> Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <section class="mt-5 pt-4 border-top">
      <h4 class="fw-bold text-dark mb-4">Ulasan Pembeli</h4>
      <div class="row g-4">
        <?php
        $sql_review = "SELECT * FROM review ORDER BY id DESC LIMIT 2";
        $query_review = $con->query($sql_review);

        if ($query_review && $query_review->num_rows > 0) {
          while ($rev = $query_review->fetch_assoc()) {
            ?>
            <div class="col-12 col-md-6">
              <div class="review-card p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="avatar-circle me-3">
                    <i class="bi bi-person"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-0 text-dark">
                      <?= htmlspecialchars($rev['name'] ?? $rev['user_name'] ?? 'Pelanggan') ?>
                    </h6>
                    <div class="star-rating text-warning small">
                      <?php
                      $rating = $rev['rating'] ?? 5;
                      for ($s = 1; $s <= 5; $s++) {
                        echo $s <= $rating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                      }
                      ?>
                    </div>
                  </div>
                </div>
                <p class="text-muted mb-0 small">
                  <?= htmlspecialchars($rev['comment'] ?? $rev['message'] ?? 'Produk sangat bagus dan bunganya segar!') ?>
                </p>
              </div>
            </div>
            <?php
          }
        } else {
          ?>
          <div class="col-12 col-md-6">
            <div class="review-card p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="avatar-circle me-3">
                  <i class="bi bi-person"></i>
                </div>
                <div>
                  <h6 class="fw-bold mb-0 text-dark">Siti Rahma</h6>
                  <div class="star-rating text-warning small">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                      class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                </div>
              </div>
              <p class="text-muted mb-0 small">Bunganya sangat segar dan pengirimannya tepat waktu. Recommended seller!</p>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="review-card p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="avatar-circle me-3">
                  <i class="bi bi-person"></i>
                </div>
                <div>
                  <h6 class="fw-bold mb-0 text-dark">Budi Santoso</h6>
                  <div class="star-rating text-warning small">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                      class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                  </div>
                </div>
              </div>
              <p class="text-muted mb-0 small">Rangkaian bunganya rapi dan wangi. Istri saya sangat suka!</p>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
    </section>

    <section class="mt-5 pt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Rekomendasi Produk Lainnya</h4>
      </div>
      <div class="row g-4">
        <?php
        $sql_rec = "SELECT * FROM product ORDER BY RAND() LIMIT 4";
        $query_rec = $con->query($sql_rec);
        while ($rec = $query_rec->fetch_assoc()) {
          ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="product-card h-100">
              <div class="product-img-holder">
                <img
                  src="admin/assets/images/product/<?= !empty($rec['product_photo']) ? htmlspecialchars($rec['product_photo']) : 'product.png' ?>"
                  alt="<?= htmlspecialchars($rec['product_name'] ?? 'Product') ?>"
                  onerror="this.src='admin/assets/images/product/product.png'">
              </div>
              <div class="product-body p-3">
                <h6 class="product-title mb-1"><?= $rec['product_name'] ?></h6>
                <p class="text-muted extra-small mb-2"><?= $rec['sold'] ?? '100+' ?> Terjual</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <span class="product-price">Rp <?= number_format($rec['product_price'], 0, ',', '.') ?></span>
                  <a href="index.php?page=products&detail&id=<?= $rec['id'] ?? $rec['product_id'] ?? '' ?>"
                    class="btn btn-outline-custom btn-sm">
                    <i class="bi bi-eye me-1"></i> Detail
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </section>
  </div>

<?php else: ?>

  <div class="container py-5">
    <div class="row g-3 align-items-center mb-5">
      <div class="col-12 col-md-6">
        <form action="" method="GET">
          <input type="hidden" name="page" value="products">
          <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="form-control custom-search-input" name="search" placeholder="Search products..."
              value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
          </div>
        </form>
      </div>
      <div class="col-12 col-md-6">
        <div class="d-flex gap-2 justify-content-md-end">
          <a href="index.php?page=products&sort=category" class="btn btn-filter">
            <i class="bi bi-grid me-2"></i> Category
          </a>
          <a href="index.php?page=products&sort=price" class="btn btn-filter">
            <i class="bi bi-sliders me-2"></i> Price
          </a>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <?php
      $search = isset($_GET['search']) ? $con->real_escape_string($_GET['search']) : '';
      $sort = isset($_GET['sort']) ? $_GET['sort'] : '';

      $sql_prod = "SELECT * FROM product";
      if (!empty($search)) {
        $sql_prod .= " WHERE product_name LIKE '%$search%'";
      }

      if ($sort == 'price') {
        $sql_prod .= " ORDER BY product_price ASC";
      } else {
        $sql_prod .= " ORDER BY product_name ASC";
      }

      $query_prod = $con->query($sql_prod);

      if ($query_prod && $query_prod->num_rows > 0) {
        while ($p = $query_prod->fetch_assoc()) {
          ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="product-card h-100">
              <div class="product-img-holder">
                <img src="admin/assets/images/product/<?= $p['product_photo'] ?>" alt="<?= $p['product_name'] ?>">
              </div>
              <div class="product-body p-3">
                <h6 class="product-title mb-1"><?= $p['product_name'] ?></h6>
                <p class="text-muted extra-small mb-2"><?= $p['sold'] ?? '100+' ?> Terjual</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <span class="product-price">Rp <?= number_format($p['product_price'], 0, ',', '.') ?></span>
                  <a href="index.php?page=products&detail&id=<?= $p['id'] ?? $p['product_id'] ?? '' ?>"
                    class="btn btn-outline-custom btn-sm">
                    <i class="bi bi-cart-plus me-1"></i> Add
                  </a>
                </div>
              </div>
            </div>
          </div>
          <?php
        }
      } else {
        echo '<div class="col-12 text-center py-5"><p class="text-muted">Product not found.</p></div>';
      }
      ?>
    </div>
  </div>

<?php endif; ?>

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

  .bg-soft-success {
    background-color: var(--primary-soft);
  }

  .text-success {
    color: var(--primary-color) !important;
  }

  .detail-img-container {
    background-color: var(--bg-light);
    border-radius: 24px;
    border: 1px solid var(--border-color);
  }

  .detail-img-container img {
    max-height: 420px;
    width: 100%;
    object-fit: cover;
  }

  .btn-variant {
    border: 1px solid var(--border-color);
    background-color: #ffffff;
    color: var(--text-dark);
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 10px;
    padding: 0.5rem;
    transition: all 0.2s ease;
  }

  .btn-variant:hover,
  .btn-variant.active {
    border-color: var(--primary-color);
    background-color: var(--primary-soft);
    color: var(--primary-color);
    font-weight: 600;
  }

  .quantity-control {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 0.25rem;
    background-color: #ffffff;
  }

  .btn-qty {
    border: none;
    background: transparent;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: var(--text-dark);
    border-radius: 8px;
    transition: background-color 0.2s ease;
  }

  .btn-qty:hover {
    background-color: var(--bg-light);
  }

  .review-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--border-color);
  }

  .avatar-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background-color: var(--primary-soft);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
  }

  .search-box {
    position: relative;
  }

  .search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
  }

  .custom-search-input {
    border-radius: 50px;
    padding-left: 2.75rem;
    padding-right: 1.25rem;
    padding-top: 0.65rem;
    padding-bottom: 0.65rem;
    border: 1px solid var(--border-color);
    background-color: #ffffff;
  }

  .custom-search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--primary-soft);
  }

  .btn-filter {
    border: 1px solid var(--border-color);
    background-color: #ffffff;
    color: var(--text-dark);
    border-radius: 50px;
    padding: 0.65rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
  }

  .btn-filter:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background-color: var(--primary-soft);
  }

  .product-card {
    background: #67d13243;
    border-radius: 16px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
  }

  .product-img-holder {
    height: 200px;
    width: 100%;
    overflow: hidden;
    background: var(--bg-light);
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

  .product-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-dark);
  }

  .product-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary-color);
  }

  .btn-custom {
    background-color: var(--primary-color);
    color: #ffffff;
    border-radius: 12px;
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
    text-decoration: none;
  }

  .btn-outline-custom:hover {
    background-color: var(--primary-color);
    color: #ffffff;
  }

  .extra-small {
    font-size: 0.75rem;
  }
</style>

<script>
  function incrementQty() {
    let qty = document.getElementById('qtyInput');
    qty.value = parseInt(qty.value) + 1;
  }
  function decrementQty() {
    let qty = document.getElementById('qtyInput');
    if (parseInt(qty.value) > 1) {
      qty.value = parseInt(qty.value) - 1;
    }
  }
</script>