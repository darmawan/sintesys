<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
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
         */
        /*
         * pengecekan session user login, jika belum login maka arahkan ke halaman login
         */
        if (isset($_SESSION['username'])) {
            redirect('../kuwu/dashboard');
        }
        $konten['smenu']   = '';
        $konten['sbmenu']  = '';
        $konten['css']     = 'css';
        $konten['js']      = 'js';
        $konten['bodycls'] = 'login-content';
        $konten['target']  = '';
        $this->load->view('login/index', $konten);
    }

    public function doLogin()
    {
        if (IS_AJAX) {
            $res = $this->User_model->verify_user($this->input->post('email'), md5($this->input->post('password')));
//            $res = $this->Data_model->verify_user(array('email' => $this->input->post('email'), 'password' => md5($this->input->post('password'))), 'vpengguna');

            if ($res !== false) {
                foreach ($res as $row => $kolom) {
                    $_SESSION[$row] = $kolom;
                }

                $this->cekTblProfil($_SESSION['user_id']);

                $ph = $this->Data_model->satuData('ad_profil', array('kode_user' => $_SESSION['user_id']));
                if ($ph) {
                    foreach ($ph as $rows => $koloms) {
                        $_SESSION[$rows] = $koloms;
                    }

                }
                // if (!isset($_SESSION['username'])) {
                //     $_SESSION['username'] = $_SESSION['first_name'];
                //     $_SESSION['kode'] = $_SESSION['user_id'];
                // } else {
                //     if ($_SESSION['username'] == '') {
                //         $_SESSION['username'] = $_SESSION['first_name'];
                //         $_SESSION['kode'] = $_SESSION['user_id'];
                //     }
                // }
                

                $_SESSION['wdp'] = $this->input->post('password');
                echo base_url('kuwu/dashboard');
            } else {
                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Password/Email tidak ditemukan/salah.';
            }
        }
    }

    public function logout()
    {        
        session_unset();
        session_destroy();
        redirect("kuwu");
        // $this->index();
    }

    public function cekTblProfil($kode)
    {
        if ($this->Data_model->cekData(DB_PROFIL, array('kode_user' => $kode)) == 0) {
            $arrProfil['kode_user'] =  $_SESSION['user_id'];
            $arrProfil['nama_lengkap'] =  $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
            $arrProfil['email'] =  $_SESSION['email'];

            // $arrProfil = (
            //     'kode_user'    => $_SESSION['user_id'],
            //     'nama_lengkap' => $_SESSION['first_name'] . ' ' . $_SESSION['last_name'],
            //     'email'        => $_SESSION['email']
            // )
            $this->Data_model->simpanData($arrProfil, DB_PROFIL);
        }
    }

}
