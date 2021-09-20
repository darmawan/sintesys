<?php

/*
 * nama class: Class Image
 * fungsi: mengatur semua data artikel dan kategori artikel
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Image extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('../kuwu/login');
        }
        $this->load->helper('formulir');
    }

    public function index() {
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
        $konten['tabel'] = DB_IMAGE;
        $konten['kolom'] = array("Kode", "Judul Photo", "Nama Photo", "Aktif", "Tanggal", "");
        $konten['breadcum'] = 'Photo';
        $konten['smenu'] = 'media';
        $konten['sbmenu'] = 'photo';
        $konten['css'] = 'image/css';
        $konten['js'] = 'image/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'image/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    public function sumber() {
        if (IS_AJAX) {
            $whereapprove = "";
            $aColumns = array();
            $sIndexColumn = '';
            $sTablex = '';
            $sIndexColumn = "image_id";
            $sTable = DB_IMAGE;

            $aColumns = array("image_id", "image_name", "image_path", "active", "date_inserted", "cat_id");
            $where = "";
            $query = "SELECT image_id, image_name, image_path, date_inserted, cat_id, mainimg, reffid, active, user_created, user_modified FROM $sTable ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1  ";

            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
        }
    }

    public function form() {
        $konten['aep'] = $this->uri->segment(5);
        if ($this->uri->segment(5) <> '') {
            $konten['bhs'] = $this->uri->segment(6);
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = ' WHERE image_id = ' . $this->uri->segment(5);
            $where = '';
            $query = "SELECT image_id, image_name, image_path, date_inserted, cat_id, mainimg, refftype, reffid, active, user_created, user_modified FROM " . DB_IMAGE . " $kondisi ";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('image/form', $konten);
    }

    function getDokumen() {
        if (IS_AJAX) {
            if ($this->uri->segment(4) == "a") {
                $result = $this->Data_model->jalankanQuery("SELECT article_id as kode, article_title as judul, type_id as tipe, date_created FROM ad_article GROUP BY article_id ORDER BY lang_id", 3);
            } else {
                $result = $this->Data_model->jalankanQuery("SELECT news_id as kode, news_title as judul, type_id as tipe, date_created FROM ad_news GROUP BY news_id ORDER BY lang_id", 3);
            }
        }
        foreach ($result as $row):
            $theresult[] = array('id' => $row->kode, 'desc' => $row->judul, 'ntype' => $row->tipe,
            );
        endforeach;
        echo json_encode($theresult);
    }

    function simpanData() {
        $image = isset($_FILES['image_path']['name']) ? $_FILES['image_path']['name'] : '';
        $basepath = APPPATH;
        $stringreplace = str_replace("apps", "publik", $basepath);
        $typeimg = explode("/", $_FILES['image_path']['type']);
        $namaimg = explode(".", $image);
        $nmbaru = generate_title_to_url($image);
      
     $h = count($namaimg);
    
    if(count($namaimg)>1) {
        $nmbaru =  generate_title_to_url(str_replace('.'.$namaimg[(count($namaimg)-1)], '', $image)).'.'.$namaimg[(count($namaimg)-1)];
    }
        $new_file_name = ($image == '') ? '' : $nmbaru; //generate_title_to_url($image);
//        $new_file_name = ($image == '') ? '' : str_replace($typeimg[1], '', $new_file_tmp) . '.' . $typeimg[1];
//        $new_file_name = str_replace($typeimg[1], '', $new_file_tmp) . '.' . $typeimg[1];

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
                    case 'reffid':
                        $arrid = explode('|', $value);
                        $arrdata[$key] = $arrid[0];
                        $arrdata['cat_id'] = (isset($arrid[1])) ? $arrid[1] : NULL;
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
         if ($image <> '') {
            $arrdata['image_path'] = $new_file_name;
        }

        if ($cid == "") {
            $arrdata['user_created'] = $_SESSION['user_id'];
            $this->Data_model->simpanData($arrdata, DB_IMAGE);
        } else {
            $kondisi = array('image_id' => $cid);
            $arrdata['date_modified'] = date('Y-m-d H:i:s');
            $arrdata['user_modified'] = $_SESSION['kode'];
            $this->Data_model->updateDataWhere($arrdata, DB_IMAGE, $kondisi);
        }

        $config['upload_path'] = $stringreplace . 'rabmag/image/';
        $config['allowed_types'] = '*';
        $config['file_name'] = $new_file_name;
        $this->load->library('upload', $config);

        if ($this->input->post('_edit') == 1) {
            if ($this->updateForm() == 1) {
//                redirect('kuwu/image');
                echo 'ok';
            } else {
                echo ' nook';
                //redirect('kuwu/image');
            }
        } else {
            if (!$this->upload->do_upload('image_path')) {
                echo 'nooke';
//                redirect('kuwu/image/getForm');
            } else {
                $data = array('upload_data' => $this->upload->data());

                $configs = array(
                    'source_image' => $config['upload_path'] . $new_file_name,
                    'new_image' => $config['upload_path'] . 'thumb',
                    'maintain_ration' => true,
                    'width' => 130,
//                    'height' => 92
                );

                $this->load->library('image_lib', $configs);
                $this->image_lib->resize();
            }
        }
    }

    function hapus() {
        if (IS_AJAX) {
//            $basepath = APPPATH;
//            $stringreplace = str_replace("apps", "publik", $basepath);
            try {
                unlink('publik/rabmag/image/thumb/' . $this->input->post('cod'));
                unlink('publik/rabmag/image/' . $this->input->post('cod'));
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
            $this->Data_model->hapusDataWhere(DB_IMAGE, array('image_id' => $this->input->post('cid')));

        }
    }

    function getList() {
        $tabel = 'ad_' . $this->input->post('tabel');
        $param = $this->input->post('param');
        $field = $this->input->post('fld');
        $nmfld = explode(",", $this->input->post('kolom'));
        $order = $nmfld[0]; //$this->input->post('tabel') . "_id";
        $query = $this->db->order_by($order, 'DESC')->get_where($tabel, (($param == '') ? array('1' => 1) : array($field => $param)));
    //    echo $this->db->last_query();
        $result = $query->result();
        
        $data = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $data[] = '{"id" : ' . $row->article_id . ', "value" : "' . trim($row->article_title) . '", "sub":0, "isigrup":0}';
            }
        }
        if (IS_AJAX) {
            echo '[' . implode(',', $data) . ']';
            die;
        }
    }

}

?>
