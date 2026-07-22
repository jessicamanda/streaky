<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
    <div class="sidebar-header d-flex justify-content-center align-items-center">
        <a class="brand-mark" aria-label="adminHMD dashboard">
            <i class="bi bi-flower3"></i> 
            <span class="brand-copy">
                <span class="brand-title">FLORIST.</span>
            </span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-link <?php if (isset($_GET['page'])) {
            if (htmlspecialchars($_GET['page']) == 'dashboard') {
              echo "active";
            }
          } ?>" href="index.php?page=dashboard" aria-current="page">
            <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>
        <a class="nav-link" <?php if (isset($_GET['page'])) {
            if (htmlspecialchars($_GET['page']) == 'users') {
              echo "active";
            }
          } ?> href="index.php?page=users">
            <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
            <span class="nav-text">Users</span>
        </a>
        <a class="nav-link" <?php if (isset($_GET['page'])) {
            if (htmlspecialchars($_GET['page']) == 'users') {
              echo "active";
            }
          } ?> href="index.php?page=category">
            <span class="nav-icon"><i class="bi bi-box-fill" aria-hidden="true"></i></span>
            <span class="nav-text">Category</span>
        </a>
        <a class="nav-link" <?php if (isset($_GET['page'])) {
            if (htmlspecialchars($_GET['page']) == 'users') {
              echo "active";
            }
          } ?> href="index.php?page=product">
            <span class="nav-icon"><i class="bi bi-boxes" aria-hidden="true"></i></span>
            <span class="nav-text">Product</span>
        </a>
        <a class="nav-link" <?php if (isset($_GET['page'])) {
            if (htmlspecialchars($_GET['page']) == 'contact') {
              echo "active";
            }
          } ?> href="index.php?page=contact">
            <span class="nav-icon"><i class="bi bi-megaphone-fill" aria-hidden="true"></i></span>
            <span class="nav-text">Contact</span>
        </a>
        <a class="nav-link" href="logout.php">
            <span class="nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
            <span class="nav-text">Logout</span>
        </a>
    </nav>

</aside>
