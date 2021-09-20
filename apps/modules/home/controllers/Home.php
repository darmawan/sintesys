<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        /*
         * pengecekan session user login, jika belum login maka arahkan ke halaman login         
         */
        $this->load->library('html_dom');
        (!isset($_SESSION['bhs'])) ? 1 : $_SESSION['bhs'];
    }

    public function index()
    {
        /*
         * array konten yang harus diisi sbb:
         * smenu = menu aktif
         * submenu = submenu aktif, jika merupakan sub dari menu induk
         * css = css tambahan
         * js = js tambahan
         * header = blok header
         * tujuan = blok konten
         * footer = blok footer
         * 
         */
        $konten['smenu'] = '';
        $konten['sbmenu'] = '';
        $konten['css'] = 'css';
        $konten['js'] = 'js';
        $konten['header'] = TEMPLATE . 'header';
        $konten['footer'] = TEMPLATE . 'footer';
        $konten['target'] = TEMPLATE . 'home';
        $konten['bhs'] = (!isset($_SESSION['bhs'])) ? 1 : $_SESSION['bhs'];

        $this->load->view(TEMPLATE . 'index', $konten);
    }

    public function bahasa()
    {
        $_SESSION['bhs'] = $this->input->post('ling');
        // ($_SESSION['bhs'] == 1) ? 2 : 1;
    }
}

/* End of file home.php */
/* Location: ./application/modules/home/controllers/home.php */