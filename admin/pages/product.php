<div>
    <?php
    if (isset($_POST['add'])) {
        $product_name = $_POST['product_name'];
        $product_category = $_POST['product_category'];
        $product_description = $_POST['product_description'];
        $product_price = $_POST['product_price'];
        $product_status = $_POST['product_status'];
        $product_photo = 'product.jpg';

        if ($_FILES['product_photo']['name'] != '') {
            $result = uploadFile($_FILES['product_photo'], 'assets/images/product/');
            $product_photo = $result['file_name'];
        }

        $con->query("INSERT INTO product (product_name, product_category, product_description, 
        product_price, product_status, product_photo) 
        VALUES ('$product_name', '$product_category', '$product_description', 
        '$product_price', '$product_status', '$product_photo')");
        echo "<script>alert('Success'); document.location.href = 'index.php?page=product';</script>";
        exit();
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $product_name = $_POST['product_name'];
        $product_category = $_POST['product_category'];
        $product_description = $_POST['product_description'];
        $product_price = $_POST['product_price'];
        $product_status = $_POST['product_status'];
        $singleproduct = $con->query("SELECT * FROM product WHERE id = '$id'")->fetch_assoc();
        $product_photo = $singleproduct['product_photo'];

        if ($_FILES['product_photo']['name'] != '') {
            if ($product_photo != 'product.jpg') {
                if (file_exists('assets/images/product/' . $product_photo)) {
                    unlink('assets/images/product/' . $product_photo);
                }
            }

            $result = uploadFile($_FILES['product_photo'], 'assets/images/product/');
            $product_photo = $result['file_name'];
        }

        $con->query("UPDATE product SET product_name = '$product_name', product_category = '$product_category', 
        product_description = '$product_description', product_price = '$product_price', product_photo = '$product_photo', 
        product_status = '$product_status' WHERE id = '$id'");

        echo "<script>alert('Success'); document.location.href = 'index.php?page=product';</script>";
        exit();
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $singleproduct = $con->query("SELECT * FROM product WHERE id = '$id'")->fetch_assoc();
        $product_photo = $singleproduct['product_photo'];

        if ($product_photo != 'product.jpg') {
            if (file_exists('assets/images/product/' . $product_photo)) {
                unlink('assets/images/product/' . $product_photo);
            }
        }

        $con->query("DELETE FROM product WHERE id = '$id'");
        echo "<script>alert('Success'); document.location.href = 'index.php?page=product';</script>";
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

        .w-25 {
            margin-right: 10px;
        }

        .category {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Default untuk desktop (≥992px) */
        img {
            height: 170px;
            object-fit: cover;
            width: 100%;
        }

        /* Tablet (≥768px dan <992px) */
        @media (max-width: 991.98px) {
            img {
                height: 140px;
            }
        }

        /* HP (<768px) */
        @media (max-width: 767.98px) {
            img {
                height: 120px;
            }
        }
    </style>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-sm btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
        + Tambah product
    </button>

    <!-- Modal Add product -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row g-1">
                            <div class="col-md-12">
                                <label for="">Name</label>
                                <input type="text" name="product_name" id="" class="form-control mb-2">
                            </div>
                            <div class="col-md-12">
                                <label for="">Description</label>
                                <textarea name="product_description" id="" class="form-control mb-2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">Price</label>
                                <input type="number" name="product_price" id="" class="form-control mb-2">
                            </div>
                            <div class="col-md-6">
                                <label for="">Category</label>
                                <select name="product_category" class="form-control mb-2" id="">
                                    <option value="" disabled selected>Select Category</option>
                                    <?php
                                    $getCategory = $con->query("SELECT * FROM category ORDER BY name ASC");
                                    while ($category = $getCategory->fetch_assoc()) {
                                        ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Photo</label>
                                <input type="file" name="product_photo" id="" class="form-control mb-2">
                            </div>
                            <div class="col-md-3">
                                <label for="">Status product</label>
                                <select name="product_status" class="form-control" id="">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="nonactive">Non Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add" class="btn btn-primary">Save product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Show -->
    <div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Show product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="productName"></h5>
                    <p id="productDescription"></p>
                    <h5 id="productPrice"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row g-1">
                            <div class="col-md-12">
                                <input type="text" hidden name="id" id="id_id">
                                <label for="">Name</label>
                                <input type="text" name="product_name" id="product_name_id" placeholder="product Name"
                                    class="form-control mb-2">
                            </div>
                            <div class="col-md-12">
                                <label for="">Decription</label>
                                <textarea name="product_description" id="product_description_id"
                                    placeholder="product Description" class="form-control mb-2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">Price</label>
                                <input type="number" name="product_price" id="product_price_id"
                                    placeholder="product Price" class="form-control mb-2">
                            </div>
                            <div class="col-md-6">
                                <label for="">Category</label>
                                <select name="product_category" class="form-control mb-2" id="product_category_id">
                                    <option value="" disabled selected>Select Category</option>
                                    <?php
                                    foreach ($getCategory as $category) {
                                        ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="">Photo</label>
                                <input type="file" name="product_photo" id="product_photo_id" class="form-control mb-2">
                            </div>
                            <div class="col-md-3">
                                <label for="">Status product</label>
                                <select name="product_status" class="form-control" id="product_status_id">
                                    <option value="active">Active</option>
                                    <option value="nonactive">Non Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow p-2 mb-2 mt-2">
        <form method="get">
            <div class="row g-1">
                <div class="col-9">
                    <input type="hidden" name="page" value="product">
                    <input type="text" class="form-control form-control-sm mb-0" name="search"
                        placeholder="Search product"
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary btn-sm mb-0 px-3"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-2">
                    <a href="index.php?page=product" class="btn btn-secondary btn-sm">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-2">
        <?php
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $sql = "SELECT product.*, category.name as category_name 
            FROM product 
            INNER JOIN category ON product.product_category = category.id";

        if (!empty($search)) {
            $sql .= " WHERE product.product_name LIKE ? OR category.name LIKE ?";
            $sql .= " ORDER BY product.created_at DESC";

            $stmt = $con->prepare($sql);
            $searchTerm = "%" . $search . "%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $getproduct = $stmt->get_result();
        } else {
            $sql .= " ORDER BY product.created_at DESC";
            $getproduct = $con->query($sql);
        }

        if ($getproduct->num_rows > 0) {
            while ($row = $getproduct->fetch_assoc()) {
            }
        } else {
            echo '<div class="col-12"><div class="alert alert-warning text-center">Produk tidak ditemukan.</div></div>';
        }
        ?>

        <?php foreach ($getproduct as $product) { ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card shadow p-2 product-card mt-3">
                    <span
                        class="category badge bg-primary position-absolute top-0 start-50 translate-middle w-45 text-center"
                        style="font-size: 10px;">
                        <?= $product['category_name'] ?>
                    </span>

                    <img class="mt-1 mb-2 px-2" src="assets/images/product/<?php echo $product['product_photo']; ?>"
                        alt="<?php echo $product['product_name']; ?>" class="img-fluid">

                    <?php
                    if ($product['product_status'] == 'active') {
                        $status = 'Active';
                    } else {
                        $status = 'Non active';
                    }
                    ?>
                    <h6 class="m-0 p-0 px-2">
                        <b><?php echo htmlspecialchars(ucwords(strtolower($product['product_name']))); ?>
                            (<?= $status ?>)</b>
                    </h6>

                    <div class="row m-0 p-0 px-2">
                        <div class="col-11 text-truncate" style="font-size: 12px; margin: 0; padding: 0;">
                            <?php echo htmlspecialchars($product['product_description']); ?>
                        </div>
                    </div>

                    <h6 class="mt-2 mb-2 px-2"><b>Rp.
                            <?php echo number_format($product['product_price'], 0, ',', '.'); ?></b></h6>

                    <div class="d-flex px-2">
                        <button type="button"
                            onclick='showproductDetails(
                                <?= json_encode($product["product_name"]) ?>,
                                <?= json_encode($product["product_description"]) ?>,
                                <?= json_encode(number_format($product["product_price"], 0, ",", ".")) ?>                              )'
                            data-bs-toggle="modal" data-bs-target="#showModal" class="btn px-2 btn-sm btn-warning w-25">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" onclick="setEditModalData('<?php echo $product['id']; ?>', '<?php echo $product['product_name']; ?>', 
                                '<?php echo $product['product_category']; ?>', '<?php echo $product['product_description']; ?>', 
                                '<?php echo $product['product_price'] ?>', '<?php echo $product['product_status'] ?>')"
                            data-bs-toggle="modal" data-bs-target="#editModal" class="btn px-2 btn-sm btn-success w-25"
                            style="background-color: #000230ff"><i class="bi bi-pencil"></i></button>
                        <a href="index.php?page=product&delete=<?= $product['id'] ?>"
                            onclick="return confirm('Are you sure to delete this product?')" type="button"
                            class="btn px-2 btn-sm btn-danger w-25" style="background-color: #6b0000ff"><i
                                class="bi bi-trash"></i></a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</div>
<script>
    function showproductDetails(name, description, price) {
        document.getElementById('productName').innerText = name;
        document.getElementById('productDescription').innerText = description;
        document.getElementById('productPrice').innerText = 'Rp ' + price;
    }
    function setEditModalData(id, name, category, description, price, status) {
        document.getElementById('id_id').value = id;
        document.getElementById('product_name_id').value = name;
        document.getElementById('product_category_id').value = category;
        document.getElementById('product_description_id').value = description;
        document.getElementById('product_price_id').value = price;

        let finalStatus = (status && status.trim() !== '') ?
            status.toLowerCase().replace(/\s+/g, '') :
            'nonactive';

        document.getElementById('product_status_id').value = finalStatus;

    }
</script>