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

    td {
        white-space: normal !important;
        word-wrap: break-word;
    }
</style>


<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambhaModalLabel">Tambah Artikel</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="">Judul Artikel</label>
                        <input type="text" class="form-control mb-3" id="judul" name="judul" placeholder="Judul Artikel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Kategori Artikel</label>
                        <input type="text" class="form-control mb-3" id="kategori" name="kategori" placeholder="Kategori Artikel" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Isi Artikel</label>
                        <textarea class="form-control" name="isi" rows="10" id="isi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Foto Artikel</label>
                        <input class="form-control" type="file" name="foto" id="foto">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="save_artikel" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Artikel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="text" id="edit-id" name="id" class="form-control" hidden>
                    <div class="mb-3">
                        <label class="form-label">Judul Artikel</label>
                        <input type="text" class="form-control mb-3" id="edit-judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Artikel</label>
                        <input type="text" class="form-control mb-3" id="edit-kategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Isi Artikel</label>
                        <textarea name="isi" class="form-control" rows="10" id="edit-isi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Foto Artikel</label>
                        <input type="file" class="form-control" name="foto" id="foto">
                        <div id="preview-foto-lama"></div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="update_artikel" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<button class="btn btn-sm btn-primary" id="tambah" name="tambah" data-bs-toggle="modal" data-bs-target="#tambahModal">
    + Tambah Artikel
</button>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Artikel</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="display table table-sm table-striped nowrap" style="width: 100%" id="myTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Isi</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $get = $con->query("SELECT * FROM artikel ORDER BY created_at ASC");
                            while ($row = $get->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['judul'] ?></td>
                                    <td><?= $row['kategori'] ?></td>
                                    <td><?= $row['isi'] ?></td>
                                    <td><img src="<?= htmlspecialchars($row['foto']) ?>" alt="" width="60"></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="<?= $row['id'] ?>"
                                            data-judul="<?= $row['judul'] ?>"
                                            data-kategori="<?= $row['kategori'] ?>"
                                            data-isi="<?= $row['isi'] ?>"
                                            data-foto="<?= $row['foto'] ?>">
                                            Edit
                                        </a>
                                        <a href="index.php?hal=artikel&delete_artikel=<?= $row['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus artikel ini?')">
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
if (isset($_POST['save_artikel'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $isi = htmlspecialchars($_POST['isi']);
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "assets/img/artikel/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid('artikel_') . '.' . $ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto = $target_file;
        }
    }
    $sql = "INSERT INTO artikel (judul, kategori, isi, foto) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $judul, $kategori, $isi, $foto);

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil ditambahkan');document.location.href='index.php?hal=artikel';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan artikel');document.location.href='index.php?hal=artikel';</script>";
    }
}

if (isset($_POST['update_artikel'])) {
    $id = $_POST['id'];
    $judul = htmlspecialchars($_POST['judul']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $isi = htmlspecialchars($_POST['isi']);
    $foto_lama = $_POST['foto_lama'];
    $foto = $foto_lama;

    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "assets/img/artikel/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid('artikel_') . '.' . $ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            if ($foto_lama && file_exists($foto_lama)) unlink($foto_lama);
            $foto = $target_file;
        }
    }

    $sql = "UPDATE artikel SET judul = ?, kategori = ?, isi = ?, foto = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $judul, $kategori, $isi, $foto, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil diupdate');document.location.href='index.php?hal=artikel';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate artikel');document.location.href='index.php?hal=artikel';</script>";
    }
}
if (isset($_GET['delete_artikel'])) {
    $id = $_GET['delete_artikel'];
    $sql = "DELETE FROM artikel WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil dihapus');document.location.href='index.php?hal=artikel';</script>";
    } else {
        echo "<script>alert('Gagal menghapus artikel');document.location.href='index.php?hal=artikel';</script>";
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
            var button = $(this);
            $('#edit-id').val(button.data('id'));
            $('#edit-judul').val(button.data('judul'));
            $('#edit-kategori').val(button.data('kategori'));
            $('#edit-isi').val(button.data('isi'));
        });
    });
</script>