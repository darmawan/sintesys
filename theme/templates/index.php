<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * susunan template (disesuaikan dengan theme html yang digunakan)
 * header   [bersifat wajib] menampung html head - body - dan tambahan
 * topbar   [bersifat opsional] menampung informasi atas (tergantung theme html yang digunakan)
 * navbar   [bersifat opsional] menampung navigasi menu (tergantung theme html yang digunakan)
 * $target  [bersifat wajib] menampung konten yang selalu berubah ubah
 * footer   [bersifat wajib] menampung footer dan pemanggilan js (lokasi pemanggilan js di footer, jika diheader maka alihkan ke header)
 */

$data['js'] = $js;
$data['css'] = $css;

$this->load->view(TEMPLATE . '/header', $data);
$this->load->view(TEMPLATE . '/topbar');
// $this->load->view(TEMPLATE . '/navbar');
$this->load->view($target);
$this->load->view(TEMPLATE . '/footer', $data);
?>