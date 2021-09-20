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

    public function daptar() {
        if (IS_AJAX) {
            $bahasa = 1; //$this->config->item('lang_uri_abbr')[$this->uri->segment(1)]['id'];
            /*
             * Loading library Pagination
             */

            $query = "(SELECT * FROM (".$this->libglobal->kueriProject().") as hafidz ) as betmen"; 
            $limit_per_page = 9;
            $this->load->library('pagination');
            $config['base_url'] = base_url('project/daptar');
            $config['total_rows'] = $this->Data_model->getTotalData($query,array('is_published'=>1)); //get_article_list_count($bahasa, 2);
            $config['per_page'] = $limit_per_page;
            $config['uri_segment'] = 3;
            $config['num_links'] = 10;
            $page_num = $config['total_rows'] / $config['per_page'];
            $config['num_links'] = round($page_num);
            $config['next_link'] = '<i class="fa fa-arrow-right"></i>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="fa fa-arrow-left"></i>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active disabled"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            $current_page = ($this->uri->segment(3)) ? (int) $this->uri->segment(3) : 0;
            /*
             * get list 
             */
            $list = $this->Data_model->getList($limit_per_page, $current_page, array('is_published'=>1), 'publish_date', 'DESC', $query);
            $data['bahasa'] = 1; 
            $data['links'] = $this->pagination->create_links();
            $data['project_list'] = $list;
            $this->load->view('paging_project', array('data' => $data));
        }
    }

    public function muka() {
        $kode = hex2bin($this->uri->segment(2));
        $bahasa = 1; 
        $judul = $this->uri->segment(3);
        $artikel = $this->O_AM->get_article($kode, $bahasa);
        $catart = $this->O_AM->get_article_category($kode);
        $img_konten = $this->O_AM->getImgArticle($kode);
        $arrkat = explode(" ", $artikel->tags);


        if ($artikel && isset($artikel)) {
            if ($judul != generate_title_to_url($artikel->article_title)) {
                redirect(base_url() . $this->uri->segment(1) . '/' . bin2hex($artikel->article_id) . '/' . generate_title_to_url($artikel->article_title) . '/' . $artikel->type_id);
            }
        } else {
            redirect(base_url() . '/d/' . $this->uri->segment(4));
        }


        $this->load->library('user_agent');
        $ip = $this->input->ip_address();
        $agent = $this->agent->agent_string();
        $tm = explode('/', $_SERVER["REQUEST_URI"]);
        $idata['xdata'] = array(
            'counter' => 0,
            'reffid' => $artikel->article_id,
            'lang_id' => 1,
            'url' => $_SERVER["REQUEST_URI"],
            'user_agent' => $agent,
            'ip' => $ip,
            'description' => 'artikel'
        );

        $konten['smenu'] = $kode;
        $konten['content'] = $artikel;
        $konten['catart'] = $catart;
        $konten['judul'] = $judul;
        $konten['kategori'] = $artikel->cat_name;
        $konten['img_konten'] = $img_konten;

        $konten['css'] = '';
        $konten['js'] = '';
        $konten['header'] = TEMPLATE . 'header';
        $konten['footer'] = TEMPLATE . 'footer';


        if ($artikel) {
            switch ($artikel->type_id) {
                case 3:
                    $konten['target'] = TEMPLATE . 'about_konten';
                    break;
                case 10:
                    $konten['target'] = TEMPLATE . 'service_konten';
                    break;
                default:
                    $konten['target'] = TEMPLATE . 'artikel_konten';
                    break;
            }
        }

        $this->load->view(TEMPLATE . 'index', $konten);
    }

    function getAjaxArtikel() {
        $artikel = $this->O_AM->get_article($this->input->post('kd'), $this->config->item('language_id'));
        $catart = $this->O_AM->get_article_category($this->input->post('kd'));
        $data['artikel'] = $artikel;
        $data['catart'] = $catart;
        $this->load->view('module/ajaxArticle', $data);
    }

    function getModul() {
        $this->load->view(MOD_DIR . 'modul');
    }

    function artikelReveal() {
        $konten['rowdata'] = $this->O_AM->get_article($this->uri->segment(3));
        $konten['rowimg'] = $this->O_AM->getImgArticle($this->uri->segment(3));

        $this->load->view(MOD_DIR . 'artikel_reveal', $konten);
    }

}

/* End of file feature.php */
/* Location: ./application/modules/controllers/feature.php */