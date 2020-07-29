<style>
    /*  custom table */
    .tableFixHead {
        overflow-y: auto;
        height: 545px;
    }

    .tableFixHead thead th {
        position: sticky;
        top: 0;
        background: #c7ebf1;
    }

    tr {
        cursor: pointer;
        transition: all .25s ease-in-out
    }

    .selected {
        color: #806520;
        background-color: #fdf3d8;
        border-color: #fceec9;
    }


    /* end of custom table */
</style>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php
    $no = $this->uri->segment(4);
    $periode = $this->uri->segment(3);
    ?>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-3">
            <form action="<?= base_url('/hasilbaca/periode/') . $periode; ?>" method="POST" autocomplete="off">
                <input type="hidden" name="tglStart" id="tglStart">
                <input type="hidden" name="tglEnd" id="tglEnd">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="far fa-fw fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="daterange" id="daterange" style="cursor: pointer" autocomplete="off">
                    <div class="input-group-append">
                        <input type="submit" class="btn btn-primary" name="cariTanggal" id="cariTanggal" value="Cari">
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <?php if ($this->session->userdata('role_id') != 4) { ?>
                <button class="btn btn-success mb-4" data-toggle="tooltip" title="Verifikasi Data" id="btn_check_all"><i class="fas fa-fw fa-check"></i></button>
                <a data-toggle="modal" data-target="#ModalKonfirmasi">
                    <button class="btn btn-info mb-4" data-toggle="tooltip" title="Konfirmasi data"><i class="fas fa-fw fa-file-signature"></i></button>
                </a>
            <?php }; ?>
            <a data-toggle="modal" data-target="#FilterDataModal">
                <button class="btn btn-info mb-4" data-toggle="tooltip" title="Filter data"><i class="fas fa-fw fa-filter"></i></button>
            </a>
            <a href="<?= base_url('/hasilbaca/refresh/') . $periode . '/' . $no; ?>">
                <button class="btn btn-info mb-4" data-toggle="tooltip" title="Refresh data"><i class="fas fa-fw fa-sync"></i></button>
            </a>

            <div class="btn-group align-top" role="group" data-toggle="tooltip" title="Print data">
                <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-print"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="<?= base_url('HasilBaca/periode/') . $periode; ?>?do=cetak" target="_blank">Cetak Laporan V.1</a>
                    <a class="dropdown-item" href="<?= base_url('HasilBaca/periode/') . $periode; ?>?do=cetak2" target="_blank">Cetak Laporan V.2</a>
                </div>
            </div>

            <div class="btn-group align-top" role="group" data-toggle="tooltip" title="Export data">
                <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-file-export"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="<?= base_url('lapPeriode/cetaXls/') . $periode . '?keyword=' . $keyword; ?>">Export Laporan Excel</a>
                    <a class="dropdown-item" href="<?= base_url('lapPeriode/cetaXls2/') . $periode . '?keyword=' . $keyword; ?>">Export Laporan Excel V.2</a>

                    <a class="dropdown-item" href="<?= base_url('lapPeriode/rekapXls/') . $periode . '?keyword=' . $keyword; ?>">Export Rekapitulasi</a>
                </div>
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
    <hr class="mt-0" />
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
    <div class="row mb-3">
        <div class="col-lg-3 mt-4">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5 class="mb-0" id="hbManometer">Manometer</h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <img id="fotobaca" src="http://125.162.138.4:8012/project/manoWS/fotomano/none.jpg" class="img-thumbnail mb-1" alt="">
                            <div class=" table-responsive">
                                <table class="table table-hover table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Presure</td>
                                            <td>
                                                <span id="hbPresure">Tekanan dalam bar</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kondisi Baca</td>
                                            <td>
                                                <span id="hbKondisi">-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Baca</td>
                                            <td>
                                                <span id="hbStatus">-</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <span id="hbMap">-</span><span class="ml-2" id="hbDetailMano">-</span>
                                                <!-- <span class="ml-2" id="hbOption">-</span> -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h5 class="mb-0"> Lainnya</h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <table class="table table-hover table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text text-center">Keterangan</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span id="hbKeterangan">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Id Manometer</td>
                                        <td>
                                            <span id="hbIdMano">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tgl Baca</td>
                                        <td>
                                            <span id="hbTglBaca">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tgl Upload</td>
                                        <td>
                                            <span id="hbTglUpload">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tgl Verifikasi</td>
                                        <td>
                                            <span id="hbTglVerifikasi">-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Akurasi</td>
                                        <td>
                                            <span id="hbAccuracy">-</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <small>Total Data: <?= $total_rows ?> </small>
            <div class="tableFixHead mb-3">
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
                            <th><i class="far fa-fw fa-check-square"></i></th>
                            <th>#</th>
                            <th>KODE</th>
                            <th>NAMA</th>
                            <th>TANGGAL BACA</th>
                            <th>ZONA</th>
                            <th>PRESURE</th>
                            <th>MASA</th>
                            <th>PEMBACA</th>
                            <th>KETERANGAN</th>
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
                                <tr <?php if ($bc['verifikasi'] == 0) { ?> class="alert alert-danger selectRow" <?php } else { ?> class="alert alert-active selectRow" <?php }; ?> data-id="<?= $bc['id_periode']; ?>" id="<?= $bc['id_periode']; ?>" style="cursor:pointer;">
                                    <?php if ($this->session->userdata('role_id') != 4) { ?>
                                        <td style="text-align: center;">
                                            <div class="form-check">
                                                <input class="form-check-input position-static chk_boxes1" type="checkbox" value="<?= $bc['id_periode']; ?>" id="verify" name="verify[]">
                                            </div>
                                        </td>
                                    <?php }; ?>
                                    <td><i class="fas fa-fw fa-check text-success"></i></td>
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
                                    <td><?= $bc['operator']; ?></td>
                                    <td><?= $bc['keterangan']; ?></td>
                                </tr>
                        <?php
                            endforeach;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <hr />
            <div class="row">
                <div class="col-lg-4">
                    <div class="pull-right">
                        <?= $this->pagination->create_links(); ?>
                    </div>
                </div>
                <div class="col-lg-8">
                    <input type="text" value="<?= $periode; ?>" id="periode" name="periode" style="width: 2px;height:3px; visibility:hidden;">
                    <small class="pt-5">* Bila data yang tampil tidak sesuai, silahkan tekan tombol CTRL + F5 </small>
                </div>
            </div>
            </>
        </div>
        </> <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
