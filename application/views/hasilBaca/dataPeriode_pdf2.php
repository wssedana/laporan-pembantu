<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title; ?></title>
    <style type="text/css">
        .table {
            border-collapse: collapse;
        }

        .table td {
            padding: 6px;
            border: 1;
        }

        .table th {
            padding: 6px;
            border: 1;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <div class="container-fluid">
            <?php
            $no = 1;;
            $periode = $this->uri->segment(3);
            $i = 1;
            $baris = 1;
            $tbSebelumnya = "";
            $zonaSebelumnya = "";
            $kecamatanSebelumnya = "";
            $masaSebelumnya = "";
            $halamanSelesai = 0;
            $pembacaSebelumnya = "";
            $mulai = 0;
            ?>
            <!-- Page Heading -->
            <?php
            if ($total_rows == 0) { ?>
                <div class="col-lg">
                    <h6 class="form-text">PENANGGULANGAN KEHILANGAN AIR<br />
                        PERUMDA AIR MINUM TIRTA SANJIWANI<br />
                        MANAJEMEN TEKANAN</h6>
                    <h5 class="form-text" style="text-align: center">MONITORING TEKANAN MANOMETER</h5>
                    <table width="30%">
                        <tr>
                            <td width="10%">
                                <h6>CABANG</h6>
                            </td>
                            <td width="5%">
                                <h6>:</h6>
                            </td>
                            <td width="15%">
                                <h6><?= $this->session->userdata('kodeWilayah'); ?></h6>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%">
                                <h6>ZONA</h6>
                            </td>
                            <td width="5%">
                                <h6>:</h6>
                            </td>
                            <td width="10%">
                                <h6>-</h6>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%">
                                <h6>PERIODE</h6>
                            </td>
                            <td width="5%">
                                <h6>:</h6>
                            </td>
                            <td width="10%">
                                <h6><?= $periodebaca; ?></h6>
                            </td>
                        </tr>
                    </table>
                </div>
                <br />
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table">
                            <thead>
                                <tr style="text-align: center">
                                    <th rowspan="2">NO</th>
                                    <th rowspan="2">KODE</th>
                                    <th rowspan="2">TAP PIPA</th>
                                    <th rowspan="2">LOKASI MANOMETER</th>
                                    <th colspan="2">JAM PUNCAK</th>
                                    <th colspan="2">JAM JENUH</th>
                                    <th rowspan="2">INDIKATOR PEL</th>
                                    <th rowspan="2">JUMLAH SR</th>
                                    <th rowspan="2">KET</th>
                                </tr>
                                <tr style="text-align: center">
                                    <th>PAGI</th>
                                    <th>SORE</th>
                                    <th>SIANG</th>
                                    <th>MALAM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="cursor:pointer;">
                                    <td style="text-align: center;" colspan="13">Pencarian <b><?= $this->session->userdata('keywordMano'); ?></b> tidak ditemukan!, klik untuk refresh halaman</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } else {
                foreach ($lapbacaan as $bc) :
                ?>
                    <?php if ($bc['tgl_dibaca'] != $tbSebelumnya || $zonaSebelumnya != $bc['zona'] || $masaSebelumnya != $bc['masa']) {
                        $no = 1;
                        if ($mulai > 0) { ?>
                            <?php
                            $CI = &get_instance();
                            $CI->load->model('Hasilbaca_model');
                            ?>
                            <table width="100%">
                                <tr>
                                    <td width="30%" style="text-align: center;">Mengetahui</td>
                                    <td width="30%" style="text-align: center;">Menyetujui</td>
                                    <td width="30%" style="text-align: center;">Dibuat Oleh</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align:center;">Kepala Cabang <?= $kecamatanSebelumnya; ?></td>
                                    <td width="40%" style="text-align:center;">Kasi.Teknik Cabang <?= $kecamatanSebelumnya; ?></td>
                                    <td width="30%" style="text-align: center;">Petugas Monitoring</td>
                                </tr>
                                <?php for ($a = 0; $a < 10; $a++) : ?>
                                    <tr>
                                        <td width="30%" style="text-align:center;"></td>
                                        <td width="30%" style="text-align:center;"></td>
                                        <td width="30%" style="text-align: center;"></td>
                                    </tr>
                                <?php endfor; ?>

                                <tr>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array(); ?>
                                    <td width="33,4%" style="text-align:center;">
                                        <h5><?= $ttd['nama']; ?></h5>
                                    </td>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array(); ?>
                                    <td width="33,4%" style="text-align:center;">
                                        <h5><?= $ttd['nama']; ?></h5>
                                    </td>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array(); ?>
                                    <td width="33,4%" style="text-align: center;">
                                        <h5><?= $ttd['nama']; ?></h5>
                                    </td>
                                </tr>

                                <tr>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array(); ?>
                                    <td width="33,4%" style="text-align:center;">
                                        <p><small><?= $ttd['pangkat']; ?></small></p>
                                    </td>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array(); ?>
                                    <td width="33,4%" style="text-align:center;">
                                        <p><small><?= $ttd['pangkat']; ?></small></p>
                                    </td>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array(); ?>
                                    <td width="33,4%" style="text-align: center;">
                                        <p><small><?= $ttd['pangkat']; ?></small></p>
                                    </td>
                                </tr>

                                <tr>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array(); ?>
                                    <td width="33,4%" style="text-align:center;">
                                        <p><small>NIK :<?= $ttd['nik']; ?></small></p>
                                    </td>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array(); ?>
                                    <td width="33,4%" style="text-align:center;">
                                        <p><small>NIK :<?= $ttd['nik']; ?></small></p>
                                    </td>
                                    <?php $ttd = $CI->Hasilbaca_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array(); ?>
                                    <td width="33,4%" style="text-align:center;">
                                        <p><small>NIK :<?= $ttd['nik']; ?></small></p>
                                    </td>
                                </tr>
                            </table>

                            <div style="page-break-after:always"></div>
                        <?php  } ?>
                        <div class="col-lg">
                            <h6 class="form-text">PENANGGULANGAN KEHILANGAN AIR<br />
                                PERUMDA AIR MINUM TIRTA SANJIWANI<br />
                                MANAJEMEN TEKANAN</h6>
                            <h5 class="form-text" style="text-align: center">MONITORING TEKANAN MANOMETER</h5>
                            <table width="40%">
                                <tr>
                                    <td width="10%">
                                        <h6>CABANG</h6>
                                    </td>
                                    <td width="5%">
                                        <h6>:</h6>
                                    </td>
                                    <td width="15%">
                                        <h6><?= $bc['kecamatan']; ?></h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <h6>ZONA</h6>
                                    </td>
                                    <td width="5%">
                                        <h6>:</h6>
                                    </td>
                                    <td width="10%">
                                        <h6><?= $bc['id_zona'] . " " . $bc['zona']; ?></h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <h6>PEMBACA</h6>
                                    </td>
                                    <td width="5%">
                                        <h6>:</h6>
                                    </td>
                                    <td width="25%">
                                        <h6><?= $bc['operator'] ?></h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <h6>TANGGAL</h6>
                                    </td>
                                    <td width="5%">
                                        <h6>:</h6>
                                    </td>
                                    <td width="10%">
                                        <h6><?= $bc['tgl_dibaca']; ?></h6>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <br />

                        <table class="table" width="100%">
                            <thead>
                                <tr style="text-align: center">
                                    <th rowspan="2" width="7%"><small>NO</small></th>
                                    <th rowspan="2" width="9%"><small>KODE</small></th>
                                    <th rowspan="2" width="8%"><small>TAP PIPA</small></th>
                                    <th rowspan="2" width="18%"><small>LOKASI MANOMETER</small></th>
                                    <th colspan="2" width="17%"><small>JAM PUNCAK</small></th>
                                    <th colspan="2" width="17%"><small>JAM JENUH</small></th>
                                    <th rowspan="2" width="15%"><small>INDIKATOR PEL</small></th>
                                    <th rowspan="2" width="12%"><small>SR</small></th>
                                    <th rowspan="2" width="10%"><small>KET</small></th>
                                </tr>
                                <tr style="text-align: center">
                                    <th width="8%"><small>PAGI</small></th>
                                    <th width="9%"><small>SORE</small></th>
                                    <th width="8%"><small>SIANG</small></th>
                                    <th width="9%"><small>MALAM</small></th>
                                </tr>
                            </thead>
                        </table>
                    <?php } ?>
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <td width="7%" style="padding: 0px;  border: 0;"></td>
                                <td width="9%" style="padding: 0px;  border: 0;"></td>
                                <td width="8%" style="padding: 0px;  border: 0;"></td>
                                <td width="18%" style="padding: 0px;  border: 0;"></td>
                                <td width="8%" style="padding: 0px;  border: 0;"></td>
                                <td width="9%" style="padding: 0px;  border: 0;"></td>
                                <td width="8%" style="padding: 0px;  border: 0;"></td>
                                <td width="9%" style="padding: 0px;  border: 0;"></td>
                                <td width="15%" style="padding: 0px;  border: 0;"></td>
                                <td width="12%" style="padding: 0px;  border: 0;"></td>
                                <td width="10%" style="padding: 0px;  border: 0;"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="7%"><?= $no ?></td>
                                <td width="9%">P-<?= $bc['kode_manometer']; ?></td>
                                <td width="8%"><?= $bc['diameter'] . '"'; ?></td>
                                <td width="18%"><?= $bc['manometer']; ?></td>

                                <td width="8%"><?php if ($bc['masa'] == "Pagi") {
                                                    echo $bc['presure'] . '<small><i>bar</i></small>';
                                                } ?>
                                </td>
                                <td width="9%">
                                    <?php if ($bc['masa'] == "Sore") {
                                        echo $bc['presure'] . '<small><i>bar</i></small>';
                                    } ?>
                                </td>
                                <td width="8%"><?php if ($bc['masa'] == "Siang") {
                                                    echo $bc['presure'] . '<small><i>bar</i></small>';
                                                } ?>
                                </td>
                                <td width="9%"><?php if ($bc['masa'] == "Malam") {
                                                    echo $bc['presure'] . '<small><i>bar</i></small>';
                                                } ?>
                                </td>
                                <td width="15%"></td>
                                <td width="12%"></td>
                                <td width="10%"></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    if (count($lapbacaan) == $i) {
                        $CI = &get_instance();
                        $CI->load->model('Hasilbaca_model');
                    ?>
                        <table width="100%">
                            <tr>
                                <td width="30%" style="text-align: center;">Mengetahui</td>
                                <td width="30%" style="text-align: center;">Menyetujui</td>
                                <td width="30%" style="text-align: center;">Dibuat Oleh</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align:center;">Kepala Cabang <?= $bc['kecamatan']; ?></td>
                                <td width="40%" style="text-align:center;">Kasi.Teknik Cabang <?= $bc['kecamatan']; ?></td>
                                <td width="30%" style="text-align: center;">Petugas Monitoring</td>
                            </tr>
                            <?php for ($a = 0; $a < 10; $a++) : ?>
                                <tr>
                                    <td width="30%" style="text-align:center;"></td>
                                    <td width="30%" style="text-align:center;"></td>
                                    <td width="30%" style="text-align: center;"></td>
                                </tr>
                            <?php endfor; ?>

                            <tr>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'KC')->row_array(); ?>
                                <td width="33,4%" style="text-align:center;">
                                    <h5><?= $ttd['nama']; ?></h5>
                                </td>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'KS')->row_array(); ?>
                                <td width="33,4%" style="text-align:center;">
                                    <h5><?= $ttd['nama']; ?></h5>
                                </td>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'STF', $bc['nik'])->row_array(); ?>
                                <td width="33,4%" style="text-align: center;">
                                    <h5><?= $ttd['nama']; ?></h5>
                                </td>
                            </tr>

                            <tr>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'KC')->row_array(); ?>
                                <td width="33,4%" style="text-align:center;">
                                    <p><small><?= $ttd['pangkat']; ?></small></p>
                                </td>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'KS')->row_array(); ?>
                                <td width="33,4%" style="text-align:center;">
                                    <p><small><?= $ttd['pangkat']; ?></small></p>
                                </td>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'STF', $bc['nik'])->row_array(); ?>
                                <td width="33,4%" style="text-align: center;">
                                    <p><small><?= $ttd['pangkat']; ?></small></p>
                                </td>
                            </tr>

                            <tr>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'KC')->row_array(); ?>
                                <td width="33,4%" style="text-align:center;">
                                    <p><small>NIK :<?= $ttd['nik']; ?></small></p>
                                </td>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'KS')->row_array(); ?>
                                <td width="33,4%" style="text-align:center;">
                                    <p><small>NIK :<?= $ttd['nik']; ?></small></p>
                                </td>
                                <?php $ttd = $CI->Hasilbaca_model->ambilTTD($bc['idareakerja'], 'STF', $bc['nik'])->row_array(); ?>
                                <td width="33,4%" style="text-align:center;">
                                    <p><small>NIK :<?= $ttd['nik']; ?></small></p>
                                </td>
                            </tr>
                        </table>

                <?php    }
                    $masaSebelumnya = $bc['masa'];
                    $tbSebelumnya = $bc['tgl_dibaca'];
                    $zonaSebelumnya = $bc['zona'];
                    $kecamatanSebelumnya = $bc['kecamatan'];
                    $nikSebelumnya = $bc['nik'];
                    $idAreaKerjaSebelumnya = $bc['idareakerja'];
                    $pembacaSebelumnya = $bc['operator'];
                    $halamanSelesai++;
                    $i++;
                    $mulai++;
                    $no++;
                endforeach;

                ?>


            <?php  } ?>


        </div>
    </div>
</body>

</html>