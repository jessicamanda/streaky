<div class="card">
    <div class="card-header pb-0">
        <h6>Daftar Aduan</h6>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="display table nowrap" style="width: 100%;" id="myTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>WhatsApp</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $get = $con->query("SELECT * FROM contact ORDER BY created_at DESC");
                    while ($row = $get->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['whatsapp'] ?></td>
                            <td><?= $row['message'] ?></td>
                            <td>
                                <?php
                                $whatsapp = $row['whatsapp'];
                                $wa_number = preg_replace('/\D/', '', $whatsapp);

                                if (!empty($wa_number)) {
                                    if (substr($wa_number, 0, 2) !== '62') {
                                        if (substr($wa_number, 0, 1) === '0') {
                                            $wa_number = '62' . substr($wa_number, 1);
                                        } else {
                                            $wa_number = '62' . $wa_number;
                                        }
                                    }
                                }
                                $text = "Hi there! Thanks for sharing your thoughts with us. We’d love to follow up on your feedback — when is a good time for us to connect?";
                                $whatsapp_url = !empty($wa_number) ? "https://wa.me/{$wa_number}?text={$text}" : '';
                                ?>
                                <a href="<?= $whatsapp_url ?>" target="_blank" class="btn btn-success btn-sm"><i class="bi bi-whatsapp"></i></a>

                            </td>
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
        $('#myTable').DataTable();
    });
</script>