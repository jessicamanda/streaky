<?php

$_SESSION['keranjang'] = $_SESSION['keranjang'] ?? [];

if (isset($_POST['add_keranjang'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $supplier = $_POST['supplier'];

    $stmt = $con->prepare("SELECT nama FROM produk WHERE id = ?");
    $stmt->bind_param("s", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();

    if ($produk) {
        $_SESSION['keranjang'][] = [
            'id' => uniqid(),
            'id_produk' => $id_produk,
            'supplier' => $supplier,
            'nama_produk' => $produk['nama'],
            'jumlah' => $jumlah,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual
        ];
    } else {
    }
}

if (isset($_GET['delete_keranjang'])) {
    $id_to_delete = $_GET['delete_keranjang'];
    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id'] === $id_to_delete) {
            unset($_SESSION['keranjang'][$key]);
            break;
        }
    }
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
}

if (isset($_POST['save_pembelian'])) {
    if (empty($_SESSION['keranjang'])) {
    } else {
        $tanggal = $_POST['tanggal'];
        $nota = 'PEM-' . date('Ymd-His');

        $con->begin_transaction();
        try {
            $stmt = $con->prepare("INSERT INTO pembelian (tanggal, nota, id_produk, jumlah, harga_beli, harga_jual, supplier) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($_SESSION['keranjang'] as $item) {
                $supplier_item = $item['supplier'];
                $stmt->bind_param("ssiiiis", $tanggal, $nota, $item['id_produk'], $item['jumlah'], $item['harga_beli'], $item['harga_jual'], $supplier_item);
                $stmt->execute();
            }

            $con->commit();

            unset($_SESSION['keranjang']);
            echo "<script> alert('Pembelian berhasil disimpan');
            window.location.href = 'index.php?hal=pembelian'; </script>";
            exit();
        } catch (mysqli_sql_exception $exception) {
            $con->rollback();
        }
    }
}
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#id_produk').select2();
    });
</script>

<a href="index.php?hal=pembelian" class="btn btn-secondary">Kembali</a>

<div class="card card-tambah">
    <div class="card-body">
        <h5 class="card-title">Tambah Supply Produk</h5>
        <form method="post">
            <label for="">Nama Produk</label>
            <select name="id_produk" class="form-control mb-3" id="id_produk" required>
                <option value="" disabled selected>Pilih Nama Produk</option>
                <?php
                $produk_list_query = $con->query("SELECT * FROM produk ORDER BY nama ASC");
                while ($item_produk = $produk_list_query->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($item_produk['id']) . '">' . htmlspecialchars($item_produk['nama']) . '</option>';
                }
                ?>
            </select>
            <div class="row mb-3">
                <div class="col-12 col-md-12">
                    <label for="">Supplier</label>
                    <input type="text" class="form-control" id="supplier" name="supplier" placeholder="Supplier" required>
                </div>
                <div class="col-12 col-md-4">
                    <label for="">Jumlah</label>
                    <input type="number" step="0.01" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah Beli" required>
                </div>
                <div class="col-12 col-md-4">
                    <label for="">Harga Beli</label>
                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" placeholder="Harga Beli" required>
                </div>
                <div class="col-12 col-md-4">
                    <label for="">Harga Jual</label>
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="Harga Jual" required>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="reset" class="btn btn-secondary me-2 w-50">Reset</button>
                <button type="submit" name="add_keranjang" class="btn btn-primary w-50">Tambah Ke Keranjang</button>
            </div>
        </form>
        <h6 class="mt-4">Keranjang</h6>
        <div class="table-responsive">
            <table class="table mt-2 mb-5">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Supplier</th>
                        <th class="text-center">Nama Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Harga Beli</th>
                        <th class="text-center">Harga Jual</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if (empty($_SESSION['keranjang'])): ?>
                        <tr>
                            <td colspan="6" class="text-center">Keranjang masih kosong.</td>
                        </tr>
                        <?php else:
                        foreach ($_SESSION['keranjang'] as $row): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row["supplier"]); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row["nama_produk"]); ?></td>
                                <td class="text-center"><?php echo number_format($row["jumlah"], 0, ',', '.'); ?></td>
                                <td class="text-center">Rp. <?php echo number_format($row["harga_beli"], 2, ',', '.'); ?></td>
                                <td class="text-center">Rp. <?php echo number_format($row["harga_jual"], 2, ',', '.'); ?></td>
                                <td class="text-center">Rp. <?php echo number_format($row["jumlah"] * $row["harga_beli"], 2, ',', '.'); ?></td>
                                <td class="text-center">
                                    <a href="index.php?hal=tambah-pembelian&delete_keranjang=<?= $row['id'] ?>"
                                        class="btn btn-danger btn-sm">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                    <?php
                    $total_jumlah = 0;
                    foreach ($_SESSION['keranjang'] as $item) {
                        $total_jumlah += $item['jumlah'];
                    }

                    $total_keseluruhan = 0;
                    foreach ($_SESSION['keranjang'] as $item) {
                        $total_keseluruhan += $item['jumlah'] * $item['harga_beli'];
                    }

                    ?>
                    <tr>
                        <th class="text-end" colspan="3">Total Jumlah: </td>
                        <td class="text-center"><?= number_format($total_jumlah, 0, ',', '.'); ?></td>
                        <th class="text-center">Total Keseluruhan: </td>
                        <td class="text-center">Rp. <?= number_format($total_keseluruhan, 2, ',', '.'); ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
        <form method="post">
            <label for="" class="form-label mt-3">Tanggal</label>
            <input type="date" class="form-control mb-3" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?= date('Y-m-d') ?>">
            <div class="d-flex justify-content-center">
                <button type="submit" name="save_pembelian" class="btn btn-primary w-100">Simpan</button>
            </div>
        </form>
    </div>
</div>