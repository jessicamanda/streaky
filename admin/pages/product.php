<?php
// 1. TAMBAH PRODUCT
if (isset($_POST['add'])) {
    $product_name = $_POST['product_name'] ?? '';
    $product_category = $_POST['product_category'] ?? '';
    $product_description = $_POST['product_description'] ?? '';
    $product_price = (float) ($_POST['product_price'] ?? 0);
    $product_status = $_POST['product_status'] ?? 'active';
    $product_photo = 'product.png';

    if (!empty($_FILES['product_photo']['name'])) {
        $result = uploadFile($_FILES['product_photo'], 'assets/images/product/');
        $product_photo = $result['file_name'];
    }

    $stmt = $con->prepare("INSERT INTO product (product_name, product_category, product_description, product_price, product_status, product_photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $product_name, $product_category, $product_description, $product_price, $product_status, $product_photo);

    if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Product added successfully!'); document.location.href = 'index.php?page=product';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to add product: " . addslashes($stmt->error) . "');</script>";
        $stmt->close();
    }
}

// 2. UPDATE PRODUCT
if (isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $product_name = $_POST['product_name'] ?? '';
    $product_category = $_POST['product_category'] ?? '';
    $product_description = $_POST['product_description'] ?? '';
    $product_price = (float) ($_POST['product_price'] ?? 0);
    $product_status = $_POST['product_status'] ?? 'active';

    $stmt_get = $con->prepare("SELECT product_photo FROM product WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $singleproduct = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $product_photo = $singleproduct['product_photo'] ?? 'product.png';

    if (!empty($_FILES['product_photo']['name'])) {
        if ($product_photo != 'product.png' && file_exists('assets/images/product/' . $product_photo)) {
            unlink('assets/images/product/' . $product_photo);
        }
        $result = uploadFile($_FILES['product_photo'], 'assets/images/product/');
        $product_photo = $result['file_name'];
    }

    $stmt = $con->prepare("UPDATE product SET product_name = ?, product_category = ?, product_description = ?, product_price = ?, product_photo = ?, product_status = ? WHERE id = ?");
    $stmt->bind_param("sssissi", $product_name, $product_category, $product_description, $product_price, $product_photo, $product_status, $id);

    if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Product updated successfully!'); document.location.href = 'index.php?page=product';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update product: " . addslashes($stmt->error) . "');</script>";
        $stmt->close();
    }
}

// 3. DELETE PRODUCT
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $stmt_get = $con->prepare("SELECT product_photo FROM product WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $singleproduct = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    if ($singleproduct) {
        $product_photo = $singleproduct['product_photo'];
        if ($product_photo != 'product.png' && file_exists('assets/images/product/' . $product_photo)) {
            unlink('assets/images/product/' . $product_photo);
        }

        $stmt_del = $con->prepare("DELETE FROM product WHERE id = ?");
        $stmt_del->bind_param("i", $id);
        $stmt_del->execute();
        $stmt_del->close();
    }

    echo "<script>alert('Product deleted successfully!'); document.location.href = 'index.php?page=product';</script>";
    exit();
}
?>

