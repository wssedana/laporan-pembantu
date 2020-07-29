<?php
defined('BASEPATH') or exit('No direct script access allowed');

class posting_model extends CI_Model
{
    public function postingMano($periodePostingMano)
    {
        $nik = $this->session->userdata('nik');
        $query = "SHOW TABLES LIKE '$periodePostingMano'";
        $result = $this->db->query($query)->num_rows();
        if ($result > 0) {
            echo "Data Manometer pada Bulan dipilih Sudah Pernah Diposting";
            return $result;
        } else {
            $query = "CREATE TABLE $periodePostingMano LIKE `m_manometer_templates`";
            $result = $this->db->query($query);
            if ($result == true) {
                $date = date('Y-m-d H:i:s');
                $query = "INSERT INTO $periodePostingMano (SELECT * FROM m_manometer)";
                $result = $this->db->query($query);
                if ($result == true) {
                    $query = "INSERT INTO log_manometer_posting(`periode`,`creator`,`date_created`,`is_active`) VALUES('$periodePostingMano','$nik','$date', '1')";
                    $result = $this->db->query($query);
                    if ($result == true){
                        echo "Berhasil melakukan posting data Manometer periode $periodePostingMano ";
                    }else{
                        echo "Gagal melakukan posting data Manometer periode $periodePostingMano ";
                    }
                } else {
                    echo "Gagal melakukan posting data Manometer periode $periodePostingMano ";
                }
            } else {
                echo "Gagal Membuat Table Manometer Posting";
            }
        }
    }

    public function getListManometerPosting()
    {
        $query = "SELECT a.*, b.*,c.`nama`
        FROM `pdamgianyar_manometer`.`log_manometer_posting` a JOIN (SELECT table_name,create_time 
               FROM information_schema.tables 
               WHERE table_schema = 'pdamgianyar_manometer' 
               AND table_name 
               LIKE 'manometerposting%') b
        ON a.`periode` = b.`table_name`
        JOIN `pdamgianyar_datacenter`.`m_pegawai` c
        ON a.`creator` = c.`nik`
        WHERE a.`is_active` = '1'
        ";

        return $this->db->query($query)->result_array();
    }

    public function jumlahManoPosting($tablename)
    {
        $query = "SELECT * FROM $tablename";
        return $this->db->query($query)->num_rows();
    }


    public function postingSR($periodePostingSR)
    {
        $nik = $this->session->userdata('nik');
        $query = "SHOW TABLES LIKE '$periodePostingSR'";
        $result = $this->db->query($query)->num_rows();
        if ($result > 0) {
            echo "Data Manometer pada Bulan dipilih Sudah Pernah Diposting";
            return $result;
        } else {
            $query = "CREATE TABLE $periodePostingSR LIKE `pdamgianyar_datacenter`.`m_latlong`";
            $result = $this->db->query($query);
            if ($result == true) {
                $date = date('Y-m-d H:i:s');
                $query = "INSERT INTO $periodePostingSR (SELECT * FROM `pdamgianyar_datacenter`.`m_latlong`)";
                $result = $this->db->query($query);
                if ($result == true) {
                    $query = "INSERT INTO log_sr_posting(`periode`,`creator`,`date_created`,`is_active`) VALUES('$periodePostingSR','$nik','$date', '1')";
                    $result = $this->db->query($query);
                    if ($result == true){
                        echo "Berhasil melakukan posting data Manometer periode $periodePostingSR ";
                    }else{
                        echo "Gagal melakukan posting data Manometer periode $periodePostingSR ";
                    }
                } else {
                    echo "Gagal melakukan posting data Manometer periode $periodePostingSR ";
                }
            } else {
                echo "Gagal Membuat Table SR Posting";
            }
        }
    }

    public function jumlahSrPosting($tablename)
    {
        $query = "SELECT * FROM $tablename";
        return $this->db->query($query)->num_rows();
    }

    function getListSrPosting()
    {
        $query = "SELECT a.*, b.*,c.`nama`
        FROM `pdamgianyar_manometer`.`log_sr_posting` a JOIN (SELECT table_name,create_time 
               FROM information_schema.tables 
               WHERE table_schema = 'pdamgianyar_manometer' 
               AND table_name 
               LIKE 'SRposting%') b
        ON a.`periode` = b.`table_name`
        JOIN `pdamgianyar_datacenter`.`m_pegawai` c
        ON a.`creator` = c.`nik`
        WHERE a.`is_active` = '1' ";
        return $this->db->query($query)->result_array();
    }
}
