<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main" style="background-color: white !important;">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="" target="_blank">
      <!-- <img src="../public/foto/logo.png" class="navbar-brand-img"> -->
      <span class="ms-1 font-weight-bold" style="font-size: 1.5rem; letter-spacing: 2px; color: #67d132;">
        <i class="bi bi-leaf me-2"></i> Florist
      </span>
    </a>
  </div>

  <hr class="horizontal dark mt-0">

  <div class="collapse navbar-collapse" style="z-index: 9999 !important; height: 100vh !important;" id="sidenav-collapse-main">
    <ul class="navbar-nav">

      <!-- Dashboard -->
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'dashboard') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=dashboard">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi bi-shop"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <h6 class="ps-3 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Produk</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'kategori') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=kategori">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-bookmark-heart-fill"></i>
          </div>
          <span class="nav-link-text ms-1">Kategori</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'produk') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=produk">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-flower1"></i>
          </div>
          <span class="nav-link-text ms-1">Produk</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'pembelian') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=pembelian">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-truck"></i>
          </div>
          <span class="nav-link-text ms-1">Pembelian</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'stok') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=stok">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-boxes"></i>
          </div>
          <span class="nav-link-text ms-1">Stok Produk</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <h6 class="ps-3 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pelanggan</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'data-pelanggan') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=data-pelanggan">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-people"></i>
          </div>
          <span class="nav-link-text ms-1">Data Pelanggan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'kritiksaran') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=kritiksaran">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-bookmark-heart-fill"></i>
          </div>
          <span class="nav-link-text ms-1">Kritik dan Saran</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'artikel') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=artikel">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-book"></i>
          </div>
          <span class="nav-link-text ms-1">Artikel</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <h6 class="ps-3 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Penjualan</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'tambah-pesanan') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=tambah-pesanan">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-cart"></i>
          </div>
          <span class="nav-link-text ms-1">Tambah Pesanan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'produk') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=produk">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-cart"></i>
          </div>
          <span class="nav-link-text ms-1">Daftar Pesanan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'pembelian') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=pembelian">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center" style="background-image: linear-gradient(310deg, #67d132 100%)">
            <i class="bi text-sm bi-files"></i>
          </div>
          <span class="nav-link-text ms-1">Rekap</span>
        </a>
      </li>

    </ul>
  </div>
</aside>