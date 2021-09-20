<?php

/*
 * goa = Go Artikel
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Goa extends MX_Controller
{

    public function __construct()
    {
        //session_start();
        parent::__construct();
        $this->load->library('html_dom');
        $_SESSION['bhs'] = $_SESSION['bhs'];
    }

    public function index()
    {
        redirect('../home');
        //        $cek = $this->Data_model->satuData(DB_TYPE, array('type_id' => $this->uri->segment(2)));
        //        $konten['tipe'] = $cek->type_grp;
        //        $konten['smenu'] = '';
        //        $konten['sbmenu'] = '';
        //        $konten['css'] = 'css';
        //        $konten['js'] = 'js';
        //        $konten['header'] = TEMPLATE . 'header';
        //        $konten['footer'] = TEMPLATE . 'footer';
        //        $konten['target'] = 'project_list';
        //        $konten['aep'] = 'project_list';
        //        $this->load->view(TEMPLATE . 'index', $konten);
    }

    public function muka()
    {
        /* trnsofrmasi kode artikel yang berupa hexadesimal ke binari
          $t =  bin2hex($this->uri->segment(3));
          echo $t;
          echo '<br>';
          echo hex2bin ($t);
          echo $this->uri->segment(1);
          die;
         */
        $this->load->library('user_agent');
        $kode = hex2bin($this->uri->segment(2));
        $bahasa = 1; //$this->config->item('lang_uri_abbr')[$this->uri->segment(1)]['id'];
        $judul = $this->uri->segment(3);
        $katip = $this->uri->segment(4);
        if ($this->uri->segment(1) == 'p') {
            $artikel = $this->Data_model->satuData(DB_PROJECT, array('project_id' => $kode));
            $catart = $this->Data_model->satuData(DB_TYPE, array('type_id' => $katip));
            $img_konten = $this->Data_model->satuData(DB_IMAGE, array('reffid' => $kode));
            $arrkat = $catart->type_name;

            if ($artikel && isset($artikel)) {
                if ($judul != generate_title_to_url($artikel->project_title)) {
                    redirect(base_url() . $this->uri->segment(1) . '/' . bin2hex($artikel->project_id) . '/' . generate_title_to_url($artikel->project_title) . '/' . $artikel->type_id);
                }
            } else {
                redirect(base_url() . '/p/' . $this->uri->segment(4));
            }

            $ip = $this->input->ip_address();
            $agent = $this->agent->agent_string();
            $tm = explode('/', $_SERVER["REQUEST_URI"]);
            $idata['xdata'] = array(
                'counter' => 0,
                'reffid' => $artikel->project_id,
                'lang_id' => 1,
                'url' => $_SERVER["REQUEST_URI"],
                'user_agent' => $agent,
                'ip' => $ip,
                'description' => 'project',
            );
        } else {
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
                'description' => 'artikel',
            );
        }

        // $this->load->model('log_model', 'O_LM');
        // $this->O_LM->insert_log($idata, 1, $_SERVER["REQUEST_URI"], $ip, TBL_ARTICLE, $artikel->article_id);

        $konten['smenu'] = $kode;
        $konten['content'] = $artikel;
        $konten['catart'] = $catart;
        $konten['judul'] = $judul;
        $konten['kategori'] = $artikel->tags;
        $konten['img_konten'] = $img_konten;

        $konten['css'] = '';
        $konten['js'] = '';
        $konten['header'] = TEMPLATE . 'header';
        $konten['footer'] = TEMPLATE . 'footer';

        if ($artikel) {
            if ($this->uri->segment(1) == 'p') {
                $konten['target'] = TEMPLATE . 'project_konten';
            } else {
                switch ($artikel->type_id) {
                    case 3:
                        $konten['target'] = TEMPLATE . 'about_konten';
                        break;
                    case 10:
                    case 15:
                    case 16:
                        $konten['target'] = TEMPLATE . 'service_konten';
                        break;
                    default:
                        $konten['target'] = TEMPLATE . 'artikel_konten';
                        break;
                }
            }
        }

        //        if($artikel && $artikel->type_id==3) {
        //            $konten['target'] = TEMPLATE.'about_konten';
        //        }else{
        //            $konten['target'] = TEMPLATE.'artikel_konten';
        //        }

        $this->load->view(TEMPLATE . 'index', $konten);
    }

    public function artikel()
    {
        $masalah = false;
        $this->load->library('user_agent');
        $bahasa = (!isset($_SESSION['bhs'])) ? 1 : $_SESSION['bhs'];
        $kode = hex2bin($this->uri->segment(2));
        $judul = $this->uri->segment(3);

        if ($this->uri->segment(4) == 'sintesys') {
            $artikel = $this->Data_model->satuData(DB_ARTICLE, array('lower(article_title)' => str_replace("_", " ", $judul), 'lang_id' => $bahasa));
            if ($artikel) {
                $catart = $this->Data_model->satuData(DB_TYPE, array('type_id' => $artikel->type_id));
                $img_konten = $this->Data_model->satuData(DB_IMAGE, array('reffid' => $artikel->article_id));
            }
        } else {
            try {
                $artikel = $this->Data_model->satuData(DB_ARTICLE, array('article_id' => $kode, 'lang_id' => $bahasa));
                // echo $this->db->last_query();
                $catart = $this->Data_model->satuData(DB_TYPE, array('type_id' => $artikel->type_id));
                $img_konten = $this->Data_model->satuData(DB_IMAGE, array('reffid' => $kode));
            } catch (\Throwable $th) {
                $masalah = true;
            }
        }

        if ($artikel && isset($artikel)) {
            if ($judul != generate_title_to_url($artikel->article_title)) {
                redirect(base_url() . $this->uri->segment(1) . '/' . bin2hex($artikel->article_id) . '/' . generate_title_to_url($artikel->article_title));
            }
        } else {
            // echo str_replace('index.php/','', current_url());
            if ($masalah == true) {
                // print_r(base_url() . $this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/' . $this->uri->segment(4));
            } else {
                redirect(base_url() . 'a/' . $this->uri->segment(4));
            }
        }

        //        $this->libglobal->simpanKeLog($artikel->article_id, DB_ARTICLE, 'article_id', 'layanan');

        $konten['smenu'] = $kode;
        $konten['content'] = $artikel;
        $konten['catart'] = (isset($catart)) ? $catart : '';
        $konten['judul'] = $judul;
        $konten['kategori'] = (isset($artikel->tags)) ? $artikel->tags : '';
        $konten['img_konten'] = (isset($img_konten)) ? $img_konten : '';
        $konten['tujuan'] = '';
        $konten['css'] = '';
        $konten['js'] = '';
        $konten['header'] = TEMPLATE . 'header';
        $konten['footer'] = TEMPLATE . 'footer';
        if (isset($artikel->type_id)) {
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
        } else {
            $konten['target'] = TEMPLATE . 'artikel_konten';
        }
        $this->load->view(TEMPLATE . 'index', $konten);
    }

    public function feature()
    {
        $this->load->library('user_agent');
        $tujuan = $this->uri->segment(1);
        $kode = hex2bin($this->uri->segment(2));
        $judul = $this->uri->segment(3);
        $query = "(" . $this->libglobal->kueriProject() . ") as hafidz ";
        $artikel = $this->Data_model->satuData($query, array('project_id' => $kode));
        //        $catart = $this->Data_model->satuData(DB_TYPE, array('type_id' => $artikel->type_id));
        $img_konten = $this->Data_model->satuData(DB_IMAGE, array('refftype' => 'project', 'reffid' => $kode));
        //        echo $this->db->last_query();
        //        print_r($img_konten);
        //        die;

        if ($artikel && isset($artikel)) {
            if ($judul != generate_title_to_url($artikel->project_title)) {
                redirect(base_url() . $this->uri->segment(1) . '/' . bin2hex($artikel->project_id) . '/' . generate_title_to_url($artikel->project_title));
            }
        } else {
            redirect(base_url() . '/p/' . $this->uri->segment(4));
        }

        //        $this->libglobal->simpanKeLog($artikel->project_id, DB_PROJECT, 'project_id', 'project detil');

        $konten['smenu'] = $kode;
        $konten['content'] = $artikel;
        $konten['catart'] = $artikel->product_title;
        $konten['judul'] = $judul;
        $konten['kategori'] = $artikel->category_name;
        $konten['img_konten'] = $img_konten;
        $konten['tujuan'] = $tujuan;
        $konten['css'] = '';
        $konten['js'] = '';
        $konten['header'] = TEMPLATE . 'header';
        $konten['footer'] = TEMPLATE . 'footer';

        if ($tujuan == 'pa') {
            $konten['target'] = TEMPLATE . 'service_konten';
        } else {
            $konten['target'] = TEMPLATE . 'feature_konten';
        }


        $this->load->view(TEMPLATE . 'index', $konten);
    }

    public function contact()
    {
        $konten['smenu'] = '';
        $konten['catart'] = '';
        $konten['judul'] = '';
        $konten['kategori'] = '';
        $konten['img_konten'] = '';
        $konten['tujuan'] = '';
        $konten['css'] = '';
        $konten['js'] = 'mapjs';
        $konten['header'] = TEMPLATE . 'header';
        $konten['footer'] = TEMPLATE . 'footer';
        $konten['target'] = TEMPLATE . 'contact_konten';
        $this->load->view(TEMPLATE . 'index', $konten);
    }

    public function getAjaxArtikel()
    {
        // $artikel = $this->O_AM->get_article($this->input->post('kd'), $this->config->item('language_id'));
        $artikel = $this->O_AM->get_article($this->input->post('kd'), $_SESSION['bhs']);
        $catart = $this->O_AM->get_article_category($this->input->post('kd'));
        $data['artikel'] = $artikel;
        $data['catart'] = $catart;
        $this->load->view('module/ajaxArticle', $data);
    }
}

/* End of file goa.php */
/* Location: ./application/modules/controllers/goa.php */
