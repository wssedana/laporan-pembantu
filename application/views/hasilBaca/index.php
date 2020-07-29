<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="mb-20"><?= $this->session->flashdata('message') ?></div>

    <div class="col-lg mb-4" style="text-align: center">
        <h5 class="form-text"><?php if ($this->session->userdata('wilayah') != "KANTOR PUSAT") { ?>
                KECAMATAN <?= $this->session->userdata('wilayah'); ?>
            <?php } else { ?>
                SEMUA WILAYAH
            <?php }; ?>
        </h5>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table id="tablePeriode" class="table table-hover table-bordered">
                <thead>
                    <tr style="text-align: center;">
                        <th scope="col">#</th>
                        <th scope="col">PERIODE</th>
                        <th scope="col">TANGGAL</th>
                        <th scope="col"><i class="far fa-fw fa-calendar-times text-danger"></i></th>
                        <th scope="col"><i class="far fa-fw fa-calendar-check text-success"></i></th>
                        <th scope="col">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($listPeriode as $lsPeriode) :

                        $periode = $lsPeriode['table_name'];
                        $wilayah = $this->session->userdata('wilayah');
                        $idjabatan = $this->session->userdata('idjabatan');
                        $nik = $this->session->userdata('nik');
                        $queryCount = "SELECT COUNT(IF(a.`verifikasi`='1',1,NULL)) 'sudah',
                                                COUNT(IF(a.`verifikasi`='0',1,NULL)) 'belum',
                                                COUNT(id_periode) AS jmlData
                                        FROM $periode a
                                        LEFT JOIN m_manometer b ON b.`id_manometer`=a.`id_manometer`
                                        LEFT JOIN m_kecamatan c ON c.`id_kecamatan`=b.`id_kecamatan`";

                        if ($wilayah != "KANTOR PUSAT") {
                            $queryCount = $queryCount . " WHERE c.kecamatan='$wilayah'";
                        };
                        if ($idjabatan == 'STF') {
                            $queryCount = $queryCount . " AND a.nik ='$nik' ";
                        }

                        $jumlahData = $this->db->query($queryCount)->result_array();
                    ?>

                        <tr data-id="<?= $periode; ?>" style="cursor:pointer;">
                            <th scope="row" style="text-align: center"><?= $i++; ?></th>
                            <td><?= format_periode($lsPeriode['table_name']); ?></td>
                            <td style="text-align: center"><?= $this->libfunction->format_tanggal($lsPeriode['create_time']); ?></td>
                            <?php foreach ($jumlahData as $jml) : ?>
                                <td class="text text-danger" style="text-align: center">
                                    <?= $jml['belum']; ?>
                                </td>
                                <td class="text text-success" style="text-align: center">
                                    <?= $jml['sudah']; ?>
                                </td>
                                <td style="text-align: center">
                                    <?= $jml['jmlData']; ?>
                                    Hasil baca
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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