<style>
    .product-card {
        transition: transform 0.2s;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    .product-img-thumb {
        height: 60px;
        width: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg"></i> Add Product
</button>

<!-- MODAL ADD PRODUCT -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="product_name" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="product_description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" step="any" name="product_price" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="product_category" class="form-select" required>
                                <option value="" disabled selected>Select Category</option>
                                <?php
                                $getCategory = $con->query("SELECT * FROM category ORDER BY name ASC");
                                while ($cat = $getCategory->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($cat['id']) . '">' . htmlspecialchars($cat['name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Photo</label>
                            <input type="file" name="product_photo" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="product_status" class="form-select">
                                <option value="active" selected>Active</option>
                                <option value="nonactive">Non Active</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT PRODUCT -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_id">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="product_name" id="product_name_id" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="product_description" id="product_description_id" class="form-control"
                                rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" step="any" name="product_price" id="product_price_id"
                                class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="product_category" id="product_category_id" class="form-select" required>
                                <option value="" disabled>Select Category</option>
                                <?php
                                $getCategoryEdit = $con->query("SELECT * FROM category ORDER BY name ASC");
                                while ($cat = $getCategoryEdit->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($cat['id']) . '">' . htmlspecialchars($cat['name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Change Photo</label>
                            <input type="file" name="product_photo" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="product_status" id="product_status_id" class="form-select">
                                <option value="active">Active</option>
                                <option value="nonactive">Non Active</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SEARCH FORM -->
<div class="card shadow-sm p-2 mb-3">
    <form method="get">
        <input type="hidden" name="page" value="product">
        <div class="row g-2">
            <div class="col-8 col-md-9">
                <input type="text" class="form-control form-control-sm" name="search"
                    placeholder="Search product name..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            </div>
            <div class="col-2 col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-2 col-md-2">
                <a href="index.php?page=product" class="btn btn-secondary btn-sm w-100">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- TABLE PRODUCT LIST -->
<div class="card shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="width: 100%; font-size: 13px;" id="myTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Stock In</th>
                    <th>Stock Out</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $search = $_GET['search'] ?? '';

                if (!empty($search)) {
                    $stmt_list = $con->prepare("SELECT p.*, c.name AS category_name, SUM(p.qty) as stock_in, SUM(o.product_qty) as stock_out FROM product p 
                    LEFT JOIN category c ON p.product_category = c.id
                    LEFT JOIN purchase_order_items po ON p.name = po.product_name 
                    LEFT JOIN order_items o ON p.id = o.product_id 
                    WHERE p.product_name LIKE ?
                    GROUP BY p.id
                    ORDER BY p.product_name ASC");
                    $searchTerm = "%" . $search . "%";
                    $stmt_list->bind_param("s", $searchTerm);
                } else {
                    $stmt_list = $con->prepare("SELECT p.*, c.name AS category_name, SUM(po.qty) as stock_in, SUM(o.product_qty) as stock_out FROM product p 
                    LEFT JOIN category c ON p.product_category = c.id
                    LEFT JOIN purchase_order_items po ON p.product_name = po.product_name 
                    LEFT JOIN order_items o ON p.id = o.product_id 
                    GROUP BY p.id
                    ORDER BY p.product_name ASC");
                }

                $stmt_list->execute();
                $get = $stmt_list->get_result();

                while ($row = $get->fetch_assoc()) {
                    $stock = $row['stock_in'] - $row['stock_out'];
                    $photo = !empty($row['product_photo']) ? $row['product_photo'] : 'product.png';
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <img src="assets/images/product/<?= htmlspecialchars($photo) ?>" alt="product"
                                class="product-img-thumb">
                        </td>
                        <td><strong><?= htmlspecialchars($row['product_name']) ?></strong></td>
                        <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
                        <td>Rp<?= number_format($row['product_price'], 2) ?></td>
                        <td><?= htmlspecialchars(substr($row['product_description'] ?? '', 0, 50)) ?>...</td>
                        <td>
                            <span class="badge <?= ($row['product_status'] == 'active') ? 'bg-success' : 'bg-danger' ?>" style="text-transform: capitalize">
                                <?= htmlspecialchars($row['product_status']) ?>
                            </span>
                        </td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['stock_in'] ?? 0) ?> Pcs </span></td>
                        <td><span class="badge bg-danger"><?= htmlspecialchars($row['stock_out'] ?? 0) ?> Pcs </span></td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($stock ?? 0) ?> Pcs </span></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                onclick="setEditModalData('<?= $row['id'] ?>', '<?= addslashes($row['product_name']) ?>', '<?= $row['product_category'] ?>', '<?= addslashes($row['product_description']) ?>', '<?= $row['product_price'] ?>', '<?= $row['product_status'] ?>')">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="index.php?page=product&delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this product?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php }
                $stmt_list->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function setEditModalData(id, name, category, description, price, status) {
        document.getElementById('id_id').value = id;
        document.getElementById('product_name_id').value = name;
        document.getElementById('product_category_id').value = category;
        document.getElementById('product_description_id').value = description;
        document.getElementById('product_price_id').value = price;

        let finalStatus = (status && status.trim() !== '') ? status.toLowerCase() : 'nonactive';
        document.getElementById('product_status_id').value = finalStatus;
    }
</script>