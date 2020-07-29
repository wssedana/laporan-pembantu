<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="mb-20"><?= $this->session->flashdata('message') ?></div>
    <div class="row">
        <div class="col-lg-3">
            <?php if ($this->session->userdata('role_id') != 4) { ?>
                <a href="<?= base_url('/masterManometer/tambah') ?>">
                    <button class="btn btn-primary mb-4" data-toggle="tooltip" title="Tambah data" type="submit"><i class="fas fa-fw fa-plus"></i></button>
                </a>
            <?php }; ?>
            <a data-toggle="modal" data-target="#FilterDataModal">
                <button class="btn btn-info mb-4" data-toggle="tooltip" title="Filter data"><i class="fas fa-fw fa-filter"></i></button>
            </a>
            <a href="<?= base_url('/masterManometer/refresh'); ?>">
                <button class="btn btn-info mb-4" data-toggle="tooltip" title="Refresh data"><i class="fas fa-fw fa-sync"></i></button>
            </a>
        </div>
        <div class="col-lg-6"> 

        </div>
        <div class="col-lg-3 pull-right">
            <form action="<?= base_url('/masterManometer'); ?>" method="POST">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="keyword" id="keyword" value="<?= $this->session->userdata('keywordMano') ?>" placeholder="Cari ..." onfocus="this.value=''" autocomplete="off">
                    <div class="input-group-append">
                        <input type="submit" class="btn btn-primary" name="cari" value="Cari">
                    </div>
                </div>
            </form>
        </div>
    </div>
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

    <div class="row">
        <div class="col-lg-12">
            <div class="col-xs-4">
                <small class="form-text text-muted pull-right">Total Data : <?= $total_rows ?></small>
            </div>
            <table id="tableMasterMano" class="table table-hover table-bordered">
                <thead>
                    <tr style="text-align: center">
                        <th>#</th>
                        <th><i class="far fa-fw fa-flag"></i></th>
                        <th>KODE</th>
                        <th>GIS</th>
                        <th>NAMA</th>
                        <th>Ø</th>
                        <th>KECAMATAN</th>
                        <th>ZONA</th>
                        <th>DMA</th>
                        <th>PEMBACA</th>
                        <?php if ($this->session->userdata('role_id') != 4) { ?>
                            <th colspan="3">ACTION</th>
                        <?php } else { ?>
                            <th>ACTION</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $this->uri->segment(3);
                    if ($total_rows == 0) { ?>
                        <tr style="cursor:pointer;">
                            <td style="text-align: center;" colspan="13">Pencarian <b><?= $this->session->userdata('keywordMano'); ?></b> tidak ditemukan!, klik untuk refresh halaman</td>
                        </tr>
                        <?php } else {
                        foreach ($manometer as $lsM) : ?>
                            <tr data-id="<?= $lsM['id_manometer']; ?>" style="cursor:pointer;">
                                <td style="text-align: center;"><?= ++$no ?></td>
                                <td style="text-align: center;">
                                    <?php if ($lsM['flag_active'] == 1) { ?>
                                        <i class="fas fa-fw fa-check text-success" data-toggle="tooltip" title="Status Aktif"></i>
                                    <?php } else { ?>
                                        <i class="fas fa-fw fa-ban text-danger" data-toggle="tooltip" title="Status Tidak Aktif"></i>
                                    <?php } ?>
                                </td>
                                <td style="text-align: center;"><?= $lsM['kode_manometer']; ?></td>
                                <td><?= $lsM['kode_gis']; ?></td>
                                <td><?= $lsM['manometer']; ?></td>
                                <td style="text-align: center;"><?= $lsM['diameter']; ?> "</td>
                                <td><?= $lsM['kecamatan']; ?></td>
                                <td><?= $lsM['zona']; ?></td>
                                <td><?= $lsM['nama_dma']; ?></td>
                                <td><?= $lsM['operator']; ?></td>
                                <td style="text-align: center;">
                                    <a href="https://maps.google.com/?q=<?= $lsM['latitude'] . ',' . $lsM['longitude']; ?>" target="_blank"><i class="fas fa-fw fa-map text-success"></i></a>
                                </td>
                                <?php if ($this->session->userdata('role_id') != 4) { ?>
                                    <td style="text-align: center;">
                                        <a onclick="editManometer(<?= $lsM['id_manometer']; ?>)"><i class="fas fa-fw fa-edit text-info"></i></a>
                                    </td>
                                    <td style="text-align: center;">
                                        <a data-toggle="modal" data-target="#deleteModal" onclick="deleteManometer(<?= $lsM['id_manometer'] ?>)" data-toggle="tooltip" title="Hapus data">
                                            <i class="fas fa-fw fa-trash-alt text-danger"></i>
                                        </a>
                                    </td>
                                <?php }; ?>
                            </tr>
                    <?php
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>
            <?= $this->pagination->create_links(); ?>
        </div>
    </div>

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
            <form action="<?= base_url('/masterManometer'); ?>" method="POST">
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