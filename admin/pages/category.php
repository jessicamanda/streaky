<?php
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $photo = 'menu.jpg';

    if ($_FILES['photo']['name'] != '') {
        $result = uploadFile($_FILES['photo'], 'assets/images/category/');
        $photo = $result['file_name'];
    }
    $sql = "INSERT INTO category (name, photo) VALUES ('$name', '$photo')";
    $query = $con->query($sql);
    if ($query) {
        echo "<script>alert('Success');document.location.href='index.php?page=category'</script>";
    } else {
        echo "<script>alert('Error');document.location.href='index.php?page=category'</script>";
    }
    exit();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $con->query("SELECT * FROM category WHERE id = '$id'")->fetch_assoc();
    $photo = $category['photo'];

    if ($_FILES['photo']['name'] != '') {
        if ($photo != 'menu.jpg') {
            if (file_exists('assets/images/category/' . $photo)) {
                unlink('assets/images/category/' . $photo);
            }
        }

        $result = uploadFile($_FILES['photo'], 'assets/images/category/');
        $photo = $result['file_name'];
    }
    $sql = "UPDATE category SET name='$name', photo='$photo' WHERE id='$id'";
    $query = $con->query($sql);
    if ($query) {
        echo "<script>alert('Success');document.location.href='index.php?page=category'</script>";
    } else {
        echo "<script>alert('Error');document.location.href='index.php?page=category'</script>";
    }
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $con->query("DELETE FROM category WHERE id='$id'");
    echo "<script>alert('Success');document.location.href='index.php?page=category'</script>";
    exit();
}
?>
<!-- Modal Add Category -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="staticBackdropLabel">Add Category</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Photo</label>
                        <input type="file" name="photo" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Category -->
<div class="modal fade" id="updateData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="staticBackdropLabel">Edit Category</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="text" name="id" id="edit_id" class="form-control" hidden>
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Photo</label>
                        <input type="file" name="photo" id="edit_photo" class="form-control mb-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-sm btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-sm btn-primary mb-4" data-bs-toggle="modal"
            data-bs-target="#staticBackdrop">+ Add Category
        </button>

        <div class="card shadow p-2 rounded">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = "SELECT * FROM category ORDER BY name ASC";
                        $query = $con->query($sql);
                        while ($row = $query->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><img class="mt-1 mb-2 px-2" src="assets/images/category/<?php echo $row['photo']; ?>" alt="<?php echo $row['photo']; ?>" class="img-fluid">
                            </td>
                                <td><b><?php echo htmlspecialchars($row['name']) ?></b></td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#updateData"
                                        onclick="upDataForUpdate('<?= $row['id'] ?>', '<?= htmlspecialchars($row['name']) ?>', '<?= $row['photo'] ?>')"
                                        class='btn btn-sm btn-warning' style="cursor: pointer;">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href='index.php?page=category&delete=<?= $row['id'] ?>' class='btn btn-sm btn-danger'
                                        onclick="return confirm('Are you sure to delete this category?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table td form {
        margin: 0;
    }

    .table td {
        vertical-align: middle;
    }

    img{
        width: 50px;
        height: 50px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    function upDataForUpdate(id, name, photo) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_photo').value = photo;
    }
    $(document).ready(function () {
        $('#dataTable').DataTable({
        });
    });
</script>