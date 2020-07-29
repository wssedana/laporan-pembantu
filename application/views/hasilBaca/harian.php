<!-- Begin Page Content -->
<div class="container-fluid">
    <?php
    $no = $this->uri->segment(4);
    $periode = $this->uri->segment(3);
    ?>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-4">
            <?php if ($this->session->userdata('role_id') != 4) { ?>
                <button class="btn btn-success mb-4" data-toggle="tooltip" title="Verifikasi Data" id="btn_check_all"><i class="fas fa-fw fa-check"></i></button>
            <?php }; ?>
            <a data-toggle="modal" data-target="#FilterDataModal">
                <button class="btn btn-info mb-4" data-toggle="tooltip" title="Filter data"><i class="fas fa-fw fa-filter"></i></button>
            </a>
            <a href="<?= base_url('/hasilbaca/refresh/') . $periode . '/' . $no; ?>">
                <button class="btn btn-info mb-4" data-toggle="tooltip" title="Refresh data"><i class="fas fa-fw fa-sync"></i></button>
            </a>
            <div class="btn-group align-top" role="group" data-toggle="tooltip" title="Export data">
                <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-file-export"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="<?= base_url('HasilBaca/periode/') . $periode; ?>?do=cetak" target="_blank">Export PDF</a>
                    <a class="dropdown-item" href="<?= base_url('Packinglist/cetakInvXls'); ?>">Export Excel</a>
                </div>
            </div>
            <div class="btn-group align-top" role="group" data-toggle="tooltip" title="Cetak Laporan">
                <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-print"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="<?= base_url('HasilBaca/periode/') . $periode; ?>?do=cetak" target="_blank">Laporan PDF</a>
                    <a class="dropdown-item" href="<?= base_url('Packinglist/cetakInvXls'); ?>">Laporan Excel</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-fw fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="daterange" id="daterange" style="cursor: pointer">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-fw fa-table"></i></span>
                </div>
                <select class="custom-select" id="per_page" name="per_page" aria-label="Example select with button addon">
                    <option value="10" <?php if ($this->session->userdata('perPage') == "10") { ?> selected <?php } ?>>10</option>
                    <option value="25" <?php if ($this->session->userdata('perPage') == "25") { ?> selected <?php } ?>>25</option>
                    <option value="50" <?php if ($this->session->userdata('perPage') == "50") { ?> selected <?php } ?>>50</option>
                    <option value="100" <?php if ($this->session->userdata('perPage') == "100") { ?> selected <?php } ?>>100</option>
                    <option value="all" <?php if ($this->session->userdata('alldata') == "yes") { ?> selected <?php } ?>>Semua</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3">
            <form action="<?= base_url('/hasilbaca/periode/') . $periode; ?>" method="POST">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="keyword" id="keyword" value="<?= $this->session->userdata('keywordMano') ?>" placeholder="Cari ..." onfocus="this.value=''" autocomplete="off">
                    <div class="input-group-append">
                        <input type="submit" class="btn btn-primary" name="cari" value="Cari">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-lg" style="text-align: center">
            <h5 class="form-text"><?php if ($this->session->userdata('kodeWilayah') != null) { ?>
                    KECAMATAN <?= $this->session->userdata('kodeWilayah'); ?>
                <?php }; ?>
            </h5>
            <h6 class="form-text text-muted">
                <?php if ($this->session->userdata('kodeZona') != null) { ?>
                    Zona <?= $this->session->userdata('kodeZona'); ?>
                <?php } ?>
            </h6>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <small>Total Data: <?= $total_rows ?> </small>
            <table id="tableHasilBaca" class="table table-hover table-bordered">
                <thead>
                    <tr style="text-align: center">
                        <?php if ($this->session->userdata('role_id') != 4) { ?>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input position-static check_all" type="checkbox">
                                </div>
                            </th>
                        <?php }; ?>
                        <th>#</th>
                        <th>KODE</th>
                        <th>NAMA</th>
                        <th>TANGGAL BACA</th>
                        <th>ZONA</th>
                        <th>PRESURE</th>
                        <th>MASA</th>
                        <th>KONDISI</th>
                        <th>STATUS</th>
                        <?php if ($this->session->userdata('role_id') != 4) { ?>
                            <th colspan="2">ACTION</th>
                        <?php } else { ?>
                            <th>ACTION</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($total_rows == 0) { ?>
                        <tr style="cursor:pointer;">
                            <td style="text-align: center;" colspan="13">Pencarian <b><?= $this->session->userdata('keywordMano'); ?></b> tidak ditemukan!, klik untuk refresh halaman</td>
                        </tr>
                        <?php } else {
                        foreach ($bacaan as $bc) : ?>
                            <tr <?php if ($bc['verifikasi'] == 0) { ?> class="alert alert-danger" <?php } else { ?> class="alert alert-active" <?php }; ?> data-id="<?= $bc['id_periode']; ?>" style="cursor:pointer;">
                                <?php if ($this->session->userdata('role_id') != 4) { ?>
                                    <td style="text-align: center;">
                                        <?php if ($bc['verifikasi'] == 0) { ?>
                                            <div class="form-check">
                                                <input class="form-check-input position-static chk_boxes1" type="checkbox" value="<?= $bc['id_periode']; ?>" id="verify" name="verify[]">
                                            </div>
                                        <?php } else { ?>
                                            <i class="fas fa-fw fa-check text-success"></i>
                                        <?php } ?>
                                    </td>
                                <?php }; ?>
                                <td class="text text-center"><?= ++$no ?></td>
                                <td class="text text-center">P-<?= $bc['kode_manometer']; ?></td>
                                <td><?= $bc['manometer']; ?></td>
                                <td><?= $this->libfunction->format_tanggal($bc['tgl_baca']); ?></td>
                                <td><?= $bc['zona']; ?></td>
                                <td class="text text-right"><?= $bc['presure']; ?> <small><i>bar</i></small></td>
                                <td>
                                    <?= $bc['masa'] . " "; ?>
                                    <?php if ($bc['status_masa'] == "mundur") { ?>
                                        <text class="text text-danger"><?= $bc['status_masa']; ?></text>
                                    <?php } elseif ($bc['status_masa'] == "maju") { ?>
                                        <text class="text text-warning"><?= $bc['status_masa']; ?></text>
                                    <?php } else { ?>
                                        <text class="text text-success"><?= $bc['status_masa']; ?></text>
                                    <?php } ?>
                                </td>
                                <td><?= $bc['kondisi_baca']; ?></td>
                                <td><?= $bc['status_baca']; ?></td>
                                <td style="text-align: center;">
                                    <a href="https://maps.google.com/?q=<?= $bc['latitude'] . ',' . $bc['longitude']; ?>" target="_blank"><i class="fas fa-fw fa-map text-success"></i></a>
                                </td>
                                <?php if ($this->session->userdata('role_id') != 4) { ?>
                                    <td style="text-align: center;">
                                        <a onclick="editManometer(<?= $bc['id_periode']; ?>)"><i class="fas fa-fw fa-edit text-info"></i></a>
                                    </td>
                                <?php }; ?>
                            </tr>
                    <?php
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-lg-6">
                    <?= $this->pagination->create_links(); ?>
                </div>
                <div class="col-lg-6">
                    <input type="text" value="<?= $periode; ?>" id="periode" name="periode" />
                    <input type="text" id="tglStart" name="tglStart" />
                    <input type="text" id="tglEnd" name="tglEnd" />
                </div>
            </div>
        </div>
    </div>
    </> <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<!-- Modal Filter Data Manometer -->
<!-- Modal -->
<div class="modal fade" id="FilterDataModal" tabindex="-1" role="dialog" aria-labelledby="FilterDataModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="FilterDataModalLabel">Filter Data Manometer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- action: controller menu -> add menu -->
            <form action="<?= base_url('/hasilbaca/periode/') . $periode ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <select name="manoWilayah" id="manoWilayah" class="form-control">
                            <?php if ($this->session->userdata('wilayah') == "KANTOR PUSAT") { ?>
                                <option value="">Pilih Wilayah</option>
                            <?php } ?>
                            <?php foreach ($wilayah as $w) : ?>
                                <option value="<?= $w['kecamatan']; ?>" <?php if ($w['kecamatan'] == $this->session->userdata('kodeWilayah')) { ?> selected <?php } ?>><?= $w['kecamatan']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="manoZona" id="manoZona" class="form-control">
                            <option value="">Pilih Zona</option>
                            <?php foreach ($zona as $z) : ?>
                                <option value="<?= $z['zona']; ?>" <?php if ($z['zona'] == $this->session->userdata('kodeZona')) { ?> selected <?php } ?>><?= $z['zona']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" name="filter" value="Filter">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="deleteManometerText">

            </div>
            <div class="modal-footer" id="deleteManometerConfirm">

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var start = new Date('<?= $this->session->userdata('tglStart') ?>')
        var end = moment();

        $('input[name="daterange"]').daterangepicker({
            startDate: start,
            endDate: end,
            opens: 'left'
        }, function(start, end) {
            document.getElementById("tglStart").value = start.format('YYYY-MM-DD');
            document.getElementById("tglEnd").value = end.format('YYYY-MM-DD');
        });

        $('.check_all').click(function() {
            $('.chk_boxes1').prop('checked', this.checked);
        });

        $('#btn_check_all').click(function() {
            if (confirm("Verifikasi data yang dipilih?")) {
                var id = [];
                var periode = $('#periode').val();

                $('.chk_boxes1:checked').each(function(i) {
                    id[i] = $(this).val();
                });

                if (id.length === 0) {
                    alert("Pilih minimal satu data");
                } else {
                    $.ajax({
                        url: "<?= base_url('hasilbaca/verifyAll'); ?>",
                        method: 'POST',
                        data: {
                            id: id,
                            periode: periode
                        },
                        success: function() {
                            window.location.replace("<?= base_url('/hasilbaca/periode/') . $periode . "/" . $this->uri->segment(4); ?>");
                        }
                    });
                }
            } else {
                return false;
            }
        });

        $('#per_page').change(function() {
            var per_page = $(this).val();
            $.ajax({
                url: "<?= base_url('/hasilbaca/periode/') . $periode; ?>",
                type: 'post',
                data: {
                    per_page: per_page
                },
                success: function() {
                    window.location.replace("<?= base_url('/hasilbaca/periode/') . $periode; ?>");
                }
            })
        });

        $('#daterange').change(function() {
            var tglStart = $("#tglStart").val();
            var tglEnd = $("#tglEnd").val();
            var daterange = "yes";
            console.log(tglStart + " to " + tglEnd);
            $.ajax({
                url: "<?= base_url('/hasilbaca/periode/') . $periode; ?>",
                method: 'POST',
                data: {
                    tglStart: tglStart,
                    tglEnd: tglEnd,
                    daterange: daterange
                },
                success: function() {
                    window.location.replace("<?= base_url('/hasilbaca/periode/') . $periode; ?>");
                }
            });
        });
    });
</script>