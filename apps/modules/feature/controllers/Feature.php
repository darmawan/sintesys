<?php
/*
 * Modeul Feature
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feature extends MX_Controller {

    function __construct() {
        //session_start();
        parent::__construct();
        $this->load->library('html_dom');
        (!isset($_SESSION['bhs'])) ? 1 : $_SESSION['bhs'];
    }

    public function index() {
        $konten['tipe'] = 'feature';
        $konten['smenu'] = '';
        $konten['sbmenu'] = '';
        $konten['css'] = 'css';
        $konten['js'] = 'js';
        $konten['header'] = TEMPLATE . 'header';
        $konten['footer'] = TEMPLATE . 'footer';
        $konten['target'] = TEMPLATE. 'feature';
        $konten['aep'] = 'feature_list';
        $konten['bhs'] = (!isset($_SESSION['bhs'])) ? 1 : $_SESSION['bhs'];
        $this->load->view(TEMPLATE . 'index', $konten);
    }

}

/* End of file feature.php */
/* Location: ./application/modules/controllers/feature.php */