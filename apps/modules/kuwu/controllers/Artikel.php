<?php

/*
 * nama class: Class Article
 * fungsi: mengatur semua data artikel dan kategori artikel
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Artikel extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('../kuwu/login');
        }
        $this->load->helper('formulir');
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
        $konten['tabel'] = DB_ARTICLE;
        $konten['kolom'] = array("Kode", "Bahasa", "Judul", "Publikasi", "Tanggal Publikasi", "Tanggal Dibuat", "");
        $konten['breadcum'] = 'Artikel';
        $konten['smenu'] = 'artikel';
        $konten['sbmenu'] = 'artikel';
        $konten['css'] = 'artikel/css';
        $konten['js'] = 'artikel/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'artikel/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    public function sumber()
    {
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
                default:
                    $whereapprove = "  ";
                    $kolom = "is_published";
                    $kolomplus = "editor_approval,moderator_approval";
                    $arrplus1 = "editor_approval";
                    $arrplus2 = "moderator_approval";
                    break;
            endswitch;

            $aColumns = array();
            $sIndexColumn = '';
            $sTablex = '';
            $sIndexColumn = "article_id";
            $sTable = DB_ARTICLE;

            $aColumns = array("article_id", "lang_id", "article_title", "$kolom", "publish_date", "date_created", "artcount", "$arrplus1", "$arrplus2");
            $where = "";
            $query = "SELECT * FROM (SELECT t.article_id, lang_id, article_title, $kolom, DATE_FORMAT(publish_date,'%d-%m-%Y') as publish_date, DATE_FORMAT(date_created,'%d-%m-%Y') as date_created, z.jumlah as artcount,$kolomplus FROM $sTable t LEFT JOIN (SELECT x.article_id, count(x.article_id) as jumlah FROM $sTable x GROUP BY x.article_id) as z ON z.article_id=t.article_id $where ) as hafidz ";
            $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1 $whereapprove ";

            echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
            //            echo $this->db->last_query();
        }
    }

    public function form()
    {
        $this->load->helper("formulir");
        $konten['aep'] = $this->uri->segment(5);
        $konten['bhs'] = 0;
        $konten['salin'] = $this->uri->segment(6);
        if ($this->uri->segment(6) == 'salin') {
            $konten['bhs'] = ($this->uri->segment(7) == 1) ? 2 : 1;
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = 'article_id';
            if ($this->uri->segment(7) == 1) {
                $query = "SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_ARTICLE . " a LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id=c.article_id) WHERE a.lang_id=1 AND a." . $kondisi . " = " . $nilai . "";
                $konten['rowdataen'] = $this->Data_model->jalankanQuery($query, 1);
            } else {
                $queryen = "SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_ARTICLE . " a LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id=c.article_id) WHERE a.lang_id=2 AND a." . $kondisi . " = " . $nilai . "";
                $konten['rowdata'] = $this->Data_model->jalankanQuery($queryen, 1);
            }
        } else if ($this->uri->segment(5) <> '') {
            $konten['bhs'] = $this->uri->segment(6);
            $nilai = str_replace("_", " ", $this->uri->segment(5));
            $kondisi = 'article_id';
            $where = '';
            $query = "SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_ARTICLE . " a LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id=c.article_id) WHERE a.lang_id=1 AND a." . $kondisi . " = " . $nilai . "";
            $konten['rowdata'] = $this->Data_model->jalankanQuery($query, 1);
            $queryen = "SELECT a.*, c.cat_id, c.name as cat_name FROM " . DB_ARTICLE . " a LEFT JOIN " . DB_CATEGORY . " c ON (a.article_id=c.article_id) WHERE a.lang_id=2 AND a." . $kondisi . " = " . $nilai . "";
            $konten['rowdataen'] = $this->Data_model->jalankanQuery($queryen, 1);
        }
        $this->load->view('artikel/form', $konten);
    }

    function simpanData()
    {
        $arrdata = array();
        $arrdata2 = array();
        $arrdataen = array();
        $arrdataen2 = array();
        $arrtmp = array();
        $arrtmp2 = array();
        $cid = '';
        foreach ($this->input->post() as $key => $value) {
            if (is_array($value)) {
            } else {
                $subject = strtolower($key);
                $pattern = '/date/i';
                $b = substr(trim($key), -3);
                if ($b == '_en') {
                    $kunci = str_replace('_en', '', $key);
                    $arrdataen['lang_id'] = 2;
                    switch ($kunci):
                        case 'status_article':
                            $statusen = $value;
                            break;
                        case 'cat_id':
                            $arrdataen2[$kunci] = $value;
                            break;
                        case 'publish_date':
                            if (strlen(trim($value)) > 0) {
                                $tgl = explode("-", $value);
                                $newtgl = $tgl[1] . "-" . $tgl[0] . "-" . $tgl[2];
                                $time = strtotime($newtgl);
                                $arrtmp2[$kunci] = $value; // date('Y-m-d', $time);
                            } else {
                                $arrtmp2[$kunci] = NULL;
                            }
                            break;
                        default:
                            if (preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE)) {
                                if (strlen(trim($value)) > 0) {
                                    $tgl = explode("-", $value);
                                    $newtgl = $tgl[1] . "-" . $tgl[0] . "-" . $tgl[2];
                                    $time = strtotime($newtgl);
                                    $arrdataen[$kunci] = $value; // date('Y-m-d', $time);
                                } else {
                                    $arrdataen[$kunci] = NULL;
                                }
                            } else {
                                $arrdataen[$kunci] = $value;
                            }
                            break;
                    endswitch;
                } else {
                    $arrdata['lang_id'] = 1;
                    switch (trim($key)):
                        case '_aid':
                            $aid = $value;
                            break;
                        case 'status_article':
                            $status = $value;
                            break;
                        case 'cat_id':
                            $arrdata2[$key] = $value;
                            break;
                        case 'publish_date':
                            if (strlen(trim($value)) > 0) {
                                $tgl = explode("-", $value);
                                $newtgl = $tgl[1] . "-" . $tgl[0] . "-" . $tgl[2];
                                $time = strtotime($newtgl);
                                $arrtmp[$key] = $value; // date('Y-m-d', $time);
                            } else {
                                $arrtmp[$key] = NULL;
                            }
                            break;
                        case 'cid':
                            $cid = $value;
                            break;
                        default:
                            if (preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE)) {
                                if (strlen(trim($value)) > 0) {
                                    $tgl = explode("-", $value);
                                    $newtgl = $tgl[1] . "-" . $tgl[0] . "-" . $tgl[2];
                                    $time = strtotime($newtgl);
                                    $arrdata[$key] = $value; // date('Y-m-d', $time);
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
        }
        if (isset($status)) {
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
                    $arrdata['publish_date'] = (isset($arrtmp['publish_date'])) ? $arrtmp['publish_date'] : $arrtmp2['publish_date'];
                    break;
            }
        }
        if (isset($statusen)) {
            switch ($statusen) {
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
                    $arrdata['publish_date'] = (isset($arrtmp['publish_date'])) ? $arrtmp['publish_date'] : $arrtmp2['publish_date'];
                    break;
            }
        }
        $category = (isset($arrdata2['cat_id']) && $arrdata2['cat_id'] != '') ? $arrdata2['cat_id'] : '';

        if ($aid != '') {
            $next_id = $aid;
        } else {
            $last_id_article_in_db = $this->Data_model->getLastIdDb(DB_ARTICLE, 'article_id', '');
            $next_id = $last_id_article_in_db->article_id + 1;
        }
        if ($category != '') {
            $whatcat = $this->Data_model->ambilDataWhere(DB_CATEGORY, array('cat_id' => $category), 'urutan', 'desc', 'cat_id', $select = 'cat_id,name, count(urutan) as urutan', 'row');
            $isthere = $this->Data_model->cekData(DB_CATEGORY, array('article_id' => $next_id));
            if ($isthere > 0) {
            } else {
                $arrdata2 = array(
                    'cat_id' => $category, 'name' => $whatcat->name, 'article_id' => $next_id, 'urutan' => ($whatcat->urutan + 1), 'lang_id' => 1
                );
                $this->Data_model->simpanData($arrdata2, DB_CATEGORY);
            }
        }
        if (isset($arrdata['article_title']) && $arrdata['article_title'] != null) {
            if ($cid == "") {
                $arrdata['article_id'] = $next_id;
                $arrdata['date_modified'] = date('Y-m-d H:i:s');
                $arrdata['user_created'] = $_SESSION['user_id'];
                $this->Data_model->simpanData($arrdata, DB_ARTICLE);
            } else {
                $kondisi = array('article_id' => $cid, 'lang_id' => 1);
                $arrdata['date_modified'] = date('Y-m-d H:i:s');
                $arrdata['user_modified'] = $_SESSION['kode'];
                $this->Data_model->updateDataWhere($arrdata, DB_ARTICLE, $kondisi);
            }
        }
        if (isset($arrdataen['article_title']) && $arrdataen['article_title'] != null) {
            if ($cid == "" && $arrdataen['article_title'] <> '') {
                $arrdataen['article_id'] = $next_id;
                $arrdataen['date_modified'] = date('Y-m-d H:i:s');
                $arrdataen['user_created'] = $_SESSION['user_id'];
                $this->Data_model->simpanData($arrdataen, DB_ARTICLE);
            } else {
                $kondisi = array('article_id' => $cid, 'lang_id' => 2);
                $arrdataen['date_modified'] = date('Y-m-d H:i:s');
                $arrdataen['user_modified'] = $_SESSION['kode'];
                $this->Data_model->updateDataWhere($arrdataen, DB_ARTICLE, $kondisi);
            }
        }

        /*
          if ($cid == "") {
          if (isset($arrdata['article_title']) && $arrdata['article_title'] != null) {
          $arrdata['user_created'] = $next_id;
          $arrdata['user_created'] = $_SESSION['user_id'];
          $this->Data_model->simpanData($arrdata, DB_ARTICLE);
          print_r($arrdata);
          }
          if (isset($arrdataen['article_title']) && $arrdataen['article_title'] != null) {
          $arrdataen['user_created'] = $next_id;
          $arrdataen['user_created'] = $_SESSION['user_id'];
          $this->Data_model->simpanData($arrdataen, DB_ARTICLE);
          echo '<br>=============================<br>';
          print_r($arrdataen);
          }
          } else {
          if (isset($arrdata['article_title']) && $arrdata['article_title'] != null) {
          $kondisi = array('article_id' => $cid, 'lang_id' => 1);
          $arrdata['date_modified'] = date('Y-m-d H:i:s');
          $arrdata['user_modified'] = $_SESSION['kode'];
          $this->Data_model->updateDataWhere($arrdata, DB_ARTICLE, $kondisi);
          print_r($arrdata);
          echo $this->db->last_query();
          }
          if (isset($arrdataen['article_title']) && $arrdataen['article_title'] != null) {
          $kondisi = array('article_id' => $cid, 'lang_id' => 2);
          $arrdataen['date_modified'] = date('Y-m-d H:i:s');
          $arrdataen['user_modified'] = $_SESSION['kode'];
          $this->Data_model->updateDataWhere($arrdata, DB_ARTICLE, $kondisi);
          echo '<br>=============================<br>';
          print_r($arrdataen);
          echo $this->db->last_query();
          }
          }
         */
        //        echo '</pre>';
    }

    function hapus()
    {
        if (IS_AJAX) {
            $this->Data_model->hapusDataWhere(DB_ARTICLE, array('article_id' => $this->input->post('cid'), 'lang_id' => $this->input->post('cod')));
        }
    }

    /*
     * Approvement
     */

    public function approvement()
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
        $konten['tabel'] = DB_ARTICLE;
        $konten['kolom'] = array("Kode", "Bahasa", "Judul", "Publikasi", "Tanggal Publikasi", "Tanggal Dibuat", "");
        $konten['breadcum'] = 'Approvement Artikel';
        $konten['smenu'] = 'artikel';
        $konten['sbmenu'] = 'artikelaprove';
        $konten['css'] = 'approvement/artikel/css';
        $konten['js'] = 'approvement/artikel/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'approvement/artikel/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    function approveArtikel()
    {
        $rol = $this->uri->segment(4);
        $data = $this->fungsi($rol, 1);
        $this->Data_model->updateDataWhere($data, DB_ARTICLE, array('lang_id' => $this->input->post('langid'), 'article_id' => $this->input->post('cid')));
    }

    function unapproveArtikel()
    {
        $rol = $this->uri->segment(4);
        $data = $this->fungsi($rol, 2);
        $this->Data_model->updateDataWhere($data, DB_ARTICLE, array('lang_id' => $this->input->post('langid'), 'article_id' => $this->input->post('cid')));
        echo $this->db->last_query();
    }

    public function approveAll()
    {
        $rol = $this->uri->segment(4);
        $DTO = $this->input->post('DTO');
        $LANG = $this->input->post('DTP');
        $data = $this->fungsi($rol, 1);
        if (isset($DTO)) {
            $assocResult = json_decode($DTO, true);
            $assocResult2 = json_decode($LANG, true);
            $x = 0;
            foreach ($assocResult as $key => $value) {
                $kondisi = array('lang_id' => $assocResult2[$x], 'article_id' => $value);
                $this->Data_model->updateDataWhere($data, DB_ARTICLE, $kondisi);
                $x++;
            }
        }
    }

    public function unapproveAll()
    {
        $rol = $this->uri->segment(4);
        $DTO = $this->input->post('DTO');
        $LANG = $this->input->post('DTP');
        $data = $this->fungsi($rol, 2);
        if (isset($DTO)) {
            $assocResult = json_decode($DTO, true);
            $assocResult2 = json_decode($LANG, true);
            $x = 0;
            foreach ($assocResult as $key => $value) {
                $kondisi = array('lang_id' => $assocResult2[$x], 'article_id' => $value);
                $this->Data_model->updateDataWhere($data, DB_ARTICLE, $kondisi);
                $x++;
            }
        }
    }

    function fungsi($param, $k)
    {
        if ($k == 1) {
            switch ($param):
                case 'e':
                    $data = array('editor_approval' => 1);
                    break;
                case 'm':
                    $data = array('moderator_approval' => 1);
                    break;
                case 'p':
                    $data = array('is_published' => 1, 'publish_date' => date("Y-m-d H:i:s"));
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
                    $data = array('is_published' => 0, 'publish_date' => NULL);
                    break;
            endswitch;
        }
        return $data;
    }

    /*
     * Category
     */

    public function kategori()
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
        $konten['tabel'] = DB_ARTICLE;
        $konten['kolom'] = array("Kode", "Nama Kategori", "");
        $konten['kolom2'] = array("Kategori", "Kode", "Artikel", "");
        $konten['kolom3'] = array("Kode", "Bahasa", "Judul", "Status", "Tanggal", "");
        $konten['breadcum'] = 'Kategori Artikel';
        $konten['smenu'] = 'artikel';
        $konten['sbmenu'] = 'artikelkategori';
        $konten['css'] = 'kategori/artikel/css';
        $konten['js'] = 'kategori/artikel/js';
        $konten['header'] = 'included/header';
        $konten['footer'] = 'included/footer';
        $konten['target'] = 'kategori/artikel/index';
        /*
         * Memanggil role untuk menu aktif
         */
        $konten['menuinfo'] = $this->libglobal->getMenuID($this->uri->uri_string, $_SESSION['role_id'], 'ad_menu_admin');
        $this->load->view('default_admin', $konten);
    }

    public function getKategori()
    {
        $aColumns = array();
        $sTablex = '';
        $sIndexColumn = "cat_id";
        $sTable = DB_CATEGORY;

        $aColumns = array("cat_id", "name", "cat_id");
        $where = "";
        $query = "SELECT * FROM (SELECT cat_id, name FROM  $sTable $where GROUP BY cat_id) as betmen WHERE 1=1 ";
        $tQuery = $query; //"SELECT * FROM ($query) AS tab WHERE 1=1 ";

        echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
    }

    public function getKategoryArtikel()
    {
        $aColumns = array();
        $where = '';
        $sTablex = '';
        $sIndexColumn = "cat_id";
        $sTable = DB_CATEGORY;

        $aColumns = array("cat_id", "article_id", "article_title", "id");
        $where .= ($this->uri->segment(4) != '') ? " AND cat_id=" . $this->uri->segment(4) : '';
        $where2 = str_replace('a.', '', $where);
        $query = "SELECT a.cat_id, a.article_id as article_id, a.name, b.article_id as id, b.article_title 
                        FROM $sTable a right join " . DB_ARTICLE . " b 
                            on a.article_id=b.article_id and a.lang_id=b.lang_id 
                            WHERE 1=1 $where ";
        $tQuery = "SELECT * FROM ($query) AS tab WHERE 1=1 ";

        echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
    }

    public function getList()
    {
        $filtercat = $this->uri->segment(4);
        $whereapprove = " is_published=1 ";
        $kolom = "is_published";
        $kolomplus = "editor_approval,moderator_approval";
        $arrplus1 = "editor_approval";
        $arrplus2 = "moderator_approval";

        $aColumns = array("article_id", "lang_id", "article_title", "$kolom", "publish_date", "date_created", "$arrplus1", "$arrplus2");
        $sIndexColumn = 'article_id';
        $sTablex = '';
        $sTable = DB_ARTICLE;
        $grpby = "GROUP BY article_id,lang_id,article_title,$kolom,publish_date,date_created";
        $sWhere = '';
        $xWhere = " AND article_id not in (SELECT article_id FROM " . DB_CATEGORY . " WHERE cat_id=" . $filtercat . " AND cat_id<>'') ";
        $sQuery = "SELECT * FROM (SELECT article_id,lang_id,article_title,$kolom,publish_date,date_created,COUNT(article_id) as artcount,$kolomplus 
                    FROM 
                (
                    SELECT
                            article_id,lang_id,article_title,$kolom,publish_date,date_created,$kolomplus 
                        FROM
                              $sTable WHERE 1=1 AND $whereapprove $xWhere   
                ) as aep
                WHERE 1=1 $xWhere 
                GROUP BY article_id,lang_id,article_title,$kolom,publish_date,date_created) as betmen ";
        $tQuery = "SELECT * FROM ($sQuery) AS tab WHERE 1=1 ";

        echo $this->libglobal->pagingData($aColumns, $sIndexColumn, $sTable, $tQuery, $sTablex);
    }

    function simpanKeKategori()
    {
        $DTO = $this->input->post('DTO');
        $CAT = $this->input->post('cat_id');
        $NME = $this->input->post('cat_name');
        $valreturn = '';
        if ($CAT == '') {
            $maxid = $this->db->select("max(cat_id) as maxid")->from(DB_CATEGORY)->get();
            $resmax = $maxid->row();
            $CAT = $resmax->maxid + 1;
            $valreturn = $CAT;
        }

        if (isset($DTO)) {
            $assocResult = json_decode($DTO, true);
            foreach ($assocResult as $key => $value) {
                $arrdata = array('cat_id' => $CAT, 'name' => $NME, 'article_id' => $value, 'lang_id' => 1);
                $this->Data_model->simpanData($arrdata, DB_CATEGORY);
                //                echo $this->db->last_query();
            }
        }
        echo $valreturn;
    }

    function hapusDariKategori()
    {
        $where = array('article_id' => $this->input->post('cid'), 'cat_id' => $this->input->post('catid'));
        $this->Data_model->hapusDataWhere(DB_CATEGORY, $where);
    }
}
