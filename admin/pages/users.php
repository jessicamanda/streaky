<?php
if (isset($_POST['save'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    if ($stmt) {
        echo "<script>
            alert('Success');
            window.location.href = 'index.php?page=users';
          </script>";
    } else {
        echo "<script>
            alert('Error');
            window.location.href = 'index.php?page=users';
          </script>";
    }
}

if (isset($_POST['update'])) {
    $id = htmlspecialchars($_POST['id']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $id);
    $stmt->execute();
    if ($stmt) {
        echo "<script>
            alert('Success');
            window.location.href = 'index.php?page=users';
          </script>";
    } else {
        echo "<script>
            alert('Error');
            window.location.href = 'index.php?page=users';
          </script>";
    }
}

if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    if ($stmt) {
        echo "<script>
            alert('Success');
            window.location.href = 'index.php?page=users';
          </script>";
    } else {
        echo "<script>
            alert('Error');
            window.location.href = 'index.php?page=users';
          </script>";
    }
}
?>

<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambhaModalLabel">Tambah User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <label class="mb-2">Username</label>
                    <input type="text" class="form-control mb-3" id="username" name="username">
                    <label class="mb-2">Password</label>
                    <input type="text" class="form-control mb-3" id="password" name="password">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="save" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <label class="mb-2">Username</label>
                    <input type="text" class="form-control mb-3" id="edit-username" name="username">
                    <label class="mb-2">Password</label>
                    <input type="text" class="form-control mb-3" id="edit-password" name="password">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<button class="btn btn-sm btn-primary" id="tambah" name="tambah" data-bs-toggle="modal" data-bs-target="#tambahModal">
    + Tambah Akun
</button>
<div class="card mb-4 mt-4">
    <div class="card-header pb-0">
        <h6>Daftar User</h6>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="display table nowrap" style="width: 100%;" id="myTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <?php if ($_SESSION['users']['username'] === 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $get = $con->query("SELECT * FROM users ORDER BY username ASC");
                    while ($user = $get->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $user['username'] ?></td>
                            <?php if ($_SESSION['users']['username'] === 'admin'): ?>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="<?= $user['id'] ?>"
                                        data-username="<?= $user['username'] ?>" data-password="<?= $user['password'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="index.php?page=users&delete_user=<?= $user['id'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure to delete this user?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive tr {
            font-size: 12px;
        }

        .dataTables_info,
        .dataTables_paginate {
            font-size: 12px !important;
        }

        .dataTables_paginate a {
            font-size: 12px !important;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            ordering: false,
            scrollX: true,
        });

        $(document).on('click', '.btn-edit', function () {
            var button = $(this);
            $('#edit-id').val(button.data('id'));
            $('#edit-username').val(button.data('username'));
            $('#edit-password').val(button.data('password'));
        });
    });
</script>