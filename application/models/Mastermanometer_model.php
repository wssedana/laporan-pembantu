<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mastermanometer_model extends CI_Model
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

    public function getTahunPeriode()
    {
        $query = "SELECT table_name,create_time 
                    FROM information_schema.tables 
                    WHERE table_schema = 'pdamgianyar_manometer' 
                    AND table_name 
                    LIKE 'periode%'
                    GROUP BY year(create_time)";

        return $this->db->query($query)->result_array();
    }

    public function getManometerAll($key = null, $wilayah = null, $zona = null, $start, $limit)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*,b.kecamatan,c.zona,d.nama_dma  FROM m_manometer a 
                        LEFT JOIN m_kecamatan b ON a.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona c ON a.id_zona=c.id_zona
                        LEFT JOIN m_dma d ON a.id_dma=d.id_dma
                        WHERE a.id_manometer > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND c.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                            OR xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'
                            OR xy.operator LIKE '%$key%'";
            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona,CONVERT(xy.kode_manometer, UNSIGNED) ASC LIMIT $start, $limit";
        } else {
            $sql = "SELECT a.*,b.kecamatan,c.zona,d.nama_dma  FROM m_manometer a 
            LEFT JOIN m_kecamatan b on a.id_kecamatan=b.id_kecamatan 
            LEFT JOIN m_zona c on a.id_zona=c.id_zona
            LEFT JOIN m_dma d on a.id_dma=d.id_dma
            WHERE a.id_manometer > 0 ";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND c.zona = '$zona' ";
            }

            $sql = $sql . "ORDER BY a.id_kecamatan,a.id_zona,CONVERT(a.kode_manometer, UNSIGNED) ASC LIMIT $start, $limit";
        }

        return $this->db->query($sql)->result_array();
    }

    public function countManometerAll($key = null, $wilayah = null, $zona = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*,b.kecamatan,c.zona,d.nama_dma FROM m_manometer a 
                        LEFT JOIN m_kecamatan b ON a.id_kecamatan=b.id_kecamatan 
                        LEFT JOIN m_zona c ON a.id_zona=c.id_zona
                        LEFT JOIN m_dma d ON a.id_dma=d.id_dma
                        WHERE a.id_manometer > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND c.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.manometer LIKE '%$key%'
                            OR xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'
                            OR xy.operator LIKE '%$key%'";
            $sql = $sql . "ORDER BY xy.id_kecamatan,xy.id_zona,CONVERT(xy.kode_manometer, UNSIGNED)";
        } else {
            $sql = "SELECT a.*,b.kecamatan,c.zona,d.nama_dma FROM m_manometer a 
            LEFT JOIN m_kecamatan b on a.id_kecamatan=b.id_kecamatan
            LEFT JOIN m_zona c on a.id_zona=c.id_zona
            LEFT JOIN m_dma d on a.id_dma=d.id_dma
            WHERE a.id_manometer > 0 ";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND c.zona = '$zona' ";
            }
            $sql = $sql . "ORDER BY a.id_kecamatan,a.id_zona,CONVERT(a.kode_manometer, UNSIGNED)";
        }
        return $this->db->query($sql)->num_rows();
    }

    public function insertManometer($data)
    {
        return $this->db->insert('m_manometer', $data);
    }

    public function getById($id)
    {
        return $this->db->query("SELECT a.*,b.kecamatan,c.zona,d.nama_dma  FROM m_manometer a 
        LEFT JOIN m_kecamatan b ON a.id_kecamatan=b.id_kecamatan 
        LEFT JOIN m_zona c ON a.id_zona=c.id_zona
        LEFT JOIN m_dma d ON a.id_dma=d.id_dma
        WHERE a.id_manometer=$id")->row_array();
    }

    public function deleteById($id)
    {
        $this->db->where('id_manometer', $id);
        return $this->db->delete('m_manometer');
    }

    public function insertData($data)
    {
        return $this->db->insert('m_manometer', $data);
    }

    public function updateData($id, $data)
    {
        $this->db->where(['id_manometer' => $id]);
        $this->db->update('m_manometer', $data);
    }

    public function getWilayah()
    {
        $wilayah = $this->session->userdata('wilayah');
        if ($wilayah != "KANTOR PUSAT") {
            $query = "SELECT * FROM m_kecamatan WHERE flag_active='1' AND kecamatan='$wilayah'";
        } else {
            $query = "SELECT * FROM m_kecamatan WHERE flag_active='1'";
        }

        return $this->db->query($query)->result_array();
    }

    public function getZona($wilayah = null)
    {
        if ($wilayah != "") {
            $query = "SELECT a.*,b.kecamatan FROM m_zona a 
            LEFT JOIN m_kecamatan b ON a.id_kecamatan=b.id_kecamatan
            WHERE a.flag_active= '1' AND b.kecamatan = '$wilayah'";
        } else {
            $query = "SELECT a.*,b.kecamatan FROM m_zona a 
            LEFT JOIN m_kecamatan b ON a.id_kecamatan=b.id_kecamatan
            WHERE a.flag_active= '1'";
        }
        return $this->db->query($query)->result_array();
    }

    public function get_filterZona($wilayah = null)
    {
        $query = "SELECT a.*,b.kecamatan FROM m_zona a 
        LEFT JOIN m_kecamatan b ON a.id_kecamatan=b.id_kecamatan
        WHERE a.flag_active= '1' AND b.kecamatan = '$wilayah'";

        return $this->db->query($query)->result();
    }

    public function get_filterPembaca($wilayah)
    {
        $query = "SELECT a.nik,a.nama,b.areakerja FROM m_pegawai a 
        LEFT JOIN m_area_kerja b ON a.idareakerja=b.idareakerja
        LEFT JOIN m_user c ON a.nik=c.nik
        WHERE c.mod_manometer='Y' AND c.level='user' AND a.idjabatan='STF' AND b.areakerja LIKE '%$wilayah%'";

        $db2 = $this->load->database('datacenter', TRUE);
        return $db2->query($query)->result();
    }

    public function getNikOperator($nama)
    {
        $query = "SELECT a.nik,a.nama,b.areakerja FROM m_pegawai a 
                    LEFT JOIN m_area_kerja b ON a.idareakerja=b.idareakerja
                    LEFT JOIN m_user c ON a.nik=c.nik
                    WHERE a.nama='$nama'";
        $db2 = $this->load->database('datacenter', TRUE);
        return $db2->query($query)->result();
    }

    public function getPembacaZona($wilayah = null, $zona = null)
    {
        $query = "SELECT nik, operator FROM m_manometer a
                    LEFT JOIN m_kecamatan b ON b.id_kecamatan=a.id_kecamatan
                    LEFT JOIN m_zona c ON c.id_zona=a.id_zona
                    WHERE b.kecamatan='$wilayah' AND c.zona='$zona' GROUP BY nik";
        return $this->db->query($query)->row_array();
    }

    public function getPembaca($wilayah)
    {
        $query = "SELECT a.nik,a.nama,b.areakerja FROM m_pegawai a 
                        LEFT JOIN m_area_kerja b ON a.idareakerja=b.idareakerja
                        LEFT JOIN m_user c ON a.nik=c.nik
                        WHERE c.mod_manometer='Y' AND c.level='user' AND a.idjabatan='STF'";
        if (isset($wilayah)) {
            $query = $query . " AND b.areakerja LIKE '%$wilayah%'";
        }
        $query = $query . " ORDER BY b.indekareakerja ASC";

        $db2 = $this->load->database('datacenter', TRUE);
        return $db2->query($query)->result_array();
    }

    public function getAreaKerja($wilayah)
    {
        $query = "SELECT * FROM m_area_kerja WHERE areakerja LIKE '%$wilayah'";

        $db2 = $this->load->database('datacenter', TRUE);
        return $db2->query($query)->row_array();
    }

    public function getIDZona($zona = null)
    {
        $query = "SELECT * FROM m_zona WHERE zona ='$zona'";

        return $this->db->query($query)->row_array();
    }
}
