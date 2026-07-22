<div class="card mb-3">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id">
            <div class="mb-3">
                <label class="form-label" for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control mb-3" id="nama_kategori" name="nama_kategori" placeholder="Nama kategori" required>
            </div>
            <button type="reset" class="btn btn-secondary" id="btn-reset">Reset</button>
            <button type="submit" name="save_kategori" id="btn-save" class="btn btn-primary">Simpan</button>
            <button type="submit" name="update_kategori" id="btn-update" class="btn btn-primary d-none">Update</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Kategori</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="display table table-sm table-striped nowrap" style="width: 100%" id="myTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $get = $con->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
                            while ($row = $get->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['nama_kategori'] ?></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm btn-edit"
                                            data-id="<?= $row['id'] ?>"
                                            data-nama_kategori="<?= $row['nama_kategori'] ?>">
                                            Edit
                                        </a>
                                        <a href="index.php?hal=kategori&delete_kategori=<?= $row['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            Hapus
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
</div>

<?php
if (isset($_POST['save_kategori'])) {
    $nama_kategori = htmlspecialchars($_POST['nama_kategori']);

    $sql = "INSERT INTO kategori (nama_kategori) VALUES (?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $nama_kategori);

    if ($stmt->execute()) {
        echo "<script>alert('Kategori berhasil ditambahkan');document.location.href='index.php?hal=kategori';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan kategori');document.location.href='index.php?hal=kategori';</script>";
    }
}

if (isset($_POST['update_kategori'])) {
    $id = $_POST['id'];
    $nama_kategori = htmlspecialchars($_POST['nama_kategori']);

    $sql = "UPDATE kategori SET nama_kategori = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $nama_kategori, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Kategori berhasil diupdate');document.location.href='index.php?hal=kategori';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate kategori');document.location.href='index.php?hal=kategori';</script>";
    }
}

if (isset($_GET['delete_kategori'])) {
    $id = $_GET['delete_kategori'];
    $sql = "DELETE FROM kategori WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Kategori berhasil dihapus');document.location.href='index.php?hal=kategori';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kategori');document.location.href='index.php?hal=kategori';</script>";
    }
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            ordering: false,
            scrollX: true,
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            var nama_kategori = $(this).data('nama_kategori');

            $('#id').val(id);
            $('#nama_kategori').val(nama_kategori);

            $('#btn-save').addClass('d-none');
            $('#btn-update').removeClass('d-none');
        });

        $('#btn-reset').on('click', function() {
            $('#id').val('');
            $('#nama_kategori').val('');
            $('#btn-save').removeClass('d-none');
            $('#btn-update').addClass('d-none');
        });
    });
</script>
