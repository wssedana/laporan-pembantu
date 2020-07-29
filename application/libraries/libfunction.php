<?php

class libfunction
{

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

    function format_periode2($periode)
    { 
        $sub1 = substr($periode, 0, 7);
        $sub2 = substr($periode,16,4);
        $sub3 = substr($periode,20,2);
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


    function format_periodeSR($periode)
    { 
        $sub1 = substr($periode, 0, 8);
        $sub2 = substr($periode,9,4);
        $sub3 = substr($periode,13,2);
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
};
