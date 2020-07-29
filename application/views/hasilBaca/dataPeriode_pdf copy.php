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
            border: 1;
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
                                    <th rowspan="2">LOKASI MANOMETER</th>
                                    <th rowspan="2">TANGGAL BACA</th>
                                    <th colspan="2">JAM PUNCAK</th>
                                    <th rowspan="2">MALAM</th>
                                    <th rowspan="2">STATUS</th>
                                    <th rowspan="2">KONDISI</th>
                                </tr>
                                <tr style="text-align: center">
                                    <th>PAGI</th>
                                    <th>SORE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="cursor:pointer;">
                                    <td style="text-align: center;" colspan="13">Pencarian <b><?= $total_rows ?></b> tidak ditemukan!, klik untuk refresh halaman</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
            ?>

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
                                <th rowspan="2">LOKASI MANOMETER</th>
                                <th rowspan="2">TANGGAL BACA</th>
                                <th colspan="2">JAM PUNCAK</th>
                                <th rowspan="2">MALAM</th>
                                <th rowspan="2">STATUS</th>
                                <th rowspan="2">KONDISI</th>
                            </tr>
                            <tr style="text-align: center">
                                <th>PAGI</th>
                                <th>SORE</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($lapbacaan as $bc) : ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td>P-<?= $bc['kode_manometer']; ?></td>
                                    <td><?= $bc['manometer']; ?></td>
                                    <td><?= $this->libfunction->format_tanggal($bc['tgl_baca']); ?></td>

                                    <td><?php if ($bc['masa'] == "Pagi") {
                                            echo $bc['presure'] . '<small><i>bar</i></small>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php if ($bc['masa'] == "Sore") {
                                            echo $bc['presure'] . '<small><i>bar</i></small>';
                                        } ?>
                                    </td>
                                    <td><?php if ($bc['masa'] == "Malam") {
                                            echo $bc['presure'] . '<small><i>bar</i></small>';
                                        } ?>
                                    </td>
                                    <td><?= $bc['status_masa']; ?></td>
                                    <td><?= $bc['status_baca']; ?></td>
                                </tr>
                            <?php
                                $no++;
                            endforeach;

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>