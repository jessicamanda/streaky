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


<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahModalLabel">Tambah Produk</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama Produk</label>
                        <input type="text" class="form-control mb-3" id="nama" name="nama" placeholder="Nama Produk" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Kategori</label>
                        <select class="form-select mb-3" id="kategori" name="kategori" required>
                            <option value="" disabled selected>Pilih Nama Kategori</option>
                            <?php
                            $kategori = $con->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
                            while ($item = $kategori->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($item['id']) . '">' . htmlspecialchars($item['nama_kategori']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" id="deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Foto</label>
                        <input class="form-control" type="file" name="foto" id="foto">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="save_produk" class="btn btn-primary">Simpan</button>
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
                <h5 class="modal-title" id="editModalLabel">Edit produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="text" id="edit-id" name="id" class="form-control" hidden>
                    <div class="mb-3">
                        <label class="form-label">Nama produk</label>
                        <input type="text" class="form-control mb-3" id="edit-nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select mb-3" id="edit-kategori" name="kategori" required>
                            <option value="" disabled>Pilih kategori</option>
                            <?php
                            $kategori = $con->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
                            while ($item = $kategori->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($item['id']) . '">'
                                    . htmlspecialchars($item['nama_kategori']) .
                                    '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" id="edit-deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Foto</label>
                        <input class="form-control" type="file" name="foto" id="foto">
                        <div id="preview-foto-lama"></div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="update_produk" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<button class="btn btn-sm btn-primary" id="tambah" name="tambah" data-bs-toggle="modal" data-bs-target="#tambahModal">
    + Tambah Produk
</button>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Produk</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="display table table-sm table-striped nowrap" style="width: 100%" id="myTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama produk</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Foto Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $get = $con->query("SELECT produk.*, kategori.nama_kategori FROM produk JOIN kategori ON produk.id_kategori = kategori.id ORDER BY nama ASC");
                            while ($row = $get->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['nama_kategori'] ?></td>
                                    <td><?= $row['deskripsi'] ?></td>
                                    <td><img src="<?= htmlspecialchars($row['foto']) ?>" alt="" width="60"></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="<?= $row['id'] ?>"
                                            data-nama="<?= $row['nama'] ?>"
                                            data-kategori="<?= $row['id_kategori'] ?>"
                                            data-deskripsi="<?= $row['deskripsi'] ?>"
                                            data-foto="<?= htmlspecialchars($row['foto']) ?>">
                                            Edit
                                        </a>
                                        <a href="index.php?hal=produk&delete_produk=<?= $row['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')">
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
if (isset($_POST['save_produk'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "assets/img/produk/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid('produk_') . '.' . $ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto = $target_file;
        }
    }
    $sql = "INSERT INTO produk (nama, id_kategori, deskripsi, foto) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $nama, $kategori, $deskripsi, $foto);

    if ($stmt->execute()) {
        echo "<script>alert('produk berhasil ditambahkan');document.location.href='index.php?hal=produk';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan produk');document.location.href='index.php?hal=produk';</script>";
    }
}

if (isset($_POST['update_produk'])) {
    $id = $_POST['id'];
    $nama = htmlspecialchars($_POST['nama']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $foto_lama = $_POST['foto_lama'];
    $foto = $foto_lama;

    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "assets/img/produk/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid('produk_') . '.' . $ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            if ($foto_lama && file_exists($foto_lama)) unlink($foto_lama);
            $foto = $target_file;
        }
    }

    $sql = "UPDATE produk SET nama = ?, id_kategori = ?, deskripsi = ?, foto = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $nama, $kategori, $deskripsi, $foto, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil diupdate');document.location.href='index.php?hal=produk';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate produk');document.location.href='index.php?hal=produk';</script>";
    }
}

if (isset($_GET['delete_produk'])) {
    $id = $_GET['delete_produk'];
    $sql = "DELETE FROM produk WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil dihapus');document.location.href='index.php?hal=produk';</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk');document.location.href='index.php?hal=produk';</script>";
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
            $('#edit-nama').val(button.data('nama'));
            $('#edit-deskripsi').val(button.data('deskripsi'));
            $('#edit-kategori').val(button.data('kategori'));
            $('#edit-foto-lama').val(button.data('foto'));

            if (button.data('foto')) {
                $('#preview-foto-lama').html(
                    `<img src="${button.data('foto')}" alt="Foto Lama" width="100" class="img-thumbnail mt-2">`
                );
            } else {
                $('#preview-foto-lama').html('<small class="text-muted">Tidak ada foto lama</small>');
            }
        });
    });
</script>