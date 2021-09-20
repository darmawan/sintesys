<?php

/*
 * nama class: Class Slider
 * fungsi: mengatur semua data artikel dan kategori artikel
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Slider extends MX_Controller {

    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('../kuwu/login');
        }
        $this->load->helper('formulir');
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
        $konten['tabel'] = DB_SLIDER;
        $konten['kolom'] = array("Kode", "Judul", "Photo", "Urutan", "Status", "Tanggal", "");
        $konten['breadcum'] = 'Slider';
        $konten['smenu'] = 'slider';
        $konten['sbmenu'] = 'slider';
        $konten['css'] = 'slider/css';
        $konten['js'] = 'slider/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'slider/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    public function sumber() {
        if (IS_AJAX) {
            $approvecat = $this->uri->segment(4);
            $whereapprove = "";
            switch ($approvecat):
                case 'editor':
                    $whereapprove = " AND moderator_approval=0 AND is_published=0 ";
                    $kolom = "editor_approval";
                    $kolomplus = "moderator_approval,is_published";
                    $arrplus1 = "moderator_approval";
                    $arrplus2 = "is_published";
                    break;
                case 'moderator':
                    $whereapprove = " AND editor_approval=1 AND is_published=0 ";
                    $kolom = "moderator_approval";
                    $kolomplus = "editor_approval,is_published";
                    $arrplus1 = "editor_approval";
                    $arrplus2 = "is_published";
                    break;
                case 'publisher':
                    $whereapprove = " AND editor_approval=1 AND moderator_approval=1 AND is_published=0 ";
                    $kolom = "is_published";
                    $kolomplus = "editor_approval,moderator_approval";
                    $arrplus1 = "editor_approval";
                    $arrplus2 = "moderator_approval";
                    break;
                default :
                    $whereapprove = ""; //" AND is_published=1 ";
                    $kolom = "is_published";
                    $kolomplus = "editor_approval,moderator_approval";
                    $arrplus1 = "editor_approval";
                    $arrplus2 = "moderator_approval";
                    break;
            endswitch;

            $sIndexColumn = '';
            $sIndexColumn = "slider_id";
            $sTable = DB_SLIDER;
            $xTable = '';
            $where = '';

            $aColumns = array("slider_id", "slider_text", "image", "urutan", "$kolom", "date_created", "lang_id");            // print_r($aColumns);
            $where = "";
            $query = "SELECT
                            slider_id ,article_id ,cat_id ,type_id ,lang_id ,slider_text ,image ,editor_approval ,moderator_approval ,is_published ,published_date ,active ,user_created ,date_created ,user_modified ,date_modified, urutan  
                        FROM
                              $sTable WHERE 1=1 $where   ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1 $whereapprove ";
            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $xTable);
        }
    }

    public function form() {
        $konten['aep'] = $this->uri->segment(5);
        if ($this->uri->segment(5) <> '') {
            $konten['bhs'] = $this->uri->segment(6);
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = ' WHERE slider_id = ' . $this->uri->segment(5);
            $where = '';
            $query = "SELECT * FROM " . DB_SLIDER . " $kondisi ";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('slider/form', $konten);
    }

    function simpanData() {
        $image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
        $basepath = APPPATH;
        $stringreplace = str_replace("apps", "publik", $basepath);
        $typeimg = explode("/", $_FILES['image']['type']);
        $new_file_tmp = generate_title_to_url($image);
        $new_file_name = ($image == '') ? '' : str_replace($typeimg[1], '', $new_file_tmp) . '.' . $typeimg[1];

        foreach ($this->input->post() as $key => $value) {
            if (is_array($value)) {
                
            } else {
                $subject = strtolower($key);
                $pattern = '/tgl/i';
                switch ($key):
                    case '_imgnm':
                        break;
                    case 'cid':
                        $cid = $value;
                        break;
                    case 'status_slider':
                        $status = $value;
                        break;
                    case 'published_date':
                        if (strlen(trim($value)) > 0) {
                            $tgl = explode("-", $value);
                            $newtgl = $tgl[1] . "-" . $tgl[0] . "-" . $tgl[2];
                            $time = strtotime($newtgl);
                            $arrtmp[$key] = $value; // date('Y-m-d', $time);
                        } else {
                            $arrtmp[$key] = NULL;
                        }
                        break;
                    default :
                        if (preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE)) {
                            if (strlen(trim($value)) > 0) {
                                $tgl = explode("-", $value);
                                $newtgl = $tgl[2] . "/" . $tgl[1] . "/" . $tgl[0];
                                $time = strtotime($newtgl);
                                $arrdata[$key] = date('Y-m-d', $time);
                            } else {
                                $arrdata[$key] = NULL;
                            }
                        } else {
                            $arrdata[$key] = $value;
                        }
                        break;
                endswitch;
            }
        }
        switch ($status) {
            case '0':
                $arrdata['editor_approval'] = '0';
                $arrdata['moderator_approval'] = '0';
                $arrdata['is_published'] = '0';
                break;
            case '1':
                $arrdata['editor_approval'] = '1';
                $arrdata['moderator_approval'] = '0';
                $arrdata['is_published'] = '0';
                break;
            case '2':
                $arrdata['editor_approval'] = '1';
                $arrdata['moderator_approval'] = '1';
                $arrdata['is_published'] = '0';
                break;
            case '3':
                $arrdata['editor_approval'] = '1';
                $arrdata['moderator_approval'] = '1';
                $arrdata['is_published'] = '1';
                $arrdata['published_date'] = $arrtmp['published_date'];
                break;
        }

        if ($image <> '') {
            $arrdata['image'] = $new_file_name;
        }
        if ($cid == "") {
            $arrdata['user_created'] = $_SESSION['user_id'];
            $this->Data_model->simpanData($arrdata, DB_SLIDER);
        } else {
            $kondisi = array('slider_id' => $cid);
            $arrdata['date_modified'] = date('Y-m-d H:i:s');
            $arrdata['user_modified'] = $_SESSION['kode'];
            $this->Data_model->updateDataWhere($arrdata, DB_SLIDER, $kondisi);
        }

        $config['upload_path'] = $stringreplace . 'rabmag/slider/';
        $config['allowed_types'] = '*';
        $config['file_name'] = $new_file_name;
        $this->load->library('upload', $config);

        if ($this->input->post('_edit') == 1) {
            if ($this->updateForm() == 1) {
                echo 'ok';
            } else {
                echo ' nook';
            }
        } else {
            if (!$this->upload->do_upload('image')) {
                echo 'nooke';
            } else {
                $data = array('upload_data' => $this->upload->data());

                $configs = array(
                    'source_image' => $config['upload_path'] . $new_file_name,
                    'new_image' => $config['upload_path'] . 'thumb',
                    'maintain_ration' => true,
//                    'width' => 150,
                    'height' => 124
                );

                $this->load->library('image_lib', $configs);
                $this->image_lib->resize();
            }
        }
    }

    function hapus() {
        if (IS_AJAX) {
            $basepath = APPPATH;
            $stringreplace = str_replace("apps", "publik", $basepath);
            try {
                unlink($stringreplace . 'rabmag/image/thumb/' . $this->input->post('langid'));
                unlink($stringreplace . 'rabmag/image/' . $this->input->post('langid'));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
            $this->Data_model->hapusDataWhere(DB_SLIDER, array('slider_id' => $this->input->post('cid')));
        }
    }

    function reOrder() {
        $query1 = $this->db->get_where(DB_SLIDER, array('slider_id' => $this->input->post('aid')));
        $qrow1 = $query1->row();
        $ordertoupdate = $qrow1->urutan;

        $query2 = $this->db->get_where(DB_SLIDER, array('urutan' => $this->input->post('ord')));
        $qrow2 = $query2->row();
        $idtoupdate = ($qrow2) ? $qrow2->slider_id : '';

        $this->db->update(DB_SLIDER, array('urutan' => $this->input->post('ord')), array('slider_id' => $this->input->post('aid')));
        if ($idtoupdate != '') {
            $this->db->update(DB_SLIDER, array('urutan' => $ordertoupdate), array('slider_id' => $idtoupdate));
        } else {
            
        }
    }

    /*
     * Approvement
     */

    public function approvement() {
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
        $konten['tabel'] = DB_SLIDER;
        $konten['kolom'] = array("Kode", "Judul", "Photo", "Urutan", "Status", "Tanggal", "");
        $konten['breadcum'] = 'Approvement Slider';
        $konten['smenu'] = 'slider';
        $konten['sbmenu'] = 'slideraprove';
        $konten['css'] = 'approvement/slider/css';
        $konten['js'] = 'approvement/slider/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'approvement/slider/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    function approveArtikel() {
        $rol = $this->uri->segment(4);
        $data = $this->fungsi($rol, 1);
        $this->Data_model->updateDataWhere($data, DB_SLIDER, array('slider_id' => $this->input->post('cid')));
    }

    function unapproveArtikel() {
        $rol = $this->uri->segment(4);
        $data = $this->fungsi($rol, 2);
        $this->Data_model->updateDataWhere($data, DB_SLIDER, array('slider_id' => $this->input->post('cid')));
        echo $this->db->last_query();
    }

    public function approveAll() {
        $rol = $this->uri->segment(4);
        $DTO = $this->input->post('DTO');
        $LANG = $this->input->post('DTP');
        $data = $this->fungsi($rol, 1);
        if (isset($DTO)) {
            $assocResult = json_decode($DTO, true);
            $assocResult2 = json_decode($LANG, true);
            $x = 0;
            foreach ($assocResult as $key => $value) {
                $kondisi = array('slider_id' => $value);
                $this->Data_model->updateDataWhere($data, DB_SLIDER, $kondisi);
                $x++;
            }
        }
    }

    public function unapproveAll() {
        $rol = $this->uri->segment(4);
        $DTO = $this->input->post('DTO');
        $LANG = $this->input->post('DTP');
        $data = $this->fungsi($rol, 2);
        if (isset($DTO)) {
            $assocResult = json_decode($DTO, true);
            $assocResult2 = json_decode($LANG, true);
            $x = 0;
            foreach ($assocResult as $key => $value) {
                $kondisi = array('slider_id' => $value);
                $this->Data_model->updateDataWhere($data, DB_SLIDER, $kondisi);
                $x++;
            }
        }
    }

    function fungsi($param, $k) {
        if ($k == 1) {
            switch ($param):
                case 'e':
                    $data = array('editor_approval' => 1);
                    break;
                case 'm':
                    $data = array('moderator_approval' => 1);
                    break;
                case 'p':
                    $data = array('moderator_approval' => 0,'is_published' => 1, 'published_date' => date("Y-m-d H:i:s"));
                    break;
            endswitch;
        } else {
            switch ($param):
                case 'e':
                    $data = array('editor_approval' => 0);
                    break;
                case 'm':
                    $data = array('moderator_approval' => 0, 'editor_approval' => 0);
                    break;
                case 'p':
                    $data = array('moderator_approval' => 0,'is_published' => 0, 'published_date' => NULL);
                    break;
            endswitch;
        }
        return $data;
    }

}
