<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profil extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        /*
         * pengecekan session user login, jika belum login maka arahkan ke halaman login         
         */
        if (!isset($_SESSION['username'])) {
            redirect('../login');
        }
    }

    public function index()
    {
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
        $konten['smenu'] = 'profil';
        $konten['sbmenu'] = 'profil';
        $konten['css'] = 'css';
        $konten['js'] = 'js';
        $konten['target'] = 'halaman';
        $konten['tabel'] = DB_USER;
        $konten['rowdata'] = $this->Data_model->satuData('(select a.user_id AS kode,p.nama_lengkap AS nama_lengkap,a.username AS username,a.email AS email,a.password AS password,a.role_id AS role,
t.nama_role AS nama_role,a.isActive AS aktif,p.lahir AS lahir,p.status AS status,p.kelamin AS kelamin,p.alamat AS alamat,
p.pekerjaan AS pekerjaan,p.telepon AS telepon,p.hp AS hp,p.deksripsi AS deksripsi,p.twitter AS twitter,p.facebook AS facebook,p.photo AS photo  
from ((ad_user a join ad_role t on((a.role_id = t.kode))) 
left join ad_profil p on((a.user_id = p.kode_user)))) as betmen ', array('kode' => $_SESSION['user_id']));
        $this->load->view('default_profil', $konten);
    }

    function detil()
    {
        $konten['rowdata'] = $this->Data_model->satuData('(select a.user_id AS kode,p.nama_lengkap AS nama_lengkap,a.username AS username,a.email AS email,a.password AS password,a.role_id AS role,
t.nama_role AS nama_role,a.isActive AS aktif,p.lahir AS lahir,p.status AS status,p.kelamin AS kelamin,p.alamat AS alamat,
p.pekerjaan AS pekerjaan,p.telepon AS telepon,p.hp AS hp,p.deksripsi AS deksripsi,p.twitter AS twitter,p.facebook AS facebook,p.photo AS photo  
from ((ad_user a join ad_role t on((a.role_id = t.kode))) 
left join ad_profil p on((a.user_id = p.kode_user)))) as betmen ', array('kode' => $_SESSION['user_id']));
        $this->load->view('profil', $konten);
    }

    function akun()
    {
        $konten['rowdata'] = $this->Data_model->satuData('(select a.user_id AS kode,p.nama_lengkap AS nama_lengkap,a.username AS username,a.email AS email,a.password AS password,a.role_id AS role,
t.nama_role AS nama_role,a.isActive AS aktif,p.lahir AS lahir,p.status AS status,p.kelamin AS kelamin,p.alamat AS alamat,
p.pekerjaan AS pekerjaan,p.telepon AS telepon,p.hp AS hp,p.deksripsi AS deksripsi,p.twitter AS twitter,p.facebook AS facebook,p.photo AS photo  
from ((ad_user a join ad_role t on((a.role_id = t.kode))) 
left join ad_profil p on((a.user_id = p.kode_user)))) as betmen ', array('kode' => $_SESSION['user_id']));
        $this->load->view('akun', $konten);
    }

    function simpanData()
    {
        $arrdata = array();
        $cid = $_SESSION['kode'];
        foreach ($this->input->post() as $key => $value) {
            if (is_array($value)) {
            } else {
                $subject = strtolower($key);
                $pattern = '/lahir/i';
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
                    default:
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
        $tuju = str_replace("t_", "ad_", $this->uri->segment(3));
        if ($this->uri->segment(3) == 'ad_profil') {
            $this->Data_model->updateDataWhere($arrdata, $tuju, array('kode_user' => $_SESSION['kode']));
        } else {
            $this->Data_model->updateDataWhere($arrdata, $tuju, array('kode' => $_SESSION['kode']));
        }
        // echo $this->db->last_query();
    }

    function uphoto()
    {
        $ofiles = $this->input->post('fileold');
        $files = $_FILES;
        $_FILES['userfile']['name'] = $files['nmfile']['name'];
        $_FILES['userfile']['type'] = $files['nmfile']['type'];
        $_FILES['userfile']['tmp_name'] = $files['nmfile']['tmp_name'];
        $_FILES['userfile']['error'] = $files['nmfile']['error'];
        $_FILES['userfile']['size'] = $files['nmfile']['size'];

        $this->Data_model->updateDataWhere(array('photo' => $_FILES['userfile']['name']), 'ad_profil', array('kode_user' => $_SESSION['kode']));


        if (strlen(trim($_FILES['userfile']['name'])) > 0) {
            $basepath = BASEPATH;
            $stringreplace = str_replace("system", "publik", $basepath);
            $config['upload_path'] = $stringreplace . 'profil';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['overwrite'] = TRUE;

            if ($ofiles <> '') {
                unlink($config['upload_path'] . '/' . $ofiles);
            }

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload()) {
                $configs = array(
                    'source_image' => $config['upload_path'] . '/' . $_FILES['userfile']['name'],
                    'new_image' => $config['upload_path'] . '/thumb',
                    'maintain_ration' => true,
                    'width' => 60,
                    'height' => 60
                );
                $this->load->library('image_lib', $configs);
                $this->image_lib->resize();
                $_SESSION['photo'] = $_FILES['userfile']['name'];
            } else {
                $error = array('error' => $this->upload->display_errors());
            }
        }
    }
}
