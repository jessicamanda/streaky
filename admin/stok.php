<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="display table table-sm table-striped nowrap" style="width: 100%" id="myTable">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Nama Produk</th>
                                <th class="text-center">Produk Masuk</th>
                                <!-- <th class="text-center">Produk Keluar</th>
                                <th class="text-center">Stok</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $get = $con->query("SELECT b.*, COALESCE(( SELECT SUM(p.jumlah) FROM pembelian p WHERE p.id_produk = b.id ), 0) AS TotalMasuk FROM produk b;");
                            while ($row = $get->fetch_assoc()) { ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><b><?= $row['nama'] ?></b></td>
                                    <td class="text-center"><?php echo number_format($row["TotalMasuk"], 0, 0, '.') ?? '0'; ?></td>
                                    <!-- <td class="text-center"><?php echo number_format($row["TotalKeluar"], 0) ?? '0'; ?></td>
                                    <td class="text-center">
                                        <?php echo $row["TotalMasuk"] - $row["TotalKeluar"]; ?>
                                    </td> -->
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>