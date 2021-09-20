<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role extends MX_Controller {

    function __construct() {
        parent::__construct();
        /*
         * pengecekan session user login, jika belum login maka arahkan ke halaman login         
         */
        if (!isset($_SESSION['username'])) {
            redirect('../login');
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
        $konten['tabel'] = DB_ROLE;
        $konten['kolom'] = array("Kode", "Nama Role", "Kode Menu", "Modul/Menu", "Tambah", "Ubah", "Hapus", "");
        $konten['jmlkolom'] = count($konten['kolom']);
        $konten['breadcum'] = 'Role';
        $konten['smenu'] = 'pengaturan';
        $konten['sbmenu'] = 'role';
        $konten['css'] = 'role/css';
        $konten['js'] = 'role/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'role/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    function sumber() {
        /*
         * list data 
         */
        if (IS_AJAX) {
            $sTable = "vrole";
            $sTablex = "";
            $aColumns = array("kode", "nama_role", "menu", "nama_menu", "tambah", "ubah", "hapus", "induk");
            $kolom = "kode,menu,nama_role,nama_menu,tambah,ubah,hapus,modul,role";
            $sIndexColumn = "kode";
            $where = "";
            $order = "";
            $tmpQry = "SELECT * FROM (SELECT t2.kode AS kode,t2.role AS role,t1.nama_role AS nama_role,t2.menu AS menu,t3.nama_menu AS nama_menu,t3.link_menu AS link_menu,t3.modul AS modul,t2.tambah AS tambah,t2.ubah AS ubah,t2.hapus AS hapus,t2.fitur AS fitur,t3.aktif AS aktif,t3.induk AS induk,t3.urutan AS urutan,t3.ikon AS ikon,t3.toggle AS toggle from ((ad_role t1 left join ad_role_menu t2 on((t1.kode = t2.role))) left join ad_menu_admin t3 on((t2.menu = t3.kode)))) as robin order by role  ";
            $tQuery = "SELECT $kolom,induk "
                    . " FROM ($tmpQry) a  WHERE 1=1 $where $order  ";
            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
        }
    }

    function ubahRole() {
        $kodemenu = $this->input->post('aod');
        $kolom = $this->input->post('aid');
        $koderole = $this->input->post('ord');

        $query = "UPDATE ad_role_menu SET $kolom = if($kolom=1,0,1) WHERE kode=$kodemenu";
        $this->Data_model->jalankanQuery($query, 6);
    }
    
    public function form() {
        $konten['aep'] = $this->uri->segment(5);
        if ($this->uri->segment(5) <> '') {
            $konten['bhs'] = $this->uri->segment(6);
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = ' WHERE kode = ' . $this->uri->segment(5);
            $where = '';
            $query = "SELECT * FROM ad_role_menu $kondisi ";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
        }
        $this->load->view('role/form', $konten);
    }
    
    function simpanData() {

        $arrdata = array();
        $cid = '';
        foreach ($this->input->post() as $key => $value) {
            if (is_array($value)) {
                
            } else {
                $subject = strtolower($key);
                $pattern = '/tgl/i';
                switch ($key):
                    case 'cid':
                        $cid = $value;
                        break;
                    case 'passold':

                        break;
                    case 'password':
                        if ($value == $this->input->post('passold')) {
                            $arrdata[$key] = $value;
                        } else {
                            $arrdata[$key] = md5($value);
                        }
                        break;
                    default :
                        if (preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE)) {
                            if (strlen(trim($value)) > 0) {
                                $tgl = explode("/", $value);
                                $newtgl = $tgl[1] . "/" . $tgl[0] . "/" . $tgl[2];
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
            $this->Data_model->simpanData($arrdata, DB_MENU_ROLE);
        } else {
            $this->Data_model->updateDataWhere($arrdata, DB_MENU_ROLE, array('kode' => $cid));
        }
//        echo $this->db->last_query();
    }

    function loadForm() {
        $konten['aep'] = '';
        if ($this->uri->segment(4) <> '') {
            $konten['rowdata'] = $this->Data_model->satuData('t_' . $this->uri->segment(3), array('kode' => $this->uri->segment(4)));
        }
        $this->load->view('form', $konten);
    }

    function loadFormw() {
        $konten['aep'] = '';
        $this->load->view('wform', $konten);
    }

    

    function hapus() {
        if (IS_AJAX) {
            $param = $this->input->post('cid');
            if ($this->input->post('cid') != '') {
                $this->Data_model->hapusDataWhere('t_' . $this->input->post('cod'), array('kode' => $param));
                echo json_encode("ok");
            }
        }
    }
    
    
    public function formulir() {
        $halaman = 'default';
        $konten['smenu'] = 'pengguna';
        $konten['sbmenu'] = 'formulir';
        $konten['css'] = 'css';
        $konten['js'] = 'jsform';
        $konten['header'] = THEME . 'header';
        $konten['footer'] = THEME . 'footer';
        $konten['tujuan'] = 'defaultform';
        $konten['tabel'] = 'pengguna';
        $konten['kolom'] = array("Kode", "Nama", "Email", "NIP", "Role", "");
        $konten['jmlkolom'] = count($konten['kolom']);
        $this->load->library('template');
        $this->template->load(THEME . 'default', $halaman, $konten);
    }

}
