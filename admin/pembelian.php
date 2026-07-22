<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />

<a href="index.php?hal=tambah-pembelian" class="btn btn-sm btn-primary"> + Tambah Pembelian produk</a>
<div class="row mt-3">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body shadow p-3">
                <div class="table-responsive">
                    <table class="display table table-striped table-bordered nowrap" id="myTable" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center align-middle">No.</th>
                                <th rowspan="2" class="text-center align-middle">Tanggal</th>
                                <th rowspan="2" class="text-center align-middle">No Nota</th>
                                <th colspan="5" class="text-center">Produk</th>
                            </tr>
                            <tr>
                                <th class="text-center">Nama produk</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query_pembelian = $con->query("SELECT DISTINCT nota, created_at FROM pembelian ORDER BY created_at DESC");

                            while ($p = $query_pembelian->fetch_assoc()) {
                                $pembelian_nota = $p['nota'];
                                $stmt = $con->prepare(
                                    "SELECT p.*, b.nama FROM pembelian p LEFT JOIN produk b ON p.id_produk = b.id
                                     WHERE p.nota = ?"
                                );
                                $stmt->bind_param("s", $pembelian_nota);
                                $stmt->execute();
                                $result_produk = $stmt->get_result();

                                $produk_list = [];
                                $supplier_list = [];
                                $jumlah_list = [];
                                $harga_list = [];
                                $total_harga =[];

                                while ($detail = $result_produk->fetch_assoc()) {
                                    $produk_list[] = htmlspecialchars($detail['nama']);
                                    $supplier_list[] = htmlspecialchars($detail['supplier']);
                                    $jumlah_list[] = number_format($detail['jumlah'], 0, ',', '.');
                                    $harga_list[] = 'Rp. ' . number_format($detail['harga_beli'], 2, ',', '.');
                                    $total_harga[] = 'Rp. ' . number_format($detail['jumlah'] * $detail['harga_beli'], 2, ',', '.');
                                }
                                $stmt->close();
                            ?>
                                <tr>
                                    <td class="text-start"><?= $no++; ?></td>
                                    <td class="text-start"><?= date('d/m/Y H:i:s', strtotime($p['created_at'])); ?></td>
                                    <td><?= htmlspecialchars($p['nota']); ?></td>

                                    <td><?= implode('<br>', $produk_list); ?></td>
                                    <td><?= implode('<br>', $supplier_list); ?></td>
                                    <td class="text-center"><?= implode('<br>', $jumlah_list); ?></td>
                                    <td class="text-start"><?= implode('<br>', $harga_list); ?></td>
                                    <td class="text-start"><?= implode('<br>', $total_harga);?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "scrollX": true,
        });
    });
</script>