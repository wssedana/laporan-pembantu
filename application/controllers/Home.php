<?php
defined('BASEPATH') or exit('No direct script access allowed');

class posting extends CI_Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Makassar');
        parent::__construct();
        //nama function helpers yg kita buat, nama ini bisa disesuaikan dgn keinginan.
    }
    public function index()
    {
        echo "Hallo world";
    }
}
