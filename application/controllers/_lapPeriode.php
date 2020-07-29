<?php
defined('BASEPATH') or exit('No direct script access allowed');

class _lapPeriode extends CI_Controller
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Makassar');
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Hasilbaca_model');
        $this->load->model('Mastermanometer_model');
        $this->load->model('LaporanXls_model');
        $this->load->library('libfunction');
        $this->load->helper(array('form', 'url'));
        //is_logged_in();
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $nik = $this->session->userdata('nik');
        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();
        $data['title'] = 'Hasil Baca Manometer';

        //memanggil model,  nama model         aliasnya
        $this->load->model('Hasilbaca_model', 'lsMasterData');

        //memanggil data dari database menggunakan model dengan memanggil aliasnya
        $data['listPeriode'] = $this->lsMasterData->getListPeriode();
        //------------------------------nama table database
        $data['lsMasterData'] = $this->db->get('information_schema.tables')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('laporanXls/index', $data);
        $this->load->view('templates/footer');
    }

    public function cetaXls($periode)
    {
        include APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        $wilayah = $this->session->userdata('kodeWilayah'); // ambil wilayah berdasarkan session user login
        $nik = $this->session->userdata('nik');
        $role_id = $this->session->userdata('role_id');
        $zona = $this->session->userdata('kodeZona');
        $idjabatan = $this->session->userdata('idjabatan');
        $key = $this->input->get('keyword');
        $tglStart = $this->session->userdata('tglStart');
        $tglEnd = $this->session->userdata('tglEnd');
        $daterange = $this->session->userdata('dateRange');

        $laporan = $this->LaporanXls_model->getLaporan($periode, $key, $wilayah, $zona, $idjabatan, $nik, $daterange, $tglStart, $tglEnd);

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();
        // Settingan awal fil excel
        $excel->getProperties()->setCreator('PERUMDA AMTS')
            ->setLastModifiedBy('PERUMDA AMTS')
            ->setTitle("Laporan Baca Manometer")
            ->setSubject("Laporan Baca Manometer")
            ->setDescription("Laporan Baca Manometer")
            ->setKeywords("Manometer");
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $ttd = array(
            'font' => array('bold' => false), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            )
        );
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );
        //Atur baris pertama
        $i = 1;
        $no = 1;
        $baris = 1;
        $tbSebelumnya = "";
        $zonaSebelumnya = "";
        $kecamatanSebelumnya = "";
        $masaSebelumnya = "";
        $halamanSelesai = 0;
        $pembacaSebelumnya = "";
        $mulai = 0;
        $pagi = "";
        $sore = "";
        $malam = "";

        if (count($laporan) == 0) {
            $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "PENANGGULANGAN KEHILANGAN AIR"); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1
            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PAMTS KABUPATEN GIANYAR"); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1
            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "MANAJEMEN TEKANAN"); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

            //$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai E1

            $baris = $baris + 2;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, 'MONITORING TEKANAN MANOMETER'); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

            $baris = $baris + 2;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PAMTS CABANG");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $this->session->userdata('kodeWilayah'));

            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "ZONA");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $this->session->userdata('kodeZona'));

            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PEMBACA");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, '');

            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "TANGGAL");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, '');

            //THEAD DIMULAI
            $baris = $baris + 2;
            $baris2 = $baris;
            $baris3 = $baris2;
            $baris4 = $baris3;
            $baris5 = $baris4; //dari sini
            $baris6 = $baris5 + 1;
            $baris7 = $baris6 + 1;
            $baris8 = $baris6;
            $baris9 = $baris5;
            $baris10 = $baris9;
            $baris11 = $baris10;
            $baris12 = $baris11;
            $baris13 = $baris12;
            $baris14 = $baris13;

            //ATUR NILAI MASING-MASING THEAD
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "NO");
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris2, "KODE");
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris3, "LOKASI MANOMETER");
            $excel->getActiveSheet()->getStyle('C' . $baris3)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris4, "TAP PIPA");
            $excel->getActiveSheet()->getStyle('D' . $baris4)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris5, "TEKANAN BAR");
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris6, "JAM PUNCAK");
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris7, "PAGI");
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $baris7, "SORE");
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris8, "MALAM");
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $baris9, "INDIKATOR PELAYANAN");
            $excel->getActiveSheet()->getStyle('H' . $baris9)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $baris10, "JUMLAH SR");
            $excel->getActiveSheet()->getStyle('I' . $baris10)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $baris11, "KET");
            $excel->getActiveSheet()->getStyle('J' . $baris11)->getAlignment()->setWrapText(true);



            //APPLY STYLE MASING2 KOLOM
            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $baris5)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F' . $baris5)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $baris5)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $baris6)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $baris7)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F' . $baris7)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $baris8)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $baris9)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $baris10)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $baris11)->applyFromArray($style_col);



            //ATUR STYLE MERGECELL
            $baris = $baris + 1;
            $baris2 = $baris2 + 1;
            $baris3 = $baris3 + 1;
            $baris4 = $baris4 + 1;
            $baris9 = $baris9 + 1;
            $baris10 = $baris10 + 1;
            $baris11 = $baris11 + 1;
            $baris12 = $baris12 + 1;
            $baris13 = $baris13 + 1;
            $baris14 = $baris14 + 1;

            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $baris9)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $baris10)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $baris11)->applyFromArray($style_col);


            //ATUR MERGECELL
            $baris = $baris - 1;
            $baris2 = $baris2 - 1;
            $baris3 = $baris3 - 1;
            $baris4 = $baris4 - 1;
            $baris9 = $baris9 - 1;
            $baris10 = $baris10 - 1;
            $baris11 = $baris11 - 1;
            $baris12 = $baris12 - 1;
            $baris13 = $baris13 - 1;
            $baris14 = $baris14 - 1;

            $excel->getActiveSheet()->mergeCells('A' . $baris . ':A' . $baris = $baris + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('B' . $baris2 . ':B' . $baris2 = $baris2 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('C' . $baris3 . ':C' . $baris3 = $baris3 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('D' . $baris4 . ':D' . $baris4 = $baris4 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('E' . $baris5 . ':G' . $baris5); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('E' . $baris6 . ':F' . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('G' . $baris8 . ':G' . $baris8 = $baris8 + 1); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('H' . $baris9 . ':H' . $baris9 = $baris9 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('I' . $baris10 . ':I' . $baris10 = $baris10 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('J' . $baris11 . ':J' . $baris11 = $baris11 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2

            //ATUR TYLE MERGECELL
            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $baris8)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $baris9)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $baris10)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $baris11)->applyFromArray($style_col);

            $baris = $baris + 1;

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "DATA PADA ZONA INI BELUM TERSEDIA");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':J' . $baris);
            $excel->getActiveSheet()->getStyle('A' . $baris . ':J' . $baris)->applyFromArray($style_col);
        }

        foreach ($laporan as $data) {
            if ($data['tgl_dibaca'] != $tbSebelumnya || $zonaSebelumnya != $data['zona'] || $masaSebelumnya != $data['masa']) {
                if ($mulai > 0) {
                    $baris = $baris + 2;
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Mengetahui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Menyetujui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Dibuat Oleh"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Kepala Cabang " . ucwords($kecamatanSebelumnya)); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Kasi.Teknik Cabang " . ucwords($kecamatanSebelumnya)); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Petugas Monitoring"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 4;
                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $baris = $baris + 1;


                    $excel->getActiveSheet('')->setBreak('A' . $baris, PHPExcel_Worksheet::BREAK_ROW);
                    $baris = $baris + 1;
                    //disini ditambah pembaca sebelumnya
                }
                $no = 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "PENANGGULANGAN KEHILANGAN AIR"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PERUMDA AIR MINUM TIRTA SANJIWANI"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "KABUPATEN GIANYAR"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "MANAJEMEN TEKANAN"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                //$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai E1

                $baris = $baris + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, 'MONITORING TEKANAN MANOMETER'); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

                $baris = $baris + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PAMTS CABANG");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['kecamatan']);

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "ZONA");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['id_zona'] . " " . $data['zona']);

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PEMBACA");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['operator']);

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "TANGGAL");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['tgl_dibaca']);

                //THEAD DIMULAI
                $baris = $baris + 2;
                $baris2 = $baris;
                $baris3 = $baris2;
                $baris4 = $baris3;
                $baris5 = $baris4; //dari sini
                $baris6 = $baris5 + 1;
                $baris7 = $baris6 + 1;
                $baris8 = $baris6;
                $baris9 = $baris5;
                $baris10 = $baris9;
                $baris11 = $baris10;
                $baris12 = $baris11;
                $baris13 = $baris12;
                $baris14 = $baris13;

                //ATUR NILAI MASING-MASING THEAD
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "NO");
                $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris2, "KODE");
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris3, "TAP PIPA");
                $excel->getActiveSheet()->getStyle('C' . $baris3)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris4, "LOKASI MANOMETER");
                $excel->getActiveSheet()->getStyle('D' . $baris4)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris5, "TEKANAN BAR");
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris6, "JAM PUNCAK");
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris7, "PAGI");
                $excel->setActiveSheetIndex(0)->setCellValue('F' . $baris7, "SORE");
                $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris8, "MALAM");
                $excel->setActiveSheetIndex(0)->setCellValue('H' . $baris9, "INDIKATOR PELAYANAN");
                $excel->getActiveSheet()->getStyle('H' . $baris9)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('I' . $baris10, "JUMLAH SR");
                $excel->getActiveSheet()->getStyle('I' . $baris10)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $baris11, "KET");
                $excel->getActiveSheet()->getStyle('J' . $baris11)->getAlignment()->setWrapText(true);



                //APPLY STYLE MASING2 KOLOM
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('E' . $baris5)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('F' . $baris5)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('G' . $baris5)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('E' . $baris6)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('E' . $baris7)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('F' . $baris7)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('G' . $baris8)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('H' . $baris9)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('I' . $baris10)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('J' . $baris11)->applyFromArray($style_col);



                //ATUR STYLE MERGECELL
                $baris = $baris + 1;
                $baris2 = $baris2 + 1;
                $baris3 = $baris3 + 1;
                $baris4 = $baris4 + 1;
                $baris9 = $baris9 + 1;
                $baris10 = $baris10 + 1;
                $baris11 = $baris11 + 1;
                $baris12 = $baris12 + 1;
                $baris13 = $baris13 + 1;
                $baris14 = $baris14 + 1;

                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('H' . $baris9)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('I' . $baris10)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('J' . $baris11)->applyFromArray($style_col);


                //ATUR MERGECELL
                $baris = $baris - 1;
                $baris2 = $baris2 - 1;
                $baris3 = $baris3 - 1;
                $baris4 = $baris4 - 1;
                $baris9 = $baris9 - 1;
                $baris10 = $baris10 - 1;
                $baris11 = $baris11 - 1;
                $baris12 = $baris12 - 1;
                $baris13 = $baris13 - 1;
                $baris14 = $baris14 - 1;

                $excel->getActiveSheet()->mergeCells('A' . $baris . ':A' . $baris = $baris + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('B' . $baris2 . ':B' . $baris2 = $baris2 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('C' . $baris3 . ':C' . $baris3 = $baris3 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('D' . $baris4 . ':D' . $baris4 = $baris4 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('E' . $baris5 . ':G' . $baris5); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('E' . $baris6 . ':F' . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('G' . $baris8 . ':G' . $baris8 = $baris8 + 1); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('H' . $baris9 . ':H' . $baris9 = $baris9 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('I' . $baris10 . ':I' . $baris10 = $baris10 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('J' . $baris11 . ':J' . $baris11 = $baris11 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2

                //ATUR TYLE MERGECELL
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('G' . $baris8)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('H' . $baris9)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('I' . $baris10)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('J' . $baris11)->applyFromArray($style_col);

                $baris = $baris + 1;
            }

            if ($data['masa'] == 'Pagi') {
                $pagi = $data['presure'];
            } else if ($data['masa'] == 'Sore') {
                $sore = $data['presure'];
            } else {
                $malam = $data['presure'];
            }

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris, 'P' . $data['kode_manometer']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['diameter'] . '"');
            $excel->getActiveSheet()->getStyle('C' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris, $data['manometer']);
            $excel->getActiveSheet()->getStyle('D' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris, $pagi);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $baris, $sore);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris, $malam);
            $excel->getActiveSheet()->getStyle('G' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $baris, '');
            $excel->getActiveSheet()->getStyle('H' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $baris, '');
            $excel->getActiveSheet()->getStyle('I' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $baris, '');
            $excel->getActiveSheet()->getStyle('J' . $baris)->getAlignment()->setWrapText(true);

            //set rowStyle
            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $baris)->applyFromArray($style_row);


            $baris = $baris + 1;

            if (count($laporan) == $i) {
                $baris = $baris + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Mengetahui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Menyetujui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Dibuat Oleh"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Kepala Cabang " . ucwords($data['kecamatan'])); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Kasi.Teknik Cabang " . ucwords($data['kecamatan'])); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Petugas Monitoring"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 4;
                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'STF', $data['nik'])->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'STF', $data['nik'])->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $baris = $baris + 1;
            }

            $pagi = "";
            $sore = "";
            $malam = "";
            $masaSebelumnya = $data['masa'];
            $tbSebelumnya = $data['tgl_dibaca'];
            $zonaSebelumnya = $data['zona'];
            $kecamatanSebelumnya = $data['kecamatan'];
            $nikSebelumnya = $data['nik'];
            $idAreaKerjaSebelumnya = $data['idareakerja'];
            $pembacaSebelumnya = $data['operator'];
            $halamanSelesai++;
            $i++;
            $mulai++;
            $no++;
        }
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set Autosize width kolom B
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10); // Set Autosize width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(10); // Set Autosize width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25); // Set Autosize width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true); // Set Autosize width kolom E
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true); // Set Autosize width kolom F
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(7); // Set Autosize width kolom G
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(11); // Set Autosize width kolom H
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(8); // Set Autosize width kolom I
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(8); // Set Autosize width kolom J


        //ked dini
        // Set orientasi kertas jadi PORTRAIT
        $excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $excel->getActiveSheet()->getPageMargins()->setTop(0.7);
        $excel->getActiveSheet()->getPageMargins()->setRight(0.4);
        $excel->getActiveSheet()->getPageMargins()->setLeft(0.9);
        $excel->getActiveSheet()->getPageMargins()->setBottom(0.7);

        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle($periode);
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="LAPORAN' . $periode . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
        //akhir export excel    
        // Buat header tabel nya pada baris ke 6
    }

    public function cetaXls2($periode)
    {
        include APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        $wilayah = $this->session->userdata('kodeWilayah'); // ambil wilayah berdasarkan session user login
        $nik = $this->session->userdata('nik');
        $role_id = $this->session->userdata('role_id');
        $zona = $this->session->userdata('kodeZona');
        $idjabatan = $this->session->userdata('idjabatan');
        $key = $this->input->get('keyword');
        $tglStart = $this->session->userdata('tglStart');
        $tglEnd = $this->session->userdata('tglEnd');
        $daterange = $this->session->userdata('dateRange');

        $laporan = $this->LaporanXls_model->getLaporan($periode, $key, $wilayah, $zona, $idjabatan, $nik, $daterange, $tglStart, $tglEnd);

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();
        // Settingan awal fil excel
        $excel->getProperties()->setCreator('PERUMDA AMTS')
            ->setLastModifiedBy('PERUMDA AMTS')
            ->setTitle("Laporan Baca Manometer")
            ->setSubject("Laporan Baca Manometer")
            ->setDescription("Laporan Baca Manometer")
            ->setKeywords("Manometer");
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $ttd = array(
            'font' => array('bold' => false), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            )
        );
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );
        //Atur baris pertama
        $i = 1;
        $no = 1;
        $baris = 1;
        $tbSebelumnya = "";
        $zonaSebelumnya = "";
        $kecamatanSebelumnya = "";
        $masaSebelumnya = "";
        $halamanSelesai = 0;
        $pembacaSebelumnya = "";
        $mulai = 0;
        $pagi = "";
        $sore = "";
        $siang = "";
        $malam = "";

        if (count($laporan) == 0) {
            $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "PENANGGULANGAN KEHILANGAN AIR"); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1
            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PAMTS KABUPATEN GIANYAR"); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1
            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "MANAJEMEN TEKANAN"); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

            //$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai E1

            $baris = $baris + 2;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, 'MONITORING TEKANAN MANOMETER'); // Set kolom A1 dengan tulisan "DATA SISWA"
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
            $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

            $baris = $baris + 2;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PAMTS CABANG");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $this->session->userdata('kodeWilayah'));

            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "ZONA");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $this->session->userdata('kodeZona'));

            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PEMBACA");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, '');

            $baris = $baris + 1;
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "TANGGAL");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, '');

            //THEAD DIMULAI
            $baris = $baris + 2;
            $baris2 = $baris;
            $baris3 = $baris2;
            $baris4 = $baris3;
            $baris5 = $baris4; //dari sini
            $baris6 = $baris5 + 1;
            $baris7 = $baris6 + 1;
            $baris8 = $baris6;
            $baris81 = $baris8 + 1;
            $baris82 = $baris81;
            $baris9 = $baris5;
            $baris10 = $baris9;
            $baris11 = $baris10;
            $baris12 = $baris11;
            $baris13 = $baris12;
            $baris14 = $baris13;

            //ATUR NILAI MASING-MASING THEAD
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "NO");
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris2, "KODE");
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris3, "LOKASI MANOMETER");
            $excel->getActiveSheet()->getStyle('C' . $baris3)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris4, "TAP PIPA");
            $excel->getActiveSheet()->getStyle('D' . $baris4)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris5, "TEKANAN BAR");
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris6, "JAM PUNCAK");
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris7, "PAGI");
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $baris7, "SORE");
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris8, "JAM JENUH");
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris81, "SIANG");
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $baris82, "MALAM");
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $baris9, "INDIKATOR PELAYANAN");
            $excel->getActiveSheet()->getStyle('I' . $baris9)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $baris10, "JUMLAH SR");
            $excel->getActiveSheet()->getStyle('J' . $baris10)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $baris11, "KET");
            $excel->getActiveSheet()->getStyle('K' . $baris11)->getAlignment()->setWrapText(true);



            //APPLY STYLE MASING2 KOLOM
            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $baris5 . ':H' . $baris5)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $baris6)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E' . $baris7)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F' . $baris7)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $baris8 . ':H' . $baris8)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $baris81)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('H' . $baris82)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $baris9)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $baris10)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('K' . $baris11)->applyFromArray($style_col);



            //ATUR STYLE MERGECELL
            $baris = $baris + 1;
            $baris2 = $baris2 + 1;
            $baris3 = $baris3 + 1;
            $baris4 = $baris4 + 1;
            $baris9 = $baris9 + 1;
            $baris10 = $baris10 + 1;
            $baris11 = $baris11 + 1;
            $baris12 = $baris12 + 1;
            $baris13 = $baris13 + 1;
            $baris14 = $baris14 + 1;

            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $baris9)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $baris10)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('K' . $baris11)->applyFromArray($style_col);



            //ATUR MERGECELL
            $baris = $baris - 1;
            $baris2 = $baris2 - 1;
            $baris3 = $baris3 - 1;
            $baris4 = $baris4 - 1;
            $baris9 = $baris9 - 1;
            $baris10 = $baris10 - 1;
            $baris11 = $baris11 - 1;
            $baris12 = $baris12 - 1;
            $baris13 = $baris13 - 1;
            $baris14 = $baris14 - 1;

            $excel->getActiveSheet()->mergeCells('A' . $baris . ':A' . $baris = $baris + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('B' . $baris2 . ':B' . $baris2 = $baris2 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('C' . $baris3 . ':C' . $baris3 = $baris3 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('D' . $baris4 . ':D' . $baris4 = $baris4 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('E' . $baris5 . ':H' . $baris5); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('E' . $baris6 . ':F' . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('G' . $baris8 . ':H' . $baris8); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('I' . $baris9 . ':I' . $baris9 = $baris9 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('J' . $baris10 . ':J' . $baris10 = $baris10 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
            $excel->getActiveSheet()->mergeCells('K' . $baris11 . ':K' . $baris11 = $baris11 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2

            //ATUR TYLE MERGECELL
            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('G' . $baris8)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('I' . $baris9)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('J' . $baris10)->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('K' . $baris11)->applyFromArray($style_col);

            $baris = $baris + 1;

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "DATA PADA ZONA INI BELUM TERSEDIA");
            $excel->getActiveSheet()->mergeCells('A' . $baris . ':K' . $baris);
            $excel->getActiveSheet()->getStyle('A' . $baris . ':K' . $baris)->applyFromArray($style_col);
        }

        foreach ($laporan as $data) {
            if ($data['tgl_dibaca'] != $tbSebelumnya || $zonaSebelumnya != $data['zona'] || $masaSebelumnya != $data['masa']) {
                if ($mulai > 0) {
                    $baris = $baris + 2;
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Mengetahui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Menyetujui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Dibuat Oleh"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Kepala Cabang " . ucwords($kecamatanSebelumnya)); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Kasi.Teknik Cabang " . ucwords($kecamatanSebelumnya)); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Petugas Monitoring"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 4;
                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $baris = $baris + 1;


                    $excel->getActiveSheet('')->setBreak('A' . $baris, PHPExcel_Worksheet::BREAK_ROW);
                    $baris = $baris + 1;
                    //disini ditambah pembaca sebelumnya
                }
                $no = 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "PENANGGULANGAN KEHILANGAN AIR"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PERUMDA AIR MINUM TIRTA SANJIWANI"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "KABUPATEN GIANYAR"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "MANAJEMEN TEKANAN"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

                //$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai E1

                $baris = $baris + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, 'MONITORING TEKANAN MANOMETER'); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

                $baris = $baris + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PAMTS CABANG");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['kecamatan']);

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "ZONA");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['id_zona'] . " " . $data['zona']);

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PEMBACA");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['operator']);

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "TANGGAL");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':B' . $baris); // Set Merge Cell pada kolom A1 sampai E1
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['tgl_dibaca']);

                //THEAD DIMULAI
                $baris = $baris + 2;
                $baris2 = $baris;
                $baris3 = $baris2;
                $baris4 = $baris3;
                $baris5 = $baris4; //dari sini
                $baris6 = $baris5 + 1;
                $baris7 = $baris6 + 1;
                $baris8 = $baris6;
                $baris81 = $baris8 + 1;
                $baris82 = $baris81;
                $baris9 = $baris5;
                $baris10 = $baris9;
                $baris11 = $baris10;
                $baris12 = $baris11;
                $baris13 = $baris12;
                $baris14 = $baris13;

                //ATUR NILAI MASING-MASING THEAD
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "NO");
                $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris2, "KODE");
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris3, "LOKASI MANOMETER");
                $excel->getActiveSheet()->getStyle('C' . $baris3)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris4, "TAP PIPA");
                $excel->getActiveSheet()->getStyle('D' . $baris4)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris5, "TEKANAN BAR");
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris6, "JAM PUNCAK");
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris7, "PAGI");
                $excel->setActiveSheetIndex(0)->setCellValue('F' . $baris7, "SORE");
                $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris8, "JAM JENUH");
                $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris81, "SIANG");
                $excel->setActiveSheetIndex(0)->setCellValue('H' . $baris82, "MALAM");
                $excel->setActiveSheetIndex(0)->setCellValue('I' . $baris9, "INDIKATOR PELAYANAN");
                $excel->getActiveSheet()->getStyle('I' . $baris9)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('J' . $baris10, "JUMLAH SR");
                $excel->getActiveSheet()->getStyle('J' . $baris10)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('K' . $baris11, "KET");
                $excel->getActiveSheet()->getStyle('K' . $baris11)->getAlignment()->setWrapText(true);



                //APPLY STYLE MASING2 KOLOM
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('E' . $baris5 . ':H' . $baris5)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('E' . $baris6)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('E' . $baris7)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('F' . $baris7)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('G' . $baris8 . ':H' . $baris8)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('G' . $baris81)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('H' . $baris82)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('I' . $baris9)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('J' . $baris10)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('K' . $baris11)->applyFromArray($style_col);



                //ATUR STYLE MERGECELL
                $baris = $baris + 1;
                $baris2 = $baris2 + 1;
                $baris3 = $baris3 + 1;
                $baris4 = $baris4 + 1;
                $baris9 = $baris9 + 1;
                $baris10 = $baris10 + 1;
                $baris11 = $baris11 + 1;
                $baris12 = $baris12 + 1;
                $baris13 = $baris13 + 1;
                $baris14 = $baris14 + 1;

                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('I' . $baris9)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('J' . $baris10)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('K' . $baris11)->applyFromArray($style_col);



                //ATUR MERGECELL
                $baris = $baris - 1;
                $baris2 = $baris2 - 1;
                $baris3 = $baris3 - 1;
                $baris4 = $baris4 - 1;
                $baris9 = $baris9 - 1;
                $baris10 = $baris10 - 1;
                $baris11 = $baris11 - 1;
                $baris12 = $baris12 - 1;
                $baris13 = $baris13 - 1;
                $baris14 = $baris14 - 1;

                $excel->getActiveSheet()->mergeCells('A' . $baris . ':A' . $baris = $baris + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('B' . $baris2 . ':B' . $baris2 = $baris2 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('C' . $baris3 . ':C' . $baris3 = $baris3 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('D' . $baris4 . ':D' . $baris4 = $baris4 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('E' . $baris5 . ':H' . $baris5); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('E' . $baris6 . ':F' . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('G' . $baris8 . ':H' . $baris8); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('I' . $baris9 . ':I' . $baris9 = $baris9 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('J' . $baris10 . ':J' . $baris10 = $baris10 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('K' . $baris11 . ':K' . $baris11 = $baris11 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2

                //ATUR TYLE MERGECELL
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('G' . $baris8)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('I' . $baris9)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('J' . $baris10)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('K' . $baris11)->applyFromArray($style_col);

                $baris = $baris + 1;
            }

            if ($data['masa'] == 'Pagi') {
                $pagi = $data['presure'];
            } else if ($data['masa'] == 'Sore') {
                $sore = $data['presure'];
            } else if ($data['masa'] == 'Malam') {
                $malam = $data['presure'];
            } else {
                $siang = $data['presure'];
            }

            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris, 'P' . $data['kode_manometer']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['diameter'] . '"');
            $excel->getActiveSheet()->getStyle('C' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris, $data['manometer']);
            $excel->getActiveSheet()->getStyle('D' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $baris, $pagi);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $baris, $sore);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $baris, $siang);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $baris, $malam);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $baris, '');
            $excel->getActiveSheet()->getStyle('I' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $baris, '');
            $excel->getActiveSheet()->getStyle('J' . $baris)->getAlignment()->setWrapText(true);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $baris, '');
            $excel->getActiveSheet()->getStyle('K' . $baris)->getAlignment()->setWrapText(true);

            //set rowStyle
            $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $baris)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $baris)->applyFromArray($style_row);


            $baris = $baris + 1;

            if (count($laporan) == $i) {
                $baris = $baris + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Mengetahui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Menyetujui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Dibuat Oleh"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Kepala Cabang " . ucwords($data['kecamatan'])); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Kasi.Teknik Cabang " . ucwords($data['kecamatan'])); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Petugas Monitoring"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 4;
                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'STF', $data['nik'])->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'STF', $data['nik'])->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $baris = $baris + 1;
            }

            $pagi = "";
            $sore = "";
            $siang = "";
            $malam = "";
            $masaSebelumnya = $data['masa'];
            $tbSebelumnya = $data['tgl_dibaca'];
            $zonaSebelumnya = $data['zona'];
            $kecamatanSebelumnya = $data['kecamatan'];
            $nikSebelumnya = $data['nik'];
            $idAreaKerjaSebelumnya = $data['idareakerja'];
            $pembacaSebelumnya = $data['operator'];
            $halamanSelesai++;
            $i++;
            $mulai++;
            $no++;
        }
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set Autosize width kolom B
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10); // Set Autosize width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(10); // Set Autosize width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25); // Set Autosize width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true); // Set Autosize width kolom E
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true); // Set Autosize width kolom F
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(7); // Set Autosize width kolom G
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(8); // Set Autosize width kolom H
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(11); // Set Autosize width kolom I
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(8); // Set Autosize width kolom J
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(8); // Set Autosize width kolom J


        //ked dini
        // Set orientasi kertas jadi PORTRAIT
        $excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $excel->getActiveSheet()->getPageMargins()->setTop(0.7);
        $excel->getActiveSheet()->getPageMargins()->setRight(0.4);
        $excel->getActiveSheet()->getPageMargins()->setLeft(0.9);
        $excel->getActiveSheet()->getPageMargins()->setBottom(0.7);

        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle($periode);
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="LAPORAN' . $periode . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
        //akhir export excel    
        // Buat header tabel nya pada baris ke 6
    }

    public function rekapXls($periode)
    {
        include APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        $wilayah = $this->session->userdata('kodeWilayah'); // ambil wilayah berdasarkan session user login
        $nik = $this->session->userdata('nik');
        $role_id = $this->session->userdata('role_id');
        $zona = $this->session->userdata('kodeZona');
        $idjabatan = $this->session->userdata('idjabatan');
        $key = $this->input->get('keyword');

        $dataManoMeter = $this->LaporanXls_model->getDataManometer($periode, $key, $wilayah, $zona, $idjabatan, $nik);

        // Panggil class PHPExcel nya
        $excel = new PHPExcel();
        // Settingan awal fil excel
        $excel->getProperties()->setCreator('PERUMDA AMTS')
            ->setLastModifiedBy('PERUMDA AMTS')
            ->setTitle("REKAPITULASI DATA MONITORING TEKANAN AIR")
            ->setSubject("LAPORAN REKAPITULASI DATA MONITORING TEKANAN AIR")
            ->setDescription("LAPORAN REKAPITULASI DATA MONITORING TEKANAN AIRr")
            ->setKeywords("Manometer");
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $ttd = array(
            'font' => array('size' => 11), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            )
        );
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );
        //Atur baris pertama
        $a = 1;
        $no = 1;
        $baris = 1;
        $tbSebelumnya = "";
        $zonaSebelumnya = "";
        $kecamatanSebelumnya = "";
        $masaSebelumnya = "";
        $halamanSelesai = 0;
        $pembacaSebelumnya = "";
        $mulai = 0;
        $pagi = "";
        $sore = "";
        $siang = "";
        $malam = "";

        foreach ($dataManoMeter as $data) {
            $getPagi = $this->LaporanXls_model->getPagi($periode, $key, $wilayah, $data['zona'], $idjabatan, $nik)->result_array();
            $getSore = $this->LaporanXls_model->getSore($periode, $key, $wilayah, $data['zona'], $idjabatan, $nik)->result_array();
            $getSiang = $this->LaporanXls_model->getSiang($periode, $key, $wilayah, $data['zona'], $idjabatan, $nik)->result_array();
            $getMalam = $this->LaporanXls_model->getMalam($periode, $key, $wilayah, $data['zona'], $idjabatan, $nik)->result_array();
            $getSemuaMasa = $this->LaporanXls_model->getSemuaMasa($periode, $key, $wilayah, $data['zona'], $idjabatan, $nik)->num_rows();
            $i = 0;
            if (count($getPagi) > 0) {
                $i = $i + 1;
            }
            if (count($getSore) > 0) {
                $i = $i + 1;
            }
            if (count($getSiang) > 0) {
                $i = $i + 1;
            }
            if (count($getMalam) > 0) {
                $i = $i + 1;
            }

            if ($zonaSebelumnya != $data['zona']) {
                if ($mulai > 0) {
                    $baris = $baris + 2;
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Menyetujui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Mengetahui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Dibuat Oleh"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Kepala Cabang " . ucwords($kecamatanSebelumnya)); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Kasi.Teknik Cabang " . ucwords($kecamatanSebelumnya)); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Petugas Monitoring"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 4;
                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'STF', $nikSebelumnya)->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KC')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                    $ambilTTD = $this->LaporanXls_model->ambilTTD($idAreaKerjaSebelumnya, 'KS')->row_array();
                    $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                    $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);
                    $baris = $baris + 1;
                    $excel->getActiveSheet('')->setBreak('A' . $baris, PHPExcel_Worksheet::BREAK_ROW);
                    $baris = $baris + 1;
                    //disini ditambah pembaca sebelumnya
                }
                $no = 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "REKAPITULASI DATA MONITORING TEKANAN AIR"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':' . chr(ord('D') + $getSemuaMasa + $i + 3) . $baris); // Set Merge Cell pada kolom A1 sampai Q1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "PERUMDA AIR MINUM TIRTA SANJIWANI KABUPATEN GIANYAR"); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':' . chr(ord('D') + $getSemuaMasa + $i + 3) . $baris); // Set Merge Cell pada kolom A1 sampai Q1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "CABANG " . $data['kecamatan']); // Set kolom A1 dengan tulisan "DATA SISWA"
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':' . chr(ord('D') + $getSemuaMasa + $i + 3) . $baris); // Set Merge Cell pada kolom A1 sampai Q1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setBold(TRUE); // Set bold kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getFont()->setSize(11); // Set font size 15 untuk kolom A1
                $excel->getActiveSheet()->getStyle('A' . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "ZONA");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A sampai C
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris, $data['zona']);

                $baris = $baris + 1;
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "BULAN");
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A sampai C
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris, $this->libfunction->format_periode($periode));
                //THEAD DIMULAI
                $baris = $baris + 2;
                $baris2 = $baris;
                $baris3 = $baris2;
                $baris4 = $baris3;
                $baris5 = $baris4; //dari sini
                $baris6 = $baris5 + 1;
                $baris7 = $baris6 + 1;
                $baris8 = $baris5;
                $baris9 = $baris8;
                $baris10 = $baris9;
                $baris11 = $baris10;
                $baris12 = $baris11;
                $baris13 = $baris12;
                $baris14 = $baris13;

                //ATUR NILAI MASING-MASING THEAD
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, "NO");
                $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris2, "KODE MANO");
                $excel->getActiveSheet()->getStyle('B' . $baris2)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris3, "TAP PIPA");
                $excel->getActiveSheet()->getStyle('C' . $baris3)->getAlignment()->setWrapText(true);
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris4, "LOKASI MANOMETER");
                $excel->getActiveSheet()->getStyle('D' . $baris4)->getAlignment()->setWrapText(true);

                $column = "D";
                if ($getSemuaMasa > 0) {
                    $excel->setActiveSheetIndex(0)->setCellValue($columnMasaAwal = chr(ord($column) + 1) . $baris5, "TANGGAL BACAAN TEKANAN MANOMETER");
                    $excel->getActiveSheet()->getStyle(chr(ord($column) + 1) . $baris5)->getAlignment()->setWrapText(true);
                    $excel->getActiveSheet()->mergeCells(chr(ord($column) + 1) . $baris5 . ":" . $columnMasaAkhir = chr(ord($column) + $getSemuaMasa + $i) . $baris5); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle(chr(ord($column) + 1) . $baris5 . ":" . chr(ord($column) + $getSemuaMasa + $i) . $baris5)->applyFromArray($style_col);
                }

                if (count($getPagi) > 0) {
                    $excel->setActiveSheetIndex(0)->setCellValue($columnPagiAwal = chr(ord($column) + 1) . $baris6, "PAGI");
                    $excel->getActiveSheet()->mergeCells($column = chr(ord($column) + 1) . $baris6 . ":" . $columnPagiAkhir = chr(ord($column) + count($getPagi) + 1) . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris6 . ":" . chr(ord($column) + count($getPagi))  . $baris6)->applyFromArray($style_col);
                }

                if (count($getSore) > 0) {
                    $excel->setActiveSheetIndex(0)->setCellValue($columnSoreAwal = chr(ord($columnPagiAkhir) + 1) . $baris6, "SORE");
                    $excel->getActiveSheet()->mergeCells($column = chr(ord($columnPagiAkhir) + 1) . $baris6 . ":" . $columnSoreAkhir = chr(ord($columnPagiAkhir) + count($getSore) + 1) . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle(chr(ord($columnPagiAkhir) + 1) . $baris6 . ":" . chr(ord($columnPagiAkhir) + count($getSore) + 1)  . $baris6)->applyFromArray($style_col);
                }

                if (count($getSiang) > 0) {
                    $excel->setActiveSheetIndex(0)->setCellValue($columnSiangAwal = chr(ord($columnSoreAkhir) + 1) . $baris6, "SIANG");
                    $excel->getActiveSheet()->mergeCells($column = chr(ord($columnSoreAkhir) + 1) . $baris6 . ":" . $columnSiangAkhir = chr(ord($columnSoreAkhir) + count($getSiang) + 1) . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle(chr(ord($columnSoreAkhir) + 1) . $baris6 . ":" . chr(ord($columnSoreAkhir) + count($getSiang) + 1)  . $baris6)->applyFromArray($style_col);
                }

                if (count($getMalam) > 0) {
                    $excel->setActiveSheetIndex(0)->setCellValue($columnMalamAwal = chr(ord($columnSiangAkhir) + 1) . $baris6, "MALAM");
                    $excel->getActiveSheet()->mergeCells($column = chr(ord($columnSiangAkhir) + 1) . $baris6 . ":" . $columnMalamAkhir = chr(ord($columnSiangAkhir) + count($getSiang) + 1) . $baris6); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                    $excel->getActiveSheet()->getStyle(chr(ord($columnSiangAkhir) + 1) . $baris6 . ":" . chr(ord($columnSiangAkhir) + count($getSiang) + 1)  . $baris6)->applyFromArray($style_col);
                }

                $column = "D";
                if (count($getPagi) > 0) {
                    foreach ($getPagi as $row) {
                        $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "Tgl " . $row['tgl_dibaca']);
                        $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "RERATA");
                    $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                }

                if (count($getSore) > 0) {
                    foreach ($getSore as $row) {
                        $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "Tgl " . $row['tgl_dibaca']);
                        $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "RERATA");
                    $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                }

                if (count($getSiang) > 0) {
                    foreach ($getSiang as $row) {
                        $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "Tgl" . $row['tgl_dibaca']);
                        $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "RERATA");
                    $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                }

                if (count($getMalam) > 0) {
                    foreach ($getMalam as $row) {
                        $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "Tgl" . $row['tgl_dibaca']);
                        $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris7, "RERATA");
                    $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris7)->applyFromArray($style_col);
                }

                $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris8, "INDIKATOR PEL");
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8)->getAlignment()->setWrapText(true);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8 = $baris8 + 1)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8 = $baris8 + 1)->applyFromArray($style_col);
                $excel->getActiveSheet()->getColumnDimension(chr(ord($column)))->setWidth(10); // Set Autosize width kolom B


                $baris8 = $baris8 - 2;
                $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris8, "JUMLAH SR");
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8)->getAlignment()->setWrapText(true);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8 = $baris8 + 1)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8 = $baris8 + 1)->applyFromArray($style_col);

                $baris8 = $baris8 - 2;
                $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris8, "KET");
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8 = $baris8 + 1)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris8 = $baris8 + 1)->applyFromArray($style_col);

                //APPLY STYLE MASING2 KOLOM
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);



                //ATUR STYLE MERGECELL
                $baris = $baris + 1;
                $baris2 = $baris2 + 1;
                $baris3 = $baris3 + 1;
                $baris4 = $baris4 + 1;


                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);



                //ATUR MERGECELL
                $baris = $baris - 1;
                $baris2 = $baris2 - 1;
                $baris3 = $baris3 - 1;
                $baris4 = $baris4 - 1;


                $excel->getActiveSheet()->mergeCells('A' . $baris . ':A' . $baris = $baris + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('B' . $baris2 . ':B' . $baris2 = $baris2 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('C' . $baris3 . ':C' . $baris3 = $baris3 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->mergeCells('D' . $baris4 . ':D' . $baris4 = $baris4 + 2); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2

                //ATUR TYLE MERGECELL
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('B' . $baris2)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('C' . $baris3)->applyFromArray($style_col);
                $excel->getActiveSheet()->getStyle('D' . $baris4)->applyFromArray($style_col);

                $baris = $baris + 1;
            }
            // SET COLUMN A
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $baris, $no);
            $excel->getActiveSheet(0)->getStyle('A' . $baris)->applyFromArray($style_row);
            //SET COLUMN B
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $baris, 'P' . $data['kode_manometer']);
            $excel->getActiveSheet(0)->getStyle('B' . $baris)->applyFromArray($style_row);
            //SET COLUMN C
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $baris, $data['diameter'] . '"');
            $excel->getActiveSheet()->getStyle('C' . $baris)->getAlignment()->setWrapText(true);
            $excel->getActiveSheet(0)->getStyle('C' . $baris)->applyFromArray($style_row);
            //SET COLUMN D
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $baris, $data['manometer']);
            $excel->getActiveSheet()->getStyle('D' . $baris)->getAlignment()->setWrapText(true);
            $excel->getActiveSheet(0)->getStyle('D' . $baris)->applyFromArray($style_row);


            $column = "D";
            if (count($getPagi) > 0) {
                foreach ($getPagi as $row) {
                    $masa = $row['masa'];
                    $presure = $this->LaporanXls_model->getPresure($data['id_manometer'], $row['masa'], $row['tgl_dibaca'], $periode)->result_array();
                    $tekanan = "";
                    if (count($presure) > 0) {
                        $xy = 1;
                        foreach ($presure as $row) {
                            $tekanan = $tekanan . $row['presure'];
                            if (count($presure) > 1) {
                                if (count($presure) != $xy) {
                                    $tekanan = $tekanan . " , ";
                                }
                            }
                            $xy++;
                        }
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $tekanan);
                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                    $excel->getActiveSheet()->getColumnDimension(chr(ord($column)))->setAutoSize(true); // Set Autosize width kolom B

                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
                $rerata = $this->LaporanXls_model->getRerata($data['id_manometer'], $masa, $periode)->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $rerata['rerata_presure']);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }


            if (count($getSore) > 0) {
                foreach ($getSore as $row) {
                    $masa = $row['masa'];
                    $presure = $this->LaporanXls_model->getPresure($data['id_manometer'], $row['masa'], $row['tgl_dibaca'], $periode)->result_array();
                    $tekanan = "";
                    if (count($presure) > 0) {
                        foreach ($presure as $row) {
                            $xy = 1;
                            $tekanan = $tekanan . $row['presure'];
                            if (count($presure) > 1) {
                                if (count($presure) != $xy) {
                                    $tekanan = $tekanan . " , ";
                                }
                            }
                            $xy++;
                        }
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $tekanan);
                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
                $rerata = $this->LaporanXls_model->getRerata($data['id_manometer'], $masa, $periode)->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $rerata['rerata_presure']);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }


            if (count($getSiang) > 0) {
                foreach ($getSiang as $row) {
                    $masa = $row['masa'];
                    $presure = $this->LaporanXls_model->getPresure($data['id_manometer'], $row['masa'], $row['tgl_dibaca'], $periode)->result_array();
                    $tekanan = "";
                    if (count($presure) > 0) {
                        foreach ($presure as $row) {
                            $xy = 1;
                            $tekanan = $tekanan . $row['presure'];
                            if (count($presure) > 1) {
                                if (count($presure) != $xy) {
                                    $tekanan = $tekanan . " , ";
                                }
                            }
                            $xy++;
                        }
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $tekanan);
                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
                $rerata = $this->LaporanXls_model->getRerata($data['id_manometer'], $masa, $periode)->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $rerata['rerata_presure']);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }


            if (count($getMalam) > 0) {
                foreach ($getMalam as $row) {
                    $masa = $row['masa'];
                    $presure = $this->LaporanXls_model->getPresure($data['id_manometer'], $row['masa'], $row['tgl_dibaca'], $periode)->result_array();
                    $tekanan = "";
                    if (count($presure) > 0) {
                        foreach ($presure as $row) {
                            $xy = 1;
                            $tekanan = $tekanan . $row['presure'];
                            if (count($presure) > 1) {
                                if (count($presure) != $xy) {
                                    $tekanan = $tekanan . " , ";
                                }
                            }
                            $xy++;
                        }
                    }
                    $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $tekanan);
                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                    $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
                $rerata = $this->LaporanXls_model->getRerata($data['id_manometer'], $masa, $periode)->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, $rerata['rerata_presure']);
                $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);
                $excel->getActiveSheet(0)->getStyle(chr(ord($column)) . $baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

            $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, " ");
            $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);

            $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, " ");
            $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);

            $excel->setActiveSheetIndex(0)->setCellValue($column = chr(ord($column) + 1) . $baris, " ");
            $excel->getActiveSheet()->getStyle(chr(ord($column)) . $baris)->applyFromArray($style_row);



            $baris = $baris + 1;

            if (count($dataManoMeter) == $a) {
                $baris = $baris + 2;
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Menyetujui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Mengetahui"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Dibuat Oleh"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "Kepala Cabang " . ucwords($data['kecamatan'])); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "Kasi.Teknik Cabang " . ucwords($data['kecamatan'])); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "Petugas Monitoring"); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 4;
                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'STF', $data['nik'])->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, $ambilTTD['nama']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, $ambilTTD['pangkat']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'STF', $data['nik'])->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('G'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('G' . $baris . ':J' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('G' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KC')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('A'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('A' . $baris . ':C' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('A' . $baris)->applyFromArray($ttd);

                $ambilTTD = $this->LaporanXls_model->ambilTTD($data['idareakerja'], 'KS')->row_array();
                $excel->setActiveSheetIndex(0)->setCellValue('D'  . $baris, "NIK : " . $ambilTTD['nik']); // Set kolom A1 dengan tulisan "DATA SISWA"    
                $excel->getActiveSheet()->mergeCells('D' . $baris . ':F' . $baris); // Set Merge Cell pada kolom A BARIS sampai A BARIS+2
                $excel->getActiveSheet()->getStyle('D' . $baris)->applyFromArray($ttd);
                $baris = $baris + 1;
            }

            $pagi = "";
            $sore = "";
            $malam = "";
            $masaSebelumnya = $data['masa'];
            $tbSebelumnya = $data['tgl_dibaca'];
            $zonaSebelumnya = $data['zona'];
            $nikSebelumnya = $data['nik'];
            $idAreaKerjaSebelumnya = $data['idareakerja'];
            $kecamatanSebelumnya = $data['kecamatan'];
            $pembacaSebelumnya = $data['operator'];
            $halamanSelesai++;
            $a++;
            $mulai++;
            $no++;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set Autosize width kolom B
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10); // Set Autosize width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(5); // Set Autosize width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set Autosize width kolom D
        // $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true); // Set Autosize width kolom E


        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file SHEET excel nya
        $excel->getActiveSheet(0)->setTitle($periode);
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="REKAPITULASI' . $periode . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
        //akhir export excel    
        // Buat header tabel nya pada baris ke 6

    }
}
