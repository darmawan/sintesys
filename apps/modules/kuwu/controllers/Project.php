<?php

/*
 * nama class: Class Project
 * fungsi: mengatur semua data project
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Project extends MX_Controller {

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
        $konten['tabel'] = DB_PROJECT;
        $konten['kolom'] = array("Kode", "Nama Project", "Perusahaan", "Tanggal", "Status", "Group", "");
        $konten['breadcum'] = 'Project';
        $konten['smenu'] = 'project';
        $konten['sbmenu'] = '';
        $konten['css'] = 'project/css';
        $konten['js'] = 'project/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'project/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    public function sumber() {
        if (IS_AJAX) {
            $aColumns = array();
            $sIndexColumn = '';
            $sTablex = '';
            $sIndexColumn = "project_id";
            $sTable = DB_PROJECT;
            $xTable = DB_TYPE;

            $aColumns = array("project_id", "project_title", "company_name", "project_date", "is_published", "belongto", "tags");
            $where = "";
            $query = $this->libglobal->kueriProject();
//                    "SELECT * FROM (SELECT x.*, y.type_name FROM $sTable x LEFT JOIN $xTable y ON (x.type_id=y.type_id) $where ) "
//                    . "as hafidz ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1 ";

            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
        }
    }

    public function form() {
        $konten['aep'] = $this->uri->segment(6);
        $konten['salin'] = $this->uri->segment(6);
        if ($this->uri->segment(5) <> '') {
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = 'project_id';
            $where = '';
            $query0 = "SELECT p.project_id, p.lang_id, p.company_name, p.project_date, p.project_title, p.summary, p.content, p.type_id, p.cat_id, p.product_id, p.image, p.tags, p.page_title, p.read_count, p.editor_approval, p.moderator_approval, p.is_published, p.publish_date, p.date_created, p.user_created, p.date_modified, p.user_modified, d.product_title, d.cat_id  AS cat_product, d.type_id AS type_product, x.type_id    AS type_belongto, x.type_name  AS belongto FROM ad_project p LEFT JOIN ad_product d ON p.product_id = d.product_id LEFT JOIN ad_kategori_product k ON d.cat_id = k.cat_id LEFT JOIN ad_type x ON d.type_id = x.type_id LEFT JOIN ad_type t ON k.type_id = t.type_id";
            $query = "SELECT * FROM ( $query0 ) as aep  WHERE " . $kondisi . " = " . $nilai . "";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('project/form', $konten);
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
            $this->Data_model->simpanData($arrdata, DB_PROJECT);
        } else {
            $kondisi = array('project_id' => $cid);
            $arrdata['date_modified'] = date('Y-m-d H:i:s');
            $arrdata['user_modified'] = $_SESSION['user_id'];
            $this->Data_model->updateDataWhere($arrdata, DB_PROJECT, $kondisi);
        }
        $config['upload_path'] = $stringreplace . 'rabmag/client/';
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
            }
        }
    }

    function hapus() {
        if (IS_AJAX) {
            $this->Data_model->hapusDataWhere(DB_PROJECT, array('project_id' => $this->input->post('cid')));
        }
    }

    function getList() {
        $tabel = 'ad_' . $this->input->post('tabel');
        $param = $this->input->post('param');
        $field = $this->input->post('fld');
        $nmfld = explode(",", $this->input->post('kolom'));
        $order = $nmfld[0]; 
        $tabel = "(SELECT x.type_id    AS type_belongto,
                        x.type_name  AS belongto, 
                        p.product_id,
                        p.product_title, k.urutan,       
                        k.cat_id,
                        trim(k.name) AS name,
                        k.type_id,
                        t.type_name,
                        t.type_grp
                 FROM ad_product    p
                      JOIN ad_kategori_product k ON p.cat_id = k.cat_id
                      JOIN ad_type x ON p.type_id = x.type_id
                      JOIN ad_type t ON k.type_id = t.type_id) as aep ";
        $query = $this->db->order_by('product_id,urutan', 'ASC')->get_where($tabel, (($param == '') ? array('1' => 1) : array($field => $param)));
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
