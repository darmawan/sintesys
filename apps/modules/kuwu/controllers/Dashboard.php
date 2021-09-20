<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MX_Controller {

    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
            redirect('../kuwu/login');
        }
    }

    function index() {
        /*
         * array konten yang harus diisi sbb:
         * smenu = menu aktif
         * submenu = submenu aktif, jika merupakan sub dari menu induk
         * css = css tambahan
         * js = js tambahan
         * header = blok header
         * tujuan = blok konten
         * footer = blok footer
         * halaman = nama file halaman yang akan dituju
         */
        $halaman = 'dashboard';
        $konten['smenu'] = 'dashboard';
        $konten['sbmenu'] = '';
        $konten['css'] = 'dashboard/css';
        $konten['js'] = 'dashboard/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'dashboard/index';
        $this->load->view('default_admin', $konten);
    }    
}