</div>
<!-- /.container-fluid -->
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

<div class="modal fade" id="ModalKonfirmasi" tabindex="-1" role="dialog" aria-labelledby="ModalKonfirmasi" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalKonfirmasiLabel">Konfirmasi Hasil Baca</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- action: controller menu -> add menu -->
            <div class="modal-body">
                <label for="txt_konfirmasi">Konfirmasi</label>
                <div class="input-group">
                    <textarea class="form-control" aria-label="With textarea" id="txt_konfirmasi"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" id="btn_konfirmasi" value="Simpan">
            </div>
        </div>
    </div>
</div>
<script>
    //http://1bestcsharp.blogspot.com/2017/03/javascript-change-selected-html-table-row-background-color.html
    function selectedRow() {
        var index,
            table = document.getElementById("tableHasilBaca");
        for (var i = 1; i < table.rows.length; i++) {
            table.rows[i].onclick = function() {
                // remove the background from the previous selected row
                if (typeof index !== "undefined") {
                    table.rows[index].classList.toggle("selected");
                }
                console.log(typeof index);
                // get the selected row index
                index = this.rowIndex;
                // add class selected to the row
                this.classList.toggle("selected");
                console.log(typeof index);
            };
        }
    }
    selectedRow();

    $(document).ready(function() {
        $('#tableHasilBaca tbody tr').on('click', function() {
            var $this = $(this);
            var id_periode = $this.closest('tr').data('id');
            var periode = '<?= $periode; ?>';

            $.ajax({
                url: "<?= base_url('hasilbaca/detailBaca'); ?>",
                method: 'POST',
                data: {
                    id_periode: id_periode,
                    periode: periode
                },
                dataType: 'json',
                success: function(data) {
                    $('#hbManometer').html(data[0].manometer);
                    $('#hbPresure').html('<b class="text-danger">' + data[0].presure + '</b> <small><i>bar</i></small>');
                    $('#hbKondisi').html('<b class="text-default">' + data[0].kondisi_baca + '</b>');
                    $('#hbStatus').html('<b class="text-default">' + data[0].status_baca + '</b>');
                    $('#hbIdMano').html(data[0].id_manometer);
                    $('#hbKeterangan').html(data[0].keterangan);
                    $('#hbTglBaca').html(data[0].tgl_baca);
                    $('#hbTglUpload').html(data[0].tgl_uploud);
                    $('#hbTglVerifikasi').html(data[0].tgl_verifikasi);
                    $('#hbAccuracy').html(data[0].accuracy);
                    $('#hbMap').html('<a class="btn btn-primary" href="https://maps.google.com/?q=' + data[0].latitude + ',' + data[0].longitude + '" target="_blank" data-toggle="tooltip" title="Lihat Peta Lokasi"><i class="fas fa-fw fa-map text-white"></i></a>');
                    $('#hbDetailMano').html('<a class="btn btn-primary" href="<?= base_url('masterManometer/detail/'); ?>' + data[0].id_manometer + '" data-toggle="tooltip" title="Lihat Detail Manometer"> <i class="fas fa-fw fa-info-circle text-white"></i></a>');
                    // $('#hbOption').html('<div class="btn-group align-top" role="group" data-toggle="tooltip" title="Pilih Opsi">' +
                    //     '<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    //     '<i class="fas fa-fw fa-bars"></i>' +
                    //     '</button>' +
                    //     '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">' +
                    //     '<a class="dropdown-item" href="#">Request Koreksi</a>' +
                    //     '<a class="dropdown-item" href="#">Request</a>' +
                    //     '<div class="dropdown-divider"></div>' +
                    //     '<a class="dropdown-item" href="#">Request Pembatalan</a>' +
                    //     '</div>' +
                    //     '</div>');

                    var url = 'http://125.162.138.4:8012/project/manoWS/foto/' + data[0].foto;
                    $.ajax({
                        url: url,
                        type: 'HEAD',
                        error: function() {
                            $('#fotobaca').attr('src', 'http://125.162.138.4:8012/project/manoWS/fotomano/none.jpg');
                        },
                        success: function() {
                            $('#fotobaca').attr('src', 'http://125.162.138.4:8012/project/manoWS/foto/' + data[0].foto);
                        }
                    });
                }
            });

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

        $('#btn_konfirmasi').click(function() {
            if (confirm("Konfirmasi data yang dipilih?")) {
                var id = [];
                var periode = $('#periode').val();
                var txt = $('#txt_konfirmasi').val();

                $('.chk_boxes1:checked').each(function(i) {
                    id[i] = $(this).val();
                });

                if (id.length === 0) {
                    alert("Pilih minimal satu data");
                } else {
                    //alert(id + ", " + periode + ", " + txt)
                    $.ajax({
                        url: "<?= base_url('hasilbaca/konfirmasi'); ?>",
                        method: 'POST',
                        data: {
                            id: id,
                            periode: periode,
                            txt: txt
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

        $(function() {

            $('input[name="daterange"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                $('#tglStart').val(picker.startDate.format('YYYY-MM-DD'));
                $('#tglEnd').val(picker.endDate.format('YYYY-MM-DD'));
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#tglStart').val('');
                $('#tglEnd').val('');
            });

        });

    });
</script>