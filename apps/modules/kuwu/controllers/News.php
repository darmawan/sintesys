<?php

if (!\defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class News extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('News_model');
//        $this->load->model('category_model');
    }

    public function index() {
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('kuwu/login');
        }
        if (IS_AJAX) {
            
        } else {
            $chkrole = $this->db->get_where('ad_menu_role', array('kode' => 3))->row();
            $konten['edit'] = $chkrole->edit;
            $konten['update'] = $chkrole->update;
            $konten['delete'] = $chkrole->delete;
            $konten['btn'] = $chkrole->btn;

            $konten['page'] = 'news/index';
            $konten['nav'] = 'news';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }

    public function getNews() {
        $aColumns = array("news_id", "lang_id", "news_title", "date_created", "is_published", "publish_date", "artcount");
        $sIndexColumn = "news_id";
        $sTable = DB_NEWS;
        $sLimit = "";

        $xDisplayStart = ($this->input->get('iDisplayStart') != "") ? $this->input->get('iDisplayStart') : (($this->input->post('iDisplayStart') && $this->input->post('iDisplayStart') != '') ? $this->input->post('iDisplayStart') : '');
        $xDisplayLength = ($this->input->get('iDisplayLength') != "") ? $this->input->get('iDisplayLength') : (($this->input->post('iDisplayLength') && $this->input->post('iDisplayLength') != '') ? $this->input->post('iDisplayLength') : '');
        if (($xDisplayStart != '') && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $xDisplayLength : (($xDisplayStart) + 10);
        }
        $sLimit = ($sLimit == '') ? 10 : $sLimit;
        $xOrder = $this->input->post('iSortCol_0');
        $xSortingCols = $this->input->post('iSortingCols');
        if (isset($xOrder)) {
            $sOrder0 = "ORDER BY  ";
            for ($i = 0; $i < intval($xSortingCols); $i++) {
                $xSortCol = $this->input->post('iSortCol_' . $i);
                $xSortDir = $this->input->post('sSortDir_' . $i);
                $xSortable = $this->input->post('bSortable_' . intval($xSortCol));
                if ($xSortable == "true") {
                    $sOrder0 .= $aColumns[intval($xSortCol)] . " " . $xSortDir . ", ";
                }
            }
            $sOrder = substr_replace($sOrder0, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $sWhere = "";
        $xSearch = ($this->input->get('sSearch') != "") ? $this->input->get('sSearch') : (($this->input->post('sSearch')) ? $this->input->post('sSearch') : '');
        if ($xSearch != "") {
            $sWhere0 = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere0 .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere0, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = ($this->input->get('bSearchable_' . $i) != "") ? $this->input->get('bSearchable_' . $i) : (($this->input->post('bSearchable_' . $i)) ? $this->input->post('bSearchable_' . $i) : '');
            $xSearch = ($this->input->get('sSearch_' . $i) != "") ? $this->input->get('sSearch_' . $i) : (($this->input->post('sSearch_' . $i)) ? $this->input->post('sSearch_' . $i) : '');
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere === ""): $sWhere = "AND ";
                else:$sWhere .= " AND ";
                endif;

                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mysql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = (($sLimit - $xDisplayLength) <= 0) ? '' : ($sLimit - $xDisplayLength) . ',';
        $sQuerys = "SELECT a.news_id,a.lang_id,a.news_title,a.is_published,a.publish_date,DATE_FORMAT(a.date_created,'%d/%m/%Y') as date_created, z.jumlah as artcount FROM ad_news a JOIN (SELECT news_id, count(news_id) as jumlah FROM ad_news GROUP BY news_id) as z ON z.news_id=a.news_id  GROUP BY a.news_id, a.lang_id, a.news_title, a.is_published, a.publish_date, a.date_created";
        // $sQuerys   = "SELECT news_id,lang_id,news_title,is_published,publish_date,DATE_FORMAT(date_created,'%d/%m/%Y') as date_created,COUNT(news_id) as artcount
        //            FROM $sTable  GROUP BY news_id, lang_id, news_title, is_published, publish_date, date_created ";
        $sQuery = "SELECT news_id,lang_id,news_title,is_published,publish_date,date_created,artcount
                   FROM ($sQuerys) as aep WHERE 1=1 $sWhere

                   $sOrder LIMIT  $nextLimit $sLimit";
        $rResult = $this->db->query($sQuery);
        $sQuery = "SELECT COUNT(*) as aTot FROM ($sQuerys) as aep WHERE 1=1 $sWhere ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
        SELECT COUNT(" . $sIndexColumn . ") as aTot
        FROM   ($sQuerys) as aep WHERE 1=1 $sWhere
    ";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = ($this->input->get('sEcho') != "") ? $this->input->get('sEcho') : (($this->input->post('sEcho')) ? $this->input->post('sEcho') : '');
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );

        $resultx = $rResult->result_array();
        foreach ($resultx as $aRow) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
                } else if ($aColumns[$i] != ' ') {
                    /* General output */
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    public function getList() {
        $approvecat = $this->uri->segment(4);
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
                $whereapprove = " AND is_published=1 ";
                $kolom = "is_published";
                $kolomplus = "editor_approval,moderator_approval";
                $arrplus1 = "editor_approval";
                $arrplus2 = "moderator_approval";
                break;
        endswitch;

        $aColumns = array("news_id", "lang_id", "news_title", "$kolom", "publish_date", "date_created", "artcount", "$arrplus1", "$arrplus2");
        $sIndexColumn = "news_id";
        $sTable = DB_NEWS;
        $sLimit = "";
        $xDisplayStart = ($this->input->get('iDisplayStart') != "") ? $this->input->get('iDisplayStart') : (($this->input->post('iDisplayStart') && $this->input->post('iDisplayStart') != '') ? $this->input->post('iDisplayStart') : '');
        $xDisplayLength = ($this->input->get('iDisplayLength') != "") ? $this->input->get('iDisplayLength') : (($this->input->post('iDisplayLength') && $this->input->post('iDisplayLength') != '') ? $this->input->post('iDisplayLength') : '');
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $xDisplayLength : (($xDisplayStart) + 10);
        }
        //$sLimit = ($sLimit=='') ? 10 : $sLimit;
        $xOrder = $this->input->post('iSortCol_0');
        $xSortingCols = $this->input->post('iSortingCols');
        if (isset($xOrder)) {
            $sOrder0 = "ORDER BY  ";
            for ($i = 0; $i < intval($xSortingCols); $i++) {
                $xSortCol = $this->input->post('iSortCol_' . $i);
                $xSortDir = $this->input->post('sSortDir_' . $i);
                $xSortable = $this->input->post('bSortable_' . intval($xSortCol));

                if ($xSortable == "true") {
                    $sOrder0 .= $aColumns[intval($xSortCol)] . "
                    " . $xSortDir . ", ";
                }
            }

            $sOrder = substr_replace($sOrder0, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        $sWhere = "";
        $xSearch = ($this->input->get('sSearch') != "") ? $this->input->get('sSearch') : (($this->input->post('sSearch')) ? $this->input->post('sSearch') : '');
        if ($xSearch != "") {
            $sWhere0 = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere0 .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere0, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = ($this->input->get('bSearchable_' . $i) != "") ? $this->input->get('bSearchable_' . $i) : (($this->input->post('bSearchable_' . $i)) ? $this->input->post('bSearchable_' . $i) : '');
            $xSearch = ($this->input->get('sSearch_' . $i) != "") ? $this->input->get('sSearch_' . $i) : (($this->input->post('sSearch_' . $i)) ? $this->input->post('sSearch_' . $i) : '');
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere == "") {
                    $sWhere = "AND "; //"WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mysql_escape($xSearch) . "%' ";
            }
        }
        $sWhere .= "";
        $nextLimit = (($sLimit - $xDisplayLength) == 0) ? '' : ($sLimit - $xDisplayLength) . ',';
        $sOrder = ($sOrder == '') ? "ORDER BY " . $sIndexColumn . " DESC" : $sOrder;
        $sQuery = "SELECT
                          news_id,lang_id,news_title,$kolom,publish_date,date_created,count(news_id) as artcount,$kolomplus
                     FROM
                         $sTable
                    WHERE 1=1 $sWhere $whereapprove
                    GROUP BY news_id,lang_id,news_title,moderator_approval,publish_date,date_created,editor_approval,is_published
                    $sOrder LIMIT $nextLimit $sLimit";

        $rResult = $this->db->query($sQuery);

        $sQuery = "
        SELECT COUNT(*) as aTot FROM $sTable WHERE 1=1 $whereapprove
    ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
        SELECT COUNT(" . $sIndexColumn . ") as aTot
        FROM   $sTable WHERE 1=1 $whereapprove
    ";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = ($this->input->get('sEcho') != "") ? $this->input->get('sEcho') : (($this->input->post('sEcho')) ? $this->input->post('sEcho') : '');
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );

        $resultx = $rResult->result_array();
        foreach ($resultx as $aRow) {
            $row = array();
            // Add the row ID and class to the object
            $row['DT_RowId'] = 'row_' . $aRow['news_id'];
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
                } else if ($aColumns[$i] != ' ') {
                    /* General output */
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    public function getApprove() {
        $konten['page'] = 'news/approvement';
        $konten['nav'] = 'news';
        $this->load->view('dashboard', array('data' => $konten));
    }

    public function getForm() {
        $param = ($this->uri->segment(4) == '') ? '' : $this->uri->segment(4);
        $paramid = ($this->uri->segment(5) == '') ? '' : $this->uri->segment(5);
        $optional = ($this->uri->segment(6) == '') ? '' : $this->uri->segment(6);
        $konten['fedit'] = false;
        $konten['lang_id'] = '';
        $konten['param_id'] = '';
        $konten['optional'] = $optional;
        if ($param == '') {
            /* buat baru  */
        } else {
            $konten['fedit'] = true;
            $konten['lang_id'] = $param;
            $konten['param_id'] = $paramid;
            $konten['rowdata'] = $this->News_model->ambilNews($paramid, $param);
            $bahasa = ($param == 1) ? 'Bahasa Indonesia' : 'Bahasa Inggris';
        }
        $this->db->flush_cache();
        $newstype = $this->db->from(DB_TYPE)->where('type_grp', 'news')->get();
        $konten['rowtype'] = $newstype->result();
        $this->db->flush_cache();
        $newscat = $this->db->select('cat_id,name')->from(DB_CATEGORY)->group_by('cat_id,name')->get();
        $konten['rowcat'] = $newscat->result();
        $this->db->flush_cache();
        $newsstat = $this->db->from(DB_STATUS)->get();
        $konten['rowstat'] = $newsstat->result();

        $konten['page'] = 'news/form';
        $konten['nav'] = 'news';
        $this->load->view('dashboard', array('data' => $konten));
    }

    public function saveForm() {
        if ($this->input->post('_edit') == 1) {
            $this->updateForm();
        } else {
            $status_news = '';
            if ($this->input->post('news_title')) {
                $title_indo = $this->input->post('news_title');
                $sum_indo = $this->input->post('summary');
                $content_indo = $this->input->post('content');
                $type_id_indo = $this->input->post('type_id');
                $cat_id_indo = $this->input->post('cat_id');
                $date_created = date('Y/m/d H:i:s', strtotime($this->input->post('date_created')));
                $publish_date = date('Y/m/d H:i:s', strtotime($this->input->post('publish_date')));
                $page_title = $this->input->post('page_title');
                $tags = $this->input->post('tags');
                $status_news = $this->input->post('status_news');
            }
            if (isset($_POST['news_title_en'])) {
                $title_eng = (!isset($_POST['news_title_en'])) ? $_POST['title'] : $_POST['news_title_en'];
                $sum_eng = $this->input->post('summary_en');
                $content_eng = $_POST['content_en'];
                $type_id_eng = $this->input->post('type_id_en');
                $page_title_eng = $this->input->post('page_title_en');
                $tags_eng = $this->input->post('tags_en');
                $date_created_eng = $_POST['date_created_en'];
                $publish_date_eng = $_POST['publish_date_en'];
                $status_news = (!isset($_POST['status_news_en'])) ? $_POST['status_news'] : $_POST['status_news_en'];
            }
            switch ($status_news) {
                case '0':
                    $editor = '0';
                    $moderator = '0';
                    $is_published = '0';
                    $publish_date = null;
                    break;
                case '1':
                    $editor = '1';
                    $moderator = '0';
                    $is_published = '0';
                    $publish_date = null;
                    break;
                case '2':
                    $editor = '1';
                    $moderator = '1';
                    $is_published = '0';
                    $publish_date = null;
                    break;
                case '3':
                    $editor = '1';
                    $moderator = '1';
                    $is_published = '1';
                    $publish_date = $publish_date;
                    break;
            }

            $category = (isset($cat_id_indo) && $cat_id_indo != '') ? $cat_id_indo : (isset($cat_id_eng) && $cat_id_eng != '') ? $cat_id_eng : '';

            if ($this->input->post('_aid') <> '') {
                $next_id = $this->input->post('_aid');
            } else {
                $last_id_news_in_db = $this->News_model->getLastIdDb();
                $next_id = $last_id_news_in_db['news_id'] + 1;
            }

            if ($category != '') {
                $whatcat = $this->News_model->getTheCategory($category);
                $isthere = $this->News_model->checkTheCategory($next_id);
                if ($isthere) {
                    
                } else {
                    $data = array('cat_id' => $category
                        , 'name' => $whatcat->name
                        , 'news_id' => $next_id
                        , 'lang_id' => 1);
                    $this->News_model->saveToCategory($data);
                }
            }

            if (isset($title_indo) && $title_indo != null) {
                $data = array(
                    'news_id' => $next_id,
                    'lang_id' => 1,
                    'news_title' => $title_indo,
                    'summary' => $sum_indo,
                    'content' => $content_indo,
                    'type_id' => $type_id_indo,
                    'editor_approval' => $editor,
                    'moderator_approval' => $moderator,
                    'is_published' => $is_published,
                    'publish_date' => ($publish_date == '') ? null : $publish_date,
                    'date_created' => ($date_created == '') ? date('Y/m/d h:i:s') : $date_created,
                    'page_title' => $page_title,
                    'tags' => $tags,
                    'user_created' => $this->session->userdata('username'));
                print_r($data);
                $this->News_model->simpanNews($data);
            }
            if (isset($title_eng) && $title_eng != '') {
                $data = array(
                    'news_id' => $next_id,
                    'lang_id' => 2,
                    'news_title' => $title_eng,
                    'summary' => $sum_eng,
                    'content' => $content_eng,
                    'type_id' => $type_id_eng,
                    'editor_approval' => $editor,
                    'moderator_approval' => $moderator,
                    'is_published' => $is_published,
                    'publish_date' => ($publish_date_eng == '') ? null : $publish_date_eng,
                    'date_created' => ($date_created_eng == '') ? date('Y/m/d h:i:s') : $date_created_eng,
                    'page_title' => $page_title_eng,
                    'tags' => $tags_eng,
                    'user_created' => $this->session->userdata('username'));
                // print_r($data);
                $this->News_model->simpanNews($data);
            }
        };
        redirect('kuwu/news');
    }

    public function updateForm() {
        $id = $this->input->post('_aid');
        $lang = $this->input->post('_lang');

        if ($lang == 1) {
            $title = $this->input->post('news_title');
            $sum = $this->input->post('summary');
            $content = $this->input->post('content');
            $type_id = $this->input->post('type_id');
            $cat_id = $this->input->post('cat_id');
            $date_created = date('Y/m/d H:i:s', strtotime($this->input->post('date_created')));
            $publish_date = date('Y/m/d H:i:s', strtotime($this->input->post('publish_date')));
            $page_title = $this->input->post('page_title');
            $tags = $this->input->post('tags');
            $status_news = $this->input->post('status_news');
        } else {
            $title = $this->input->post('news_title_en');
            $sum = $this->input->post('summary_en');
            $content = $this->input->post('content_en');
            $type_id = $this->input->post('type_id_en');
            $cat_id = $this->input->post('cat_id_en');
            $date_created = date('Y/m/d H:i:s', strtotime($this->input->post('date_created_en')));
            $publish_date = date('Y/m/d H:i:s', strtotime($this->input->post('publish_date_en')));
            $page_title = $this->input->post('page_title_en');
            $tags = $this->input->post('tags_en');
            $status_news = $this->input->post('status_news_en');
        }

        $category = ($cat_id != '') ? $cat_id : '';
        if ($category != '') {
            $whatcat = $this->News_model->getTheCategory($category);
            $isthere = $this->News_model->checkTheCategory($id);
            if ($whatcat) {
                if ($isthere) {
                    $data = array('cat_id' => $category
                        , 'name' => $whatcat->name
                        , 'lang_id' => 1);
                    $this->News_model->updateToCategory($isthere->cat_id, $id, $data);
                } else {
                    $data = array('cat_id' => $category
                        , 'name' => $whatcat->name
                        , 'news_id' => $id
                        , 'lang_id' => 1);
                    $this->News_model->saveToCategory($data);
                }
            }
        }

        switch ($status_news) {
            case '0':
                $editor = '0';
                $moderator = '0';
                $is_published = '0';
                break;
            case '1':
                $editor = '1';
                $moderator = '0';
                $is_published = '0';
                break;
            case '2':
                $editor = '1';
                $moderator = '1';
                $is_published = '0';
                break;
            case '3':
                $editor = '1';
                $moderator = '1';
                $is_published = '1';
                break;
        }
        $data = array(
            'news_title' => $title,
            'summary' => $sum,
            'content' => $content,
            'type_id' => $type_id,
            'editor_approval' => $editor,
            'moderator_approval' => $moderator,
            'is_published' => $is_published,
            'publish_date' => $publish_date,
            'date_created' => $date_created,
            'date_modified' => date("Y-m-d H:i:s"),
            'page_title' => $page_title,
            'tags' => $tags,
            'user_created' => $this->session->userdata('username'));
        $this->News_model->updateNews($id, $lang, $data);
    }

    public function removeData() {
        if (IS_AJAX) {
            $this->News_model->deleteNews($this->input->post('cid'), $this->input->post('langid'));
        }
    }

    public function unPublish() {
        if (IS_AJAX) {
            $data = array('is_published' => 0, 'publish_date' => null);
            $this->News_model->updateNews($this->input->post('cid'), $this->input->post('langid'), $data);
            #echo $this->db->last_query();
        }
    }

    public function approveNews() {
        switch ($this->uri->segment(4)):
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
        $this->News_model->updateNews($this->input->post('cid'), $this->input->post('langid'), $data);
    }

    public function unapproveNews() {
        switch ($this->uri->segment(3)):
            case 'e':
                $data = array('editor_approval' => 0);
                break;
            case 'm':
                $data = array('moderator_approval' => 0);
                break;
            case 'p':
                $data = array('is_published' => 0, 'publish_date' => null);
                break;
        endswitch;
        $this->News_model->updateNews($this->input->post('cid'), $this->input->post('langid'), $data);
    }

}
