<?php

$_SESSION['keranjang'] = $_SESSION['keranjang'] ?? [];

if (isset($_POST['add_keranjang'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];

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
            'nama_produk' => $produk['nama'],
            'jumlah' => $jumlah,
            'harga' => $harga,
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

if (isset($_POST['save_pesanan'])) {
    if (empty($_SESSION['keranjang'])) {
    } else {
        $tanggal = $_POST['tanggal'];
        $nota = 'PEM-' . date('Ymd-His');

        $con->begin_transaction();
        try {
            $stmt = $con->prepare("INSERT INTO pesanan (tanggal, nota, id_produk, jumlah, harga) VALUES (?, ?, ?, ?, ?)");
            foreach ($_SESSION['keranjang'] as $item) {
                $supplier_item = $item['supplier'];
                $stmt->bind_param("ssiii", $tanggal, $nota, $item['id_produk'], $item['jumlah'], $item['harga']);
                $stmt->execute();
            }

            $con->commit();

            unset($_SESSION['keranjang']);
            header("Location: index.php?hal=tambah-pesanan");
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

<a href="index.php?hal=pesanan" class="btn btn-secondary">Kembali</a>

<div class="card card-tambah">
    <div class="card-body">
        <h5 class="card-title">Tambah Pesanan</h5>
        <form method="post">
            <label for="">Nama Produk</label>
<select name="id_produk" class="form-control mb-3" id="id_produk" required>
    <option value="" disabled selected>Pilih Nama Produk</option>
    <?php
    $query = "
        SELECT 
            b.id, 
            b.nama, 
            p.harga_jual, 
            p.tanggal, 
            COALESCE((SELECT SUM(p2.jumlah) FROM pesanan p2 WHERE p2.id_produk = b.id), 0) AS stok
        FROM produk b
        LEFT JOIN pesanan p ON p.id_produk = b.id 
        ORDER BY b.nama ASC, p.tanggal ASC
    ";

    $produk_list_query = $con->query($query);

    if (!$produk_list_query) {
        die('Query Error: ' . $con->error);
    }

    while ($item_produk = $produk_list_query->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($item_produk['id']) . '" 
                    data-stok="' . htmlspecialchars($item_produk['stok']) . '" 
                    data-harga="' . htmlspecialchars($item_produk['harga_jual']) . '">'
                . htmlspecialchars($item_produk['nama']) . ' (' . htmlspecialchars($item_produk['tanggal']) . ')' . 
             '</option>';
    }
    ?>
</select>

            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" readonly name="stok" id="stok">
                </div>
                <div class="col-12 col-md-6">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" step="0.01" class="form-control" id="harga" name="harga" placeholder="Harga Produk" required>
                </div>
                <div class="col-12 col-md-6">
                    <label for="">Jumlah Beli</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah Beli" required>
                </div>
                <div class="col-12 col-md-6">
                    <label for="">Total</label>
                    <input type="number" class="form-control" id="total" name="total" placeholder="Total" required>
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
                        <th class="text-center">Nama Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Harga</th>
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
                                <td class="text-center"><?php echo htmlspecialchars($row["nama_produk"]); ?></td>
                                <td class="text-center"><?php echo number_format($row["jumlah"], 0, ',', '.'); ?></td>
                                <td class="text-center">Rp. <?php echo number_format($row["harga"], 2, ',', '.'); ?></td>
                                <td class="text-center">Rp. <?php echo number_format($row["jumlah"] * $row["harga"], 2, ',', '.'); ?></td>
                                <td class="text-center">
                                    <a href="index.php?hal=tambah-pesanan&delete_keranjang=<?= $row['id'] ?>"
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
                        $total_keseluruhan += $item['jumlah'] * $item['harga'];
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
            <label for="" class="form-label">Tanggal</label>
            <input type="date" class="form-control mb-3" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?= date('Y-m-d') ?>">
            <label for="" class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control mb-3" id="nama_pelanggan" name="nama_pelanggan" placeholder="Nama Pelanggan" required>
            <div class="d-flex justify-content-center">
                <button type="submit" name="save_pesanan" class="btn btn-primary w-100">Simpan</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#id_produk').on('change', function() {
            var stok = $(this).find(':selected').data('stok');
            var harga = $(this).find(':selected').data('harga');

            $('#stok').val(stok);
            $('#harga').val(harga);
        });

        function hitungTotal() {
            let harga = parseFloat($('#harga').val()) || 0;
            let jumlah = parseFloat($('#jumlah').val()) || 0;
            let total = harga * jumlah;
            $('#total').val(total.toFixed(2));
        }

        $('#harga, #jumlah').on('input', hitungTotal);
    </script>