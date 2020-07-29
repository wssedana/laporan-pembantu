<?php
defined('BASEPATH') or exit('No direct script access allowed');

class masterData_model extends CI_Model
{
    public function zona($key = null, $wilayah = null, $zona = null, $start, $limit)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, b.kecamatan, b.`id_kecamatan`
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND a.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'";

            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona ASC LIMIT $start, $limit";
        } else {
            $sql = "SELECT a.*, b.kecamatan, b.`id_kecamatan`
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND a.zona = '$zona' ";
            }
            $sql = $sql . " ORDER BY b.id_kecamatan,a.id_zona ASC LIMIT $start, $limit";
        }

        return $this->db->query($sql)->result_array();
    }

    public function countZona($key = null, $wilayah = null, $zona = null)
    {
        if ($key) {
            $sql = "SELECT xy.* FROM (SELECT a.*, b.kecamatan, b.`id_kecamatan`
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND a.zona = '$zona' ";
            }
            $sql = $sql . ") AS xy WHERE xy.kecamatan LIKE '%$key%'
                            OR xy.zona LIKE '%$key%'";

            $sql = $sql . " ORDER BY xy.id_kecamatan,xy.id_zona ASC";
        } else {
            $sql = "SELECT a.*, b.kecamatan, b.`id_kecamatan`
                        FROM m_zona a LEFT JOIN m_kecamatan b
                        ON a.id_kecamatan = b.id_kecamatan
                        WHERE a.id_zona > 0";
            if ($wilayah) {
                $sql = $sql . " AND b.kecamatan = '$wilayah' ";
            }
            if ($zona) {
                $sql = $sql . " AND a.zona = '$zona' ";
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
}
