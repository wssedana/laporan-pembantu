<?php
function format_periode($periode)
{
    $sub1 = substr($periode, 0, 7);
    $sub2 = substr($periode, 7, 4);
    $sub3 = substr($periode, 11, 2);
    $newperiode = strtotime($sub3 . "/1" . "/" . $sub2);

    $tanggal = date('m/Y', $newperiode);

    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('/', $tanggal);

    return $bulan[(int) $pecahkan[0]] . ' ' . $pecahkan[1];
}

function format_tanggal($tanggal)
{
    return date_format(date_create($tanggal), "d F Y H:i:s");
}

if ($this->session->userdata('username') == null) {
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" style="text-align: center">Sesi Anda telah habis!,<br/> Silahkan Login kembali.</div>');
    redirect('auth');
} else {
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title; ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link rel="shortcut icon" href="<?= base_url('assets/'); ?>img/icon/manometer.ico" />
    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('assets/'); ?>vendor/chart.js/Chart.min.js"></script>


    <!-- Page level custom scripts -->


    <!-- Datetimepicker -->
    <script src="<?= base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .sbaik {
            color: #66ff33;
        }

        .baik {
            color: #035efc;
        }

        .kurang {
            color: #ffff00;
        }

        .buruk {
            color: #ff0000;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">