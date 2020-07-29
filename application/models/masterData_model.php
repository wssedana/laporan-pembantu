<?php
defined('BASEPATH') or exit('No direct script access allowed');

class masterData_model extends CI_Model
{
    public function zona($key = null, $wilayah = null, $start, $limit)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . ") AS xy WHERE xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'";

            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona ASC LIMIT $start, $limit";
        } else {
            $sql = "SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . " ORDER BY b.id_kecamatan,a.id_zona ASC LIMIT $start, $limit";
        }

        return $this->db->query($sql)->result_array();
    }

    public function countZona($key = null, $wilayah = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . ") AS xy WHERE xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'";

            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona ASC";
        } else {
            $sql = "SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . " ORDER BY b.id_kecamatan,a.id_zona ASC";
        }

        return $this->db->query($sql)->num_rows();
    }

    public function getWilayah()
    {
        $query = "SELECT * FROM m_kecamatan WHERE flag_active = 1";
        return $this->db->query($query)->result_array();
    }

    public function getDetail($idZona)
    {
        $query = "SELECT a.*, b.kecamatan
                 FROM m_zona a LEFT JOIN m_kecamatan b
                 ON a.id_kecamatan = b.id_kecamatan
                 WHERE a.id_zona = $idZona ";
        return $this->db->query($query);
    }

    public function update($idZona, $data)
    {
        $this->db->where('id_zona', $idZona);
        return $this->db->update('m_zona', $data);
    }

    public function delete($idZona)
    {
        $this->db->where('id_zona', $idZona);
        return $this->db->delete('m_zona');
    }

    //for DMA
    public function dma($key = null, $wilayah = null, $start, $limit)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . ") AS xy WHERE xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'";

            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona ASC LIMIT $start, $limit";
        } else {
            $sql = "SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . " ORDER BY b.id_kecamatan,a.id_zona ASC LIMIT $start, $limit";
        }
        return $this->db->query($sql)->result_array();
    }

    public function countDma($key = null, $wilayah = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . ") AS xy WHERE xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'";

            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona ASC";
        } else {
            $sql = "SELECT a.*, b.kecamatan
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            $sql = $sql . " ORDER BY b.id_kecamatan,a.id_zona ASC";
        }

        return $this->db->query($sql)->num_rows();
    }
}
