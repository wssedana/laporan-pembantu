<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hasilbaca_model extends CI_Model
{
    public function getListPeriode()
    {
        $query = "SELECT table_name,create_time 
                    FROM information_schema.tables 
                    WHERE table_schema = 'pdamgianyar_manometer' 
                    AND table_name 
                    LIKE 'periode%'";

        return $this->db->query($query)->result_array();
    }

    public function countReadingAll($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik, $daterange = null, $tglStart = null, $tglEnd = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*,b.kode_manometer,b.manometer,c.id_kecamatan,c.kecamatan,d.id_zona,d.zona,e.nama_dma,f.index_ FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                        LEFT JOIN m_masa f ON f.masa=a.masa
                    WHERE a.id_manometer > 0 ";
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
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                            OR xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'
                            OR xy.operator LIKE '%$key%'";
            $sql = $sql . " ORDER BY xy.id_kecamatan,DATE(xy.tgl_baca),TIME(xy.tgl_baca),xy.index_,xy.id_zona DESC";
        } else {
            $sql = "SELECT a.*,b.kode_manometer,b.manometer,c.id_kecamatan,c.kecamatan,d.id_zona,d.zona,e.nama_dma,f.index_ FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                        LEFT JOIN m_masa f ON f.masa=a.masa
                    WHERE a.id_manometer > 0 ";
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
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd' ";
            }

            $sql = $sql . " ORDER BY b.id_kecamatan,DATE(a.tgl_baca),TIME(a.tgl_baca),f.index_,b.id_zona DESC";
        }
        return $this->db->query($sql)->num_rows();
    }

    public function getReadingAll($periode, $key = null, $wilayah = null, $zona = null, $start, $limit, $idjabatan, $nik, $daterange = null, $tglStart = null, $tglEnd = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,d.id_zona,d.zona,e.nama_dma,f.index_ FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                        LEFT JOIN m_masa f ON a.masa =f.masa
                    WHERE a.id_manometer > 0 ";
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
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                            OR xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'
                            OR xy.operator LIKE '%$key%'";

            $sql = $sql . " ORDER BY xy.id_kecamatan,DATE(xy.tgl_baca),TIME(xy.tgl_baca),xy.index_,xy.id_zona DESC LIMIT $start, $limit";
        } else {
            $sql = "SELECT a.*,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,d.id_zona,d.zona,e.nama_dma,f.index_ FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                        LEFT JOIN m_masa f ON a.masa =f.masa
                    WHERE a.id_manometer > 0 ";
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
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd' ";
            }

            $sql = $sql . " ORDER BY b.id_kecamatan,DATE(a.tgl_baca),TIME(a.tgl_baca),f.index_,b.id_zona DESC LIMIT $start, $limit";
        }

        return $this->db->query($sql)->result_array();
    }

    public function getReportZona($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,d.id_zona,d.zona,e.nama_dma FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                    WHERE a.id_manometer > 0 ";
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

            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona,CONVERT(xy.kode_manometer, UNSIGNED) ASC";
        } else {
            $sql = "SELECT a.*,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,d.id_zona,d.zona,e.nama_dma FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                    WHERE a.id_manometer > 0 ";
            if ($idjabatan == 'STF') {
                $sql = $sql . " AND a.nik = '$nik' ";
            }
            if ($wilayah) {
                $sql = $sql . " AND c.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND d.zona = '$zona' ";
            }

            $sql = $sql . " ORDER BY id_kecamatan,id_zona,CONVERT(kode_manometer, UNSIGNED)";
        }

        return $this->db->query($sql)->result_array();
    }

    public function getReadingReport($periode, $key = null, $wilayah = null, $zona = null, $idjabatan, $nik, $daterange = null, $tglStart = null, $tglEnd = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*,DATE_FORMAT(a.tgl_baca, '%a/%d-%m-%Y')AS tgl_dibaca,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,c.idareakerja,d.id_zona,d.zona,e.nama_dma, f.index_ FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                        LEFT JOIN m_masa f ON a.masa = f.masa
                    WHERE a.id_manometer > 0 ";
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
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                            OR xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'
                            OR xy.operator LIKE '%$key%'";

            $sql = $sql . "ORDER BY xy.id_kecamatan, xy.id_zona, DATE(xy.tgl_baca), xy.index_ ASC";
        } else {
            $sql = "SELECT a.*,DATE_FORMAT(a.tgl_baca, '%a/%d-%m-%Y')AS tgl_dibaca,b.kode_manometer,b.manometer,b.diameter,c.id_kecamatan,c.kecamatan,c.idareakerja,d.id_zona,d.zona,e.nama_dma,f.index_ FROM $periode a 
                        LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                        LEFT JOIN m_kecamatan c ON c.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona d ON d.id_zona=b.id_zona
                        LEFT JOIN m_dma e ON e.id_dma=b.id_dma
                        LEFT JOIN m_masa f ON f.masa = a.masa
                    WHERE a.id_manometer > 0 ";
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
                $sql = $sql . " AND DATE_FORMAT(a.tgl_baca,'%Y-%m-%d') BETWEEN '$tglStart' AND '$tglEnd' ";
            }

            $sql = $sql . "ORDER BY id_kecamatan,id_zona,DATE(tgl_baca), index_ ASC ";
        }

        return $this->db->query($sql)->result_array();
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

    public function verifyAll($id, $periode)
    {
        for ($i = 0; $i < count($id); $i++) {
            $this->db->where('id_periode', $id[$i]);
            $this->db->update($periode, [
                'verifikasi' => '1',
                'tgl_verifikasi' => date('Y-m-d h:i:s')
            ]);
        }
    }

    public function konfirmasi($id, $periode, $txt)
    {
        for ($i = 0; $i < count($id); $i++) {
            $query = "SELECT keterangan FROM $periode
                    WHERE id_periode = $id[$i] ";

            $hasil = $this->db->query($query)->row_array();

            $this->db->where('id_periode', $id[$i]);
            $this->db->update($periode, [
                'keterangan' => $hasil['keterangan'] . "; VA: " . $txt
            ]);
        }
    }

    public function get_detailBaca($id_periode, $periode)
    {
        $query = "SELECT a.*,b.manometer FROM $periode a
                    LEFT JOIN m_manometer b ON b.id_manometer=a.id_manometer
                    WHERE a.id_periode='$id_periode'";
        return $this->db->query($query)->result_array();
    }
}
