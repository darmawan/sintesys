<?php

/*
 * nama class: Class Galeri
 * fungsi: mengatur semua data artikel dan kategori artikel
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Galeri extends MX_Controller {

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
        $konten['tabel'] = DB_GALERI;
        $konten['kolom'] = array("Kode", "Judul Photo", "Photo", "Album", "Status", "Tanggal", "");
        $konten['breadcum'] = 'Galeri/Album';
        $konten['smenu'] = 'media';
        $konten['sbmenu'] = 'galeri';
        $konten['css'] = 'galeri/css';
        $konten['js'] = 'galeri/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'galeri/index';
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
            $sIndexColumn = "galeri_id";
            $sTable = DB_GALERI;
            $xTable = DB_GALERI_CAT;

            $aColumns = array("galeri_id", "galeri_text", "image", "name", "active", "date_created", "cat_id");
            $where = "";
            $query = "SELECT
                            $sTable.galeri_id, galeri_text, image, $xTable.name, date_created, $sTable.cat_id, active, user_created, user_modified  
                        FROM
                              $sTable LEFT JOIN $xTable ON ($sTable.cat_id= $xTable.cat_id) ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1  ";

            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $xTable);
        }
    }

    public function form() {
        $konten['aep'] = $this->uri->segment(5);
        if ($this->uri->segment(5) <> '') {
            $konten['bhs'] = $this->uri->segment(6);
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = ' WHERE galeri_id = ' . $this->uri->segment(5);
            $where = '';
            $query = "SELECT * FROM " . DB_GALERI . " $kondisi ";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('galeri/form', $konten);
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
            $this->Data_model->simpanData($arrdata, DB_GALERI);
        } else {
            $kondisi = array('galeri_id' => $cid);
            $arrdata['date_modified'] = date('Y-m-d H:i:s');
            $arrdata['user_modified'] = $_SESSION['kode'];
            $this->Data_model->updateDataWhere($arrdata, DB_GALERI, $kondisi);
        }

        $config['upload_path'] = $stringreplace . 'rabmag/galeri/';
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
            if (!$this->upload->do_upload('image')) {
                echo 'nooke';
//                redirect('kuwu/image/getForm');
            } else {
                $data = array('upload_data' => $this->upload->data());

                $configs = array(
                    'source_image' => $config['upload_path'] . $new_file_name,
                    'new_image' => $config['upload_path'] . 'thumb',
                    'maintain_ration' => true,
//                    'width' => 150,
                    'height' => 86
                );

                $this->load->library('image_lib', $configs);
                $this->image_lib->resize();
            }
        }
    }

    function hapus() {
        if (IS_AJAX) {
            $this->Data_model->hapusDataWhere(DB_GALERI, array('galeri_id'=>  $this->input->post('cid')));

            unlink('publik/rabmag/galeri/' . $this->input->post('cod'));
            unlink('publik/rabmag/galeri/thumb/' . $this->input->post('cod'));
        }
    }


    public function deleteImage() {
        $idimg = $this->uri->segment(3);
        unlink('../' . $_GET['p']);
        $this->galeri_model->delete_record($idimg);
        redirect('kuwu/image/imageList');
    }

    public function tesImagepage() {
        $this->load->view('image/tesaja');
    }

}

?>
