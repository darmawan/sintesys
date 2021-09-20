<?php

/*
 * nama class: Class Article
 * fungsi: mengatur semua data artikel dan kategori artikel
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pengguna extends MX_Controller {

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
        $konten['tabel'] = DB_USER;
        $konten['kolom'] = array("Kode", "Nama", "Email", "Role", "Status", "Tanggal Dibuat", "");
        $konten['breadcum'] = 'Pengguna';
        $konten['smenu'] = 'pengaturan';
        $konten['sbmenu'] = 'pengguna';
        $konten['css'] = 'pengguna/css';
        $konten['js'] = 'pengguna/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'pengguna/index';
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
            $sIndexColumn = "user_id";
            $sTable = DB_USER;
            $xTable = DB_ROLE;

            $aColumns = array("user_id", "first_name", "email", "role_name", "isActive", "date_created", "image");
            $where = "";
            $query = "SELECT * FROM (SELECT x.user_id, x.username, x.first_name, x.last_name, x.email, x.password, x.role_id, x.image, x.isActive, x.date_created, "
                    . "x.user_created, x.date_modified, x.user_modified, y.nama_role as role_name FROM $sTable x JOIN $xTable y ON (x.role_id=y.kode) $where ) "
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
            $kondisi = 'user_id';
            $where = '';
            $query = "SELECT x.user_id, x.username, x.first_name, x.last_name, x.email, x.password, x.role_id, x.image, x.isActive, x.date_created, "
                    . "x.user_created, x.date_modified, x.user_modified, y.nama_role as role_name FROM " . DB_USER . " x JOIN " . DB_ROLE . " y ON (x.role_id=y.kode) WHERE x." . $kondisi . " = " . $nilai . "";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('pengguna/form', $konten);
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
                    case 'passold':

                        break;
                    case 'password':
                        if ($value == $this->input->post('passold') || md5($value) == $this->input->post('passold')) {
                            if ($value <> '') {
                                $arrdata[$key] = $value;
                            }
                        } else {
                            $arrdata[$key] = md5($value);
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

        if ($image <> '') {
            $arrdata['image'] = $new_file_name;
        }
        if ($cid == "") {
            $arrdata['username'] =  strtolower($arrdata['first_name']);
            $arrdata['user_created'] = $_SESSION['user_id'];
            
            $this->Data_model->simpanData($arrdata, DB_USER);
            
            $cek = $this->Data_model->getLastIdDb($DB_USER, 'user_id','');
            $arrprofil['kode_user'] = $cek->user_id;
            $arrprofil['kode_user'] = $arrdata['first_name'] .' '.$arrdata['last_name'];
            $arrprofil['email'] = $arrdata['email'];
            $this->Data_model->simpanData($arrprofil, DB_PROFIL);


        } else {
            $kondisi = array('user_id' => $cid);
            $arrdata['date_modified'] = date('Y-m-d H:i:s');
            $arrdata['user_modified'] = $_SESSION['user_id'];
            $this->Data_model->updateDataWhere($arrdata, DB_USER, $kondisi);
        }

        $config['upload_path'] = $stringreplace . 'profil/';
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
                    'height' => 56
                );

                $this->load->library('image_lib', $configs);
                $this->image_lib->resize();
            }
        }
    }

    function hapus() {
        if (IS_AJAX) {
            $this->Data_model->hapusDataWhere(DB_USER, array('user_id' => $this->input->post('cid')));
        }
    }

}
