<?php

/*
 * nama class: Class Project
 * fungsi: mengatur semua data product
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Product extends MX_Controller {

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
        $konten['tabel'] = DB_PRODUCT;
        $konten['kolom'] = array("Kode", "Nama Produk/Layanan", "Kategori", "Pemilik", "Jenis", "Status", "");
        $konten['breadcum'] = 'Product';
        $konten['smenu'] = 'product';
        $konten['sbmenu'] = '';
        $konten['css'] = 'product/css';
        $konten['js'] = 'product/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'product/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    public function sumber() {
        if (IS_AJAX) {
            $aColumns = array();
            $sTablex = '';
            $sIndexColumn = "product_id";
            $sTable = DB_PRODUCT;
            $xTable = DB_CATEGORY_PRODUCT;
            $yTable = DB_TYPE;

            $aColumns = array("product_id", "product_title", "category_name", "belongto", "tags", "is_published", "inpo");
            $where = "";
            $query = "SELECT * FROM (" . $this->libglobal->kueriProduct() . " ) as hafidz  ";
//            $query = "SELECT * FROM (SELECT x.*, z.type_name, y.name as cat_name, y.urutan, a.type_name as pemilik FROM $sTable x LEFT JOIN $yTable a ON (x.type_id=a.type_id) LEFT JOIN   $xTable y ON (x.cat_id=y.cat_id) JOIN $yTable z ON (y.type_id=z.type_id) $where ) "
//                    . "as hafidz ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1 ";

            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
        }
    }

    public function sumberkat() {
        if (IS_AJAX) {
            $aColumns = array();
            $sIndexColumn = '';
            $sIndexColumn = "cat_id";
            $sTable = DB_CATEGORY_PRODUCT;
            $xTable = DB_TYPE;
            $sTablex = '';

            $aColumns = array("cat_id", "name", "type_name", "type_id");
            $where = "";
            $query = "SELECT * FROM (SELECT x.*, a.type_name FROM $sTable x LEFT JOIN $xTable a ON (x.type_id=a.type_id) $where ) "
                    . "as hafidz ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1 ";

            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
        }
    }

    public function form() {
        $konten['aep'] = $this->uri->segment(6);
        $konten['salin'] = $this->uri->segment(6);
        if ($this->uri->segment(5) <> '') {
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = 'product_id';
            $where = '';
            $query = "SELECT * FROM " . DB_PRODUCT . "  WHERE " . $kondisi . " = " . $nilai . "";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('product/form', $konten);
    }

    function simpanData() {
        $image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
        $basepath = APPPATH;
        $stringreplace = str_replace("apps", "publik", $basepath);
        $typeimg = explode("/", $_FILES['image']['type']);
        $new_file_tmp = generate_title_to_url(substr($image,0,-4));
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
                    case 'is_published':
                        if ($value == 1) {
                            $arrdata['publish_date'] = date('Y-m-d H:i:s');
                        } else {
                            $arrdata['publish_date'] = NULL;
                        }
                        $arrdata[$key] = $value;
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
            $arrdata['image'] = $new_file_name;
        }
        if ($cid == "") {
            $arrdata['user_created'] = $_SESSION['user_id'];
            $this->Data_model->simpanData($arrdata, DB_PRODUCT);
        } else {
            $kondisi = array('product_id' => $cid);
            $arrdata['date_modified'] = date('Y-m-d H:i:s');
            $arrdata['user_modified'] = $_SESSION['user_id'];
            $this->Data_model->updateDataWhere($arrdata, DB_PRODUCT, $kondisi);
        }

        $config['upload_path'] = $stringreplace . 'rabmag/product/';
        $config['allowed_types'] = '*';
        $config['file_name'] = $new_file_name;
        $this->load->library('upload', $config);

        if ($this->input->post('_edit') == 1) {
            
        } else {
            if (!$this->upload->do_upload('image')) {
                echo 'nooke';
            } else {
                $data = array('upload_data' => $this->upload->data());

                $configs = array(
                    'source_image' => $config['upload_path'] . $new_file_name,
                    'new_image' => $config['upload_path'] . 'thumb',
                    'maintain_ration' => true,
                    'width' => 216,
                    'height' => 108
                );

                $this->load->library('image_lib', $configs);
                $this->image_lib->resize();
                if ($cid <> "") {
                    unlink('publik/rabmag/product/thumb/' . $this->input->post('_imgnm'));
                    unlink('publik/rabmag/product/' . $this->input->post('_imgnm'));
                }
            }
        }
    }

    function simpanDataKat() {
        foreach ($this->input->post() as $key => $value) {
            if (is_array($value)) {
                
            } else {
                $subject = strtolower($key);
                $pattern = '/tgl/i';
                switch ($key):
                    case 'cat_id':
                        $cid = $value;
                        $arrdata[$key] = $value;
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

        if ($cid == "") {
            $kodebaru = $this->Data_model->getLastIdDb(DB_CATEGORY_PRODUCT, 'cat_id', '');
            $arrdata['cat_id'] = $kodebaru->cat_id + 1;
            $this->Data_model->simpanData($arrdata, DB_CATEGORY_PRODUCT);
        } else {
            $kondisi = array('cat_id' => $cid);
            $this->Data_model->updateDataWhere($arrdata, DB_CATEGORY_PRODUCT, $kondisi);
        }
    }

    function hapus() {
        if (IS_AJAX) {
            $this->Data_model->hapusDataWhere(DB_PRODUCT, array('product_id' => $this->input->post('cid')));
        }
    }

    function getList() {
        $tabel = 'ad_' . $this->input->post('tabel');
        $param = $this->input->post('param');
        $field = $this->input->post('fld');
        $nmfld = explode(",", $this->input->post('kolom'));
        $order = $nmfld[0];
        $tabel = "(" . $this->libglobal->kueriProduct() . ") as aep ";
        $query = $this->db->order_by('product_id', 'ASC')->get_where($tabel, (($param == '') ? array('1' => 1) : array($field => $param, 'inpo' => 'induk')));
//        echo $this->db->last_query();
        $result = $query->result();
        $data = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $data[] = '{"id" : ' . $row->$nmfld[0] . ', "value" : "' . trim($row->$nmfld[1]) . '"}';
            }
        }
        if (IS_AJAX) {
            echo '[' . implode(',', $data) . ']';
            die;
        }
    }

}
