<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanXls_model extends CI_Model
{
    public function getLaporan($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik, $daterange = null, $tglStart = null, $tglEnd = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*,DATE_FORMAT(a.tgl_baca, '%a/%d-%m-%Y')AS tgl_dibaca,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,c.idareakerja,d.id_zona,d.zona,e.id_dma, e.nama_dma,f.index_ 
            FROM $periode a 
            LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
            LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
            LEFT JOIN m_zona d ON d.id_zona=b.id_zona
            LEFT JOIN m_dma e ON e.id_dma=b.id_dma
            LEFT JOIN m_masa f ON a.masa = f.masa
            WHERE a.id_manometer > 0 AND a.verifikasi = '1' ";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            if ($daterange) {
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd'";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                            OR xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'
                            OR xy.operator LIKE '%$key%'";

            $sql = $sql . "ORDER BY xy.id_kecamatan, xy.id_zona, DATE(xy.tgl_baca), xy.index_, xy.nama_dma ASC";
        } else {
            $sql = "SELECT a.*,DATE_FORMAT(a.tgl_baca, '%a/%d-%m-%Y')AS tgl_dibaca,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,c.idareakerja,d.id_zona,d.zona,e.id_dma,e.nama_dma, f.index_ 
                        FROM $periode a LEFT JOIN m_manometer b 
                        ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c 
                        ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d 
                        ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e 
                        ON e.id_dma=b.id_dma
                        LEFT JOIN m_masa f
                        ON a.masa = f.masa
                    WHERE a.id_manometer > 0 AND a.verifikasi = '1' ";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            if ($daterange) {
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd'";
            }
            $sql = $sql . "  ORDER BY id_kecamatan, id_zona, DATE(tgl_baca), index_, nama_dma ASC";
        }

        return $this->db->query($sql)->result_array();
    }

    public function getDataManometer($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, DATE_FORMAT(a.tgl_baca, '%a/%d-%m-%Y')AS tgl_dibaca,b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan, c.idareakerja, d.id_zona, d.zona, e.nama_dma. e.id_dma
            FROM $periode a 
            LEFT JOIN m_manometer b 
            ON b.`id_manometer`=a.`id_manometer` 
            LEFT JOIN m_kecamatan c 
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d 
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e 
            ON b.`id_dma` = e.`id_dma`
            WHERE a.`id_manometer` > 0 AND a.`verifikasi` =  '1'";

            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                OR xy.kecamatan LIKE '%$key%'
                OR xy.zona LIKE '%$key%'
                OR xy.operator LIKE '%$key%'";

            $sql = $sql . " GROUP BY xy.`id_manometer`
                ORDER BY xy.`id_zona`,xy.`nama_dma`, xy.`kode_manometer`";
        } else {
            $sql = "SELECT a.*, DATE_FORMAT(a.tgl_baca, '%a/%d-%m-%Y')AS tgl_dibaca,b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan, c.idareakerja, d.id_zona, d.zona, e.nama_dma, e.id_dma
            FROM $periode a LEFT JOIN m_manometer b  ON b.`id_manometer`=a.`id_manometer` 
            LEFT JOIN m_kecamatan c ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e ON b.`id_dma` = e.`id_dma`
            WHERE a.`id_manometer` > 0 AND a.`verifikasi` = '1'";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . " GROUP BY `id_manometer`
            ORDER BY `id_zona`,`nama_dma`,`kode_manometer`";
        }
        return $this->db->query($sql)->result_array();
    }

    public function getPagi($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca,b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan,c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Pagi' AND verifikasi = '1'";

            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                OR xy.kecamatan LIKE '%$key%'
                OR xy.zona LIKE '%$key%'
                OR xy.operator LIKE '%$key%'";

            $sql = $sql . " GROUP BY xy.masa, xy.tgl_dibaca 
            ORDER BY xy.tgl_dibaca ASC";
        } else {
            $sql = "SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca,b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan,c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Pagi' AND verifikasi = '1'";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . " GROUP BY masa, tgl_dibaca
            ORDER BY tgl_dibaca ASC";
        }

        return $this->db->query($sql);
    }

    public function getSore($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca,b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan,c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Sore' AND verifikasi = '1'";

            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                OR xy.kecamatan LIKE '%$key%'
                OR xy.zona LIKE '%$key%'
                OR xy.operator LIKE '%$key%'";

            $sql = $sql . " GROUP BY xy.masa, xy.tgl_dibaca
             ORDER BY xy.tgl_dibaca ASC";
        } else {
            $sql = "SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca, b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan,c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Sore' AND verifikasi = '1'";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . " GROUP BY masa, tgl_dibaca
            ORDER BY tgl_dibaca ASC";
        }

        return $this->db->query($sql);
    }

    public function getSiang($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca, b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan,c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Siang' AND verifikasi = '1'";

            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                OR xy.kecamatan LIKE '%$key%'
                OR xy.zona LIKE '%$key%'
                OR xy.operator LIKE '%$key%'";

            $sql = $sql . " GROUP BY xy.masa, xy.tgl_dibaca
             ORDER BY xy.tgl_dibaca ASC";
        } else {
            $sql = "SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca, b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan, c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Siang' AND verifikasi = '1'";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . " GROUP BY masa, tgl_dibaca
            ORDER BY tgl_dibaca ASC";
        }

        return $this->db->query($sql);
    }

    public function getMalam($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca, b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan,c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Malam' AND verifikasi = '1'";

            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                OR xy.kecamatan LIKE '%$key%'
                OR xy.zona LIKE '%$key%'
                OR xy.operator LIKE '%$key%'";

            $sql = $sql . " GROUP BY xy.masa, xy.tgl_dibaca
             ORDER BY xy.tgl_dibaca ASC";
        } else {
            $sql = "SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca, b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan, c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE masa ='Malam' AND verifikasi = '1'";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . " GROUP BY masa, tgl_dibaca
            ORDER BY tgl_dibaca ASC";
        }

        return $this->db->query($sql);
    }

    public function getSemuaMasa($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca,b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan, c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE a.`id_manometer` > 0 AND a.`verifikasi` ='1' ";

            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                OR xy.kecamatan LIKE '%$key%'
                OR xy.zona LIKE '%$key%'
                OR xy.operator LIKE '%$key%'";

            $sql = $sql . " GROUP BY xy.masa, xy.tgl_dibaca
             ORDER BY xy.tgl_dibaca ASC";
        } else {
            $sql = "SELECT a.*, DATE_FORMAT(a.tgl_baca, '%d')AS tgl_dibaca,b.kode_manometer, b.diameter, b.manometer, c.id_kecamatan, c.kecamatan, c.idareakerja, d.id_zona, d.zona, e.nama_dma
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer` = b.`id_manometer`
            LEFT JOIN m_kecamatan c
            ON b.`id_kecamatan` = c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON b.`id_zona` = d.`id_zona`
            LEFT JOIN m_dma e
            ON b.`id_dma` = e.`id_dma`
            WHERE a.`id_manometer` > 0 AND a.`verifikasi`='1' ";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }
            $sql = $sql . " GROUP BY masa, tgl_dibaca
            ORDER BY tgl_dibaca ASC";
        }

        return $this->db->query($sql);
    }

    public function getRerata($id_manometer, $masa, $periode)
    {
        $sql = " SELECT 
        ((SUM(`presure`))/(COUNT(`presure`))) AS rerata_presure
        FROM $periode 
        WHERE `masa` = '$masa' AND `id_manometer` = $id_manometer";

        return $this->db->query($sql);
    }

    public function getPresure($id_manometer, $masa, $tgl_baca, $periode)
    {
        $sql = "SELECT `presure` FROM $periode 
        WHERE `id_manometer` = $id_manometer AND `masa` = '$masa' AND DATE_FORMAT(`tgl_baca`,'%d') = '$tgl_baca'
        ";

        return $this->db->query($sql);
    }

    public function ambilTTD($idAreaKerja, $idJabatan, $nik = null)
    {
        $db2 = $this->load->database('datacenter', TRUE);
        $sql = "SELECT a.`nik`,a.`nama`,e.`pangkat`,a.tugas,a.`idjabatan`,d.`jabatan`,a.`idareakerja`,b.`areakerja`,c.`level`,c.`role_id` FROM m_pegawai a
        LEFT JOIN m_area_kerja b ON b.`idareakerja`=a.`idareakerja`
        LEFT JOIN m_user c ON c.`nik`=a.`nik`
        LEFT JOIN m_jabatan d ON d.`idjabatan`=a.`idjabatan`
        LEFT JOIN m_golongan e ON e.`kodegol`=a.`kodegol`
        WHERE c.`mod_manometer`='Y' AND b.`idareakerja`='$idAreaKerja' AND d.`idjabatan`='$idJabatan' ";
        if ($nik) {
            $sql = $sql . "AND a.`nik`='$nik'";
        }
        $sql = $sql . "ORDER BY d.`indekjabatan` ASC";

        return $db2->query($sql);
    }

    public function countSRPerDMA($pelanggan, $id_dma)
    {
        $db2 = $this->load->database('datacenter', TRUE);
        $sql = "SELECT * FROM $pelanggan WHERE `id_dma` = $id_dma";
        return $db2->query($sql)->num_rows();
    }
}
