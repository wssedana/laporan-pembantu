<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    public function getNewPresureKecamatan($periode, $id_kecamatan)
    {
        $query = "SELECT b.`id_manometer`, MAX(a.`tgl_baca`), a.`presure`, b.`id_zona`
        FROM $periode a LEFT JOIN m_manometer b
        ON a.`id_manometer` = b.`id_manometer`
        WHERE b.`id_kecamatan` = $id_kecamatan AND a.`presure` >= 0.7 AND a.`verifikasi` = '1'
        GROUP BY b.`id_manometer`";

        $hasil = $this->db->query($query);
        return $hasil->num_rows();
    }

    public function getNewPresureZona($periode, $id_zona)
    {
        $query = "SELECT b.`id_manometer`, MAX(a.`tgl_baca`), a.`presure`, b.`id_zona`
        FROM $periode a LEFT JOIN m_manometer b
        ON a.`id_manometer` = b.`id_manometer`
        WHERE b.`id_zona` = $id_zona AND a.`presure` >= 0.7 AND a.`verifikasi` = '1'
        GROUP BY b.`id_manometer`";

        $hasil = $this->db->query($query);
        return $hasil->num_rows();
    }

    public function getAllKecamatan()
    {
        $query = "SELECT * FROM m_kecamatan WHERE flag_active =1 ";
        return $this->db->query($query)->result_array();
    }

    public function getAllZona()
    {
        $query = "SELECT * FROM m_zona WHERE flag_active =1 ";
        return $this->db->query($query)->result_array();
    }

    public function getManoPerWilayah($id_kecamatan)
    {
        $query = "SELECT * FROM m_manometer WHERE id_kecamatan = $id_kecamatan ";
        return $this->db->query($query)->num_rows();
    }

    public function getManoPerZona($id_zona)
    {
        $query = "SELECT * FROM m_manometer WHERE id_zona = $id_zona";
        return $this->db->query($query)->num_rows();
    }

    public function getPresurePerKecamatan($start, $limit, $periode) // tambah periode
    {
        $query = "SHOW TABLES LIKE '$periode'";
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) {
            $query = "SELECT xy.*, (((xy.`standar`+xy.`diatas_standar`)/xy.`total_baca`)*100) AS rumus , (xy.`standar`+xy.`diatas_standar`) AS standar FROM 
            (SELECT a.`masa`,b.`id_zona`, d.`zona` ,b.`id_kecamatan`,c.`kecamatan` ,
            (SUM(a.`presure`)) AS total_presure,
            (COUNT(a.`presure`))AS total_baca,
            ((SUM(a.`presure`))/(COUNT(a.`presure`))) AS rerata_presure,
            SUM(IF(a.`presure` > 0.7,1,0))AS diatas_standar ,
            SUM(IF(a.`presure` < 0.7,1,0)) AS dibawah_standar , 
            SUM(IF(a.`presure` = 0.7,1,0)) AS standar 
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer`= b.`id_manometer`
            LEFT JOIN m_kecamatan c 
            ON b.`id_kecamatan`=c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON d.`id_zona`=b.`id_zona`
            WHERE a.`verifikasi`='1' 
            GROUP BY b.`id_kecamatan`) AS xy
            LIMIT $start,$limit
            ";
            $result = $this->db->query($query);
            if ($result->num_rows() > 0) {
                return $result->result_array();
            } else {
                echo '<script>
                    alert("Data pada periode ini belum tersedia");
                    </script>';
                return $result->result_array();
            }
        } else {
            echo '<script>
                alert("Periode ini belum tersedia");
                </script>';
            return $hasil->result_array();
        }
    }

    public function jumlahManoKecamatan($idKecamatan, $periode)
    {
        $manoPeriode = "manometerposting" .  substr($periode, 7);
        $query = "SHOW TABLES LIKE '$manoPeriode'";
        $hasil = $this->db->query($query)->num_rows();
        if ($hasil > 0) {
            $query = "SELECT COUNT(manometer)AS total_manometer_kecamatan FROM `$manoPeriode`  WHERE `id_kecamatan` = $idKecamatan";
        } else {
            $query = "SELECT COUNT(manometer)AS total_manometer_kecamatan FROM `m_manometer`  WHERE `id_kecamatan` = $idKecamatan";
        }
        return $this->db->query($query)->row_array();
    }

    public function jumlahManoZona($idZona, $periode)
    {
        $manoPeriode = "manometerposting" . substr($periode, 7);
        $query = "SHOW TABLES LIKE '$manoPeriode'";
        $result = $this->db->query($query)->num_rows();
        if ($result > 0){
            $query = "SELECT COUNT(manometer)AS total_manometer_zona FROM `$manoPeriode`  WHERE `id_zona` = $idZona";
        }else{
            $query = "SELECT COUNT(manometer)AS total_manometer_zona FROM `m_manometer`  WHERE `id_zona` = $idZona";
        }
        // echo $query;
        // die();
        return $this->db->query($query)->row_array();
    }

    public function countAllKecamatan($periode)
    {
        $query = "SHOW TABLES LIKE '$periode'";
        $hasil = $this->db->query($query)->num_rows();
        if ($hasil > 0) {
            $query = "SELECT xy.*, (((xy.`standar`+xy.`diatas_standar`)/xy.`total_baca`)*100) AS rumus FROM 
            (SELECT a.`masa`,b.`id_zona`, d.`zona` ,b.`id_kecamatan`,c.`kecamatan` ,
            (SUM(a.`presure`)) AS total_presure,
            (COUNT(a.`presure`))AS total_baca,
            ((SUM(a.`presure`))/(COUNT(a.`presure`))) AS rerata_presure,
            SUM(IF(a.`presure` > 0.7,1,0))AS diatas_standar ,
            SUM(IF(a.`presure` < 0.7,1,0)) AS dibawah_standar , 
            SUM(IF(a.`presure` = 0.7,1,0)) AS standar 
            FROM $periode a LEFT JOIN m_manometer b
            ON a.`id_manometer`= b.`id_manometer`
            LEFT JOIN m_kecamatan c 
            ON b.`id_kecamatan`=c.`id_kecamatan`
            LEFT JOIN m_zona d
            ON d.`id_zona`=b.`id_zona`
            WHERE a.`verifikasi`='1' 
            GROUP BY b.`id_kecamatan`) AS xy
            ";
            $result = $this->db->query($query);
            if ($result->num_rows() > 0) {
                return $result->num_rows();
            } else {
                return $result->num_rows();
            }
        } else {

            return $hasil;
        }
    }

    public function getPresurePerZona($idKecamatan = null, $start, $limit, $periode) // tambah periode
    {
        $query = "SHOW TABLES LIKE '$periode'";
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) {
            $query = "SELECT xy.*, (((xy.`standar`+xy.`diatas_standar`)/xy.`total_baca`)*100) AS rumus, (xy.`standar`+xy.`diatas_standar`) AS standar FROM 
        (SELECT a.`masa`,b.`id_zona`, d.`zona` ,b.`id_kecamatan`,c.`kecamatan` ,
        (SUM(a.`presure`)) AS total_presure,
        (COUNT(a.`presure`))AS total_baca,
        ((SUM(a.`presure`))/(COUNT(a.`presure`))) AS rerata_presure,
        SUM(IF(a.`presure` > 0.7,1,0))AS diatas_standar ,
        SUM(IF(a.`presure` < 0.7,1,0)) AS dibawah_standar , 
        SUM(IF(a.`presure` = 0.7,1,0)) AS standar 
        FROM $periode a LEFT JOIN m_manometer b
        ON a.`id_manometer`= b.`id_manometer`
        LEFT JOIN m_kecamatan c 
        ON b.`id_kecamatan`=c.`id_kecamatan`
        LEFT JOIN m_zona d
        ON d.`id_zona`=b.`id_zona`
        WHERE a.`verifikasi`='1' AND c.`kecamatan`= '$idKecamatan'
        GROUP BY b.`id_zona`) AS xy
        LIMIT $start,$limit
        ";
            $result = $this->db->query($query);

            if ($result->num_rows() > 0) {
                return $result->result_array();
            } else {
                echo '<script>
                    alert("Data pada periode ini belum tersedia");
                    </script>';
                return $result->result_array();
            }
        } else {
            echo '<script>
            alert("Periode ini belum tersedia");
            </script>';
            return $hasil->result_array();
        }
    }

    public function countAllZona($idKecamatan, $periode)
    {
        $query = "SHOW TABLES LIKE '$periode'";
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) {
            $query = "SELECT xy.*, (((xy.`standar`+xy.`diatas_standar`)/xy.`total_baca`)*100) AS rumus FROM 
        (SELECT a.`masa`,b.`id_zona`, d.`zona` ,b.`id_kecamatan`,c.`kecamatan` ,
        (SUM(a.`presure`)) AS total_presure,
        (COUNT(a.`presure`))AS total_baca,
        ((SUM(a.`presure`))/(COUNT(a.`presure`))) AS rerata_presure,
        SUM(IF(a.`presure` > 0.7,1,0))AS diatas_standar ,
        SUM(IF(a.`presure` < 0.7,1,0)) AS dibawah_standar , 
        SUM(IF(a.`presure` = 0.7,1,0)) AS standar 
        FROM $periode a LEFT JOIN m_manometer b
        ON a.`id_manometer`= b.`id_manometer`
        LEFT JOIN m_kecamatan c 
        ON b.`id_kecamatan`=c.`id_kecamatan`
        LEFT JOIN m_zona d
        ON d.`id_zona`=b.`id_zona`
        WHERE a.`verifikasi`='1' AND c.`kecamatan`= '$idKecamatan'
        GROUP BY b.`id_zona`) AS xy
        ";
            $result = $this->db->query($query);
            if ($result->num_rows() > 0) {
                return $result->num_rows();
            } else {
                return $result->num_rows();
            }
        } else {
            return $hasil->num_rows();
        }
    }

    public function getPresurePerZonaNoLimit($idKecamatan = null, $periode) // tambah periode
    {
        $query = "SHOW TABLES LIKE '$periode'";
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) {
            $query = "SELECT xy.*, (((xy.`standar`+xy.`diatas_standar`)/xy.`total_baca`)*100) AS rumus, (xy.`standar`+xy.`diatas_standar`) AS standar FROM 
        (SELECT a.`masa`,b.`id_zona`, d.`zona` ,b.`id_kecamatan`,c.`kecamatan` ,
        (SUM(a.`presure`)) AS total_presure,
        (COUNT(a.`presure`))AS total_baca,
        ((SUM(a.`presure`))/(COUNT(a.`presure`))) AS rerata_presure,
        SUM(IF(a.`presure` > 0.7,1,0))AS diatas_standar ,
        SUM(IF(a.`presure` < 0.7,1,0)) AS dibawah_standar , 
        SUM(IF(a.`presure` = 0.7,1,0)) AS standar 
        FROM $periode a LEFT JOIN m_manometer b
        ON a.`id_manometer`= b.`id_manometer`
        LEFT JOIN m_kecamatan c 
        ON b.`id_kecamatan`=c.`id_kecamatan`
        LEFT JOIN m_zona d
        ON d.`id_zona`=b.`id_zona`
        WHERE a.`verifikasi`='1' AND c.`kecamatan`= '$idKecamatan'
        GROUP BY b.`id_zona`) AS xy
        ";
            return $this->db->query($query)->result_array();
        } else {
            return $hasil->result_array();
        }
    }

    public function getPresurePerKecamatanNoLimit($periode) // tambah periode
    {
        $query = "SHOW TABLES LIKE '$periode'";
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) {
            $query = "SELECT xy.*, (((xy.`standar`+xy.`diatas_standar`)/xy.`total_baca`)*100) AS rumus, (xy.`standar`+xy.`diatas_standar`) AS standar FROM 
        (SELECT a.`masa`,b.`id_zona`, d.`zona` ,b.`id_kecamatan`,c.`kecamatan` ,
        (SUM(a.`presure`)) AS total_presure,
        (COUNT(a.`presure`))AS total_baca,
        ((SUM(a.`presure`))/(COUNT(a.`presure`))) AS rerata_presure,
        SUM(IF(a.`presure` > 0.7,1,0))AS diatas_standar ,
        SUM(IF(a.`presure` < 0.7,1,0)) AS dibawah_standar , 
        SUM(IF(a.`presure` = 0.7,1,0)) AS standar 
        FROM $periode a LEFT JOIN m_manometer b
        ON a.`id_manometer`= b.`id_manometer`
        LEFT JOIN m_kecamatan c 
        ON b.`id_kecamatan`=c.`id_kecamatan`
        LEFT JOIN m_zona d
        ON d.`id_zona`=b.`id_zona`
        WHERE a.`verifikasi`='1'
        GROUP BY b.`id_kecamatan`) AS xy
        ";
            return $this->db->query($query)->result_array();
        } else {
            return $hasil->result_array();
        }
    }

    public function getPresurePerMasa($periode, $id_zona) // tambah periode
    {

        $query = "SELECT xy.*, (((xy.`standar`+xy.`diatas_standar`)/xy.`total_baca`)*100) AS rumus, (xy.`standar`+xy.`diatas_standar`) AS standar FROM 
        (SELECT a.`masa`,b.`id_zona`, d.`zona` ,b.`id_kecamatan`,c.`kecamatan` ,
        (SUM(a.`presure`)) AS total_presure,
        (COUNT(a.`presure`))AS total_baca,
        ((SUM(a.`presure`))/(COUNT(a.`presure`))) AS rerata_presure,
        SUM(IF(a.`presure` > 0.7,1,0))AS diatas_standar ,
        SUM(IF(a.`presure` < 0.7,1,0)) AS dibawah_standar , 
        SUM(IF(a.`presure` = 0.7,1,0)) AS standar 
        FROM $periode a LEFT JOIN m_manometer b
        ON a.`id_manometer`= b.`id_manometer`
        LEFT JOIN m_kecamatan c 
        ON b.`id_kecamatan`=c.`id_kecamatan`
        LEFT JOIN m_zona d
        ON d.`id_zona`=b.`id_zona`
        WHERE a.`verifikasi`='1' AND b.`id_zona` ='$id_zona'
        GROUP BY a.`masa`) AS xy
        ";
        return $this->db->query($query)->result_array();
    }


    public function kwp()
    {
        $query = " SELECT * FROM       
        ((SELECT v_start AS awalSbaik, v_end AS akhirSbaik FROM m_standart WHERE standart = 'kwp_sbaik') AS a,
        (SELECT v_start AS awalBaik, v_end AS akhirBaik FROM m_standart WHERE standart = 'kwp_baik') AS b,
        (SELECT v_start AS awalKurang, v_end AS akhirKurang FROM m_standart WHERE standart = 'kwp_kurang') AS c,
        (SELECT v_start AS awalBuruk, v_end AS akhirBuruk FROM m_standart WHERE standart = 'kwp_buruk') AS d)";

        return $this->db->query($query)->row_array();
    }
}
