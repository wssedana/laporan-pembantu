<!-- Begin Page Content -->
<style>
    .hide {
        display: none;
    }
</style>
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-4">
            <?php if ($this->session->userdata('role_id') != 4) { ?>

                <button class="btn btn-primary mb-4" data-toggle="tooltip" title="Tambah data" id="addZona"><i class="fas fa-fw fa-plus"></i></button>

            <?php }; ?>

            <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2) { ?>
                <a data-toggle="modal" data-target="#FilterZonaModal">
                    <button class="btn btn-info mb-4" data-toggle="tooltip" title="Filter data"><i class="fas fa-fw fa-filter"></i></button>
                </a>
            <?php  } ?>

            <a href="<?= base_url('/masterData/refresh/'); ?>">
                <button class="btn btn-info mb-4" data-toggle="tooltip" title="Refresh data"><i class="fas fa-fw fa-sync"></i></button>
            </a>
        </div>
        <div class="col-lg-3">
            <div class="mb-20"><?= $this->session->flashdata('message') ?></div>
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
            <form action="<?= base_url('/masterData/zona/') ?>" method="POST">
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

    <div class="row hide" id="formAddZona">
        <div class="col-lg-8">
            <h6 class="m-0 font-weight-bold text-primary">Add Zona</h6>
            <hr />
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i>WILAYAH</i></span>
                </div>
                <select class="custom-select" name="wilayah" id="wilayah" title="Pilih Wilayah">
                    <?php foreach ($wilayah as $row) : ?>
                        <option value="<?= $row['id_kecamatan']; ?>"><?= $row['kecamatan']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br />
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i>Zona</i></span>
                </div>
                <input type="text" name="namaZona" id="namaZona" class="form-control">
            </div>
            <br />
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <li id="labelFlag" class="fas fa-fw fa-check" style="color: green;"></li>
                    </span>
                </div>
                <select name="flagActive" id="flagActive" class="custom-select">
                    <option value="1">Active</option>
                    <option value="0">Tidak Active</option>
                </select>
            </div>
            <br />
            <button class="btn btn-success" id="simpan">Simpan</button>
            <button class="btn btn-warning" id="batal">Batal</button>
            <hr />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12" style="overflow-y:auto;">
            <small>Total Data: <?= $total_rows ?> </small>
            <table id="tableZona" class="table table-hover table-bordered">
                <thead>
                    <tr style="text-align: center">
                        <th>#</th>
                        <th>Nama Zona</th>
                        <th>
                            <li class="far fa-fw fa-flag"></li>
                        </th>
                        <th>Kecamatan</th>
                        <th>Manometer</th>
                        <th>DMA</th>
                        <th>Pelanggan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = $this->uri->segment(3);
                    foreach ($zona as $row) : ?>
                        <tr data-id="<?= $row['id_zona']; ?>" style="cursor: pointer;">
                            <td style="text-align: center"><?= ++$i; ?></td>
                            <td><?= $row['zona']; ?></td>
                            <?php
                            if ($row['flag_active'] == 1) :
                                $status = "<i class='fas fa-fw fa-check' style='color :green;'></i>";
                            else :
                                $status = "<i class='fas fa-fw fa-exclamation' style ='color : red;'></i>";
                            endif;
                            ?>
                            <td style="text-align: center"><?= $status; ?></td>
                            <td><?= $row['kecamatan']; ?></td>
                            <?php
                            $idZona = $row['id_zona'];
                            $query = "SELECT * FROM m_manometer
                            WHERE id_zona = $idZona ";

                            $hasil = $this->db->query($query)->result_array();
                            ?>
                            <td><?= count($hasil); ?> <i><small> Manometer</small></i></td>
                            <?php
                            $idZona = $row['id_zona'];
                            $query = "SELECT * FROM m_dma
                            WHERE id_zona = $idZona ";

                            //$db2 = $this->load->database('datacenter', TRUE);
                            $hasil = $this->db->query($query)->result_array();
                            ?>
                            <td><?= count($hasil); ?><i><small> DMA</small></i></td>
                            <?php
                            $idZona = $row['id_zona'];
                            $periode = $this->session->userdata('periode');


                            $query = "SELECT * FROM pelanggan202006 WHERE id_zona='$idZona' ";

                            $db2 = $this->load->database('datacenter', TRUE);
                            $hasil = $db2->query($query)->result_array();
                            ?>
                            <td><?= $row['jml_pelanggan'] ?><i><small> Pelanggan</small></i></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $this->pagination->create_links(); ?>
        </div>
    </div>
</div> <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<div class="modal fade" id="FilterZonaModal" tabindex="-1" aria-labelledby="myLargeModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="text-align: center;">FILTER ZONA</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("masterData/zona") ?>" method="POST">
                    <select name="zonaWilayah" class="custom-select">
                        <?php foreach ($wilayah as $row) { ?>
                            <option value="<?= $row['kecamatan']; ?>"><?= $row['kecamatan']; ?></option>
                        <?php  } ?>
                    </select>
                    <hr />
                    <input type="submit" id="filterZona" name="filterZona" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalZona" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align: center;"><b>Data Zona</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="contentModalZona">

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#tableZona tbody tr').click(function() {
            var id_zona = $(this).attr('data-id');
            $.ajax({
                url: '<?= base_url('masterData/getDetailZona') ?>',
                method: 'post',
                data: {
                    id_zona: id_zona
                },
                success: function(data) {
                    $('#contentModalZona').html(data);
                    $('#modalZona').modal('show');
                }
            });
        });

        $('#per_page').change(function() {
            var per_page = $(this).val();
            $.ajax({
                url: '<?= base_url('masterData/zona') ?>',
                method: 'post',
                data: {
                    per_page: per_page
                },
                success: function() {
                    window.location.replace('<?= base_url('masterData/zona') ?>');
                }
            });
        });

        $('#addZona').click(function() {
            $('#formAddZona').removeClass('hide');
        });

        $('#batal').click(function() {
            $('#formAddZona').addClass('hide');
        });

        $('#simpan').click(function() {
            var wilayah = $('#wilayah').val();
            var zona = $('#namaZona').val();
            var flagActive = $('#flagActive').val();
            var status;
            if (zona == "") {
                alert("Masih Ada Field Kosong");
            } else {
                $.ajax({
                    url: '<?= base_url('masterData/addZona') ?>',
                    method: 'post',
                    data: {
                        wilayah: wilayah,
                        zona: zona,
                        flagActive: flagActive
                    },
                    success: function(data) {
                        if (data == "success") {
                            alert("Success Tambah Zona");
                            window.location.replace("<?= base_url('masterData/zona') ?>");
                        } else {
                            alert("Gagal Tambah Zona");
                            window.location.replace("<?= base_url('masterData/zona') ?>");
                        }
                    }
                });
            }

        });

        $('#flagActive').change(function() {
            var p = $('#flagActive option:selected').val();

            if (p == 0) {
                $('#labelFlag').removeClass('fas fa-fw fa-check');
                $('#labelFlag').addClass('fas fa-fw fa-exclamation');
            } else {
                $('#labelFlag').removeClass('fas fa-fw fa-exclamation');
                $('#labelFlag').addClass('fas fa-fw fa-check');
            }
        });
    });
</script>