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
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Balas Masukkan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="text" id="edit-id" name="id" class="form-control" hidden>
                    <div class="mb-3">
                        <label class="form-label" for="">Tanggal Kirim</label>
                        <input type="text" class="form-control mb-3" id="edit-tanggal" name="tanggal" placeholder="Kategori masukkan" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Email Pengirim</label>
                        <input type="email" class="form-control mb-3" id="edit-pengirim" name="pengirim" placeholder="Kategori masukkan" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Pertanyaan/Pernyataan</label>
                        <textarea name="isi" id="edit-isi" class="form-control" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Jawaban Penjual</label>
                        <textarea name="jawaban" id="edit-jawab" class="form-control" rows="3"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="update_masukkan" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Kritik dan Saran</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="display table table-sm table-striped nowrap" style="width: 100%" id="myTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Pengirim</th>
                                <th>Isi</th>
                                <th>Jawaban</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $get = $con->query("SELECT * FROM masukkan ORDER BY created_at ASC");
                            while ($row = $get->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['tanggal'] ?></td>
                                    <td><?= $row['pengirim'] ?></td>
                                    <td><?= $row['isi'] ?></td>
                                    <td><?= $row['jawaban'] ?></td>
                                    <?php if ($row['jawaban'] == NULL) { ?>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-sm btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="<?= $row['id'] ?>"
                                                data-tanggal="<?= $row['tanggal'] ?>"
                                                data-pengirim="<?= $row['pengirim'] ?>"
                                                data-isi="<?= $row['isi'] ?>">
                                                Balas
                                            </a>
                                            <a href="index.php?hal=masukkan&delete_masukkan=<?= $row['id'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus masukkan ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    <?php } else { ?>
                                        <td>
                                            <a href="index.php?hal=masukkan&delete_masukkan=<?= $row['id'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus masukkan ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    <?php } ?>
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
if (isset($_POST['update_masukkan'])) {
    $id = $_POST['id'];
    $jawaban = htmlspecialchars($_POST['jawaban']);
    $sql = "UPDATE masukkan SET jawaban = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $jawaban, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Jawaban berhasil dikirim');document.location.href='index.php?hal=kritiksaran';</script>";
    } else {
        echo "<script>alert('Gagal mengirim jawaban');document.location.href='index.php?hal=kritiksaran';</script>";
    }
}
if (isset($_GET['delete_masukkan'])) {
    $id = $_GET['delete_masukkan'];
    $sql = "DELETE FROM masukkan WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Masukkan berhasil dihapus');document.location.href='index.php?hal=kritiksaran';</script>";
    } else {
        echo "<script>alert('Gagal menghapus masukkan');document.location.href='index.php?hal=kritiksaran';</script>";
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
            $('#edit-tanggal').val(button.data('tanggal'));
            $('#edit-pengirim').val(button.data('pengirim'));
            $('#edit-isi').val(button.data('isi'));
        });
    });
</script>