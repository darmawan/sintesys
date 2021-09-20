<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Event extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('event_model');
//        if ($this->session->userdata('is_logged_in') != 1) {
//            redirect('kuwu/login');
//        }
    }

    function index() {

        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('../kuwu/login');
        }

        if (IS_AJAX) {
            
        } else {
            #$konten['rowcat'] = $this->category_model->getForSelect();
            #echo $this->db->last_query();
            $konten['page'] = 'event/index';
            $konten['nav'] = 'event';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }

    function getEvent() {
        $aColumns = array("event_id", "lang_id", "name", "time_start", "time_finish", "place", "descriptionevent", "date_created", "user_created", "editor_approval", "moderator_approval", "is_published", "date_published", "date_modified", "user_modified", "artcount");
        $sIndexColumn = "event_id";
        $sTable = DB_EVENT;
        $sLimit = "";
        $xDisplayStart = (isset($_GET['iDisplayStart']) && $_GET['iDisplayStart'] != "") ? $_GET['iDisplayStart'] : ((isset($_POST['iDisplayStart'])) ? $_POST['iDisplayStart'] : '' );
        $xDisplayLength = (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != "") ? $_GET['iDisplayLength'] : ((isset($_POST['iDisplayLength'])) ? $_POST['iDisplayLength'] : '' );
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $this->libglobal->mssql_escape($xDisplayLength) : ($this->libglobal->mssql_escape($xDisplayStart) + 10);
        }

        $xOrder = $_POST['iSortCol_0'];
        $xSortingCols = $_POST['iSortingCols'];
        if (isset($xOrder)) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($xSortingCols); $i++) {
                $xSortCol = $_POST['iSortCol_' . $i];
                $xSortDir = $_POST['sSortDir_' . $i];
                $xSortable = $_POST['bSortable_' . intval($xSortCol)];

                if ($xSortable == "true") {
                    $sOrder .= $aColumns[intval($xSortCol)] . "
				 	" . $xSortDir . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        $sWhere = "";
        $xSearch = (isset($_GET['sSearch']) && $_GET['sSearch'] != "") ? $_GET['sSearch'] : ((isset($_POST['sSearch'])) ? $_POST['sSearch'] : '' );
        if ($xSearch != "") {
            $sWhere = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR "; //$sWhere .= $aColumns[$i]." LIKE '%".$this->libglobal->mssql_escape( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] != "") ? $_GET['bSearchable_' . $i] : ((isset($_POST['bSearchable_' . $i])) ? $_POST['bSearchable_' . $i] : '' );
            $xSearch = (isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != "") ? $_GET['sSearch_' . $i] : ((isset($_POST['sSearch_' . $i])) ? $_POST['sSearch_' . $i] : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere == "") {
                    $sWhere = "AND "; //"WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mssql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = (($sLimit - $xDisplayLength) == 0) ? '' : ',' . ($sLimit - $xDisplayLength);
        $sQuery = "
                SELECT
                          event_id ,lang_id ,name ,place ,descriptionevent ,time_start ,time_finish ,date_created ,user_created ,editor_approval ,moderator_approval ,is_published ,date_published ,date_modified ,user_modified,COUNT(event_id) as artcount 
                     FROM
                         $sTable WHERE 1=1 $sWhere 
                         GROUP BY event_id ,lang_id ,name ,place ,descriptionevent ,time_start ,time_finish ,date_created ,user_created ,editor_approval ,moderator_approval ,is_published ,date_published ,date_modified ,user_modified 
                    $sOrder LIMIT $sLimit $nextLimit";

        $rResult = $this->db->query($sQuery);
        #echo $this->db->last_query();
        $sQuery = "
		SELECT COUNT(*) as aTot FROM $sTable LIMIT 1
	";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as aTot
		FROM   $sTable LIMIT 1
	";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = (isset($_GET['sEcho']) && $_GET['sEcho'] != "") ? $_GET['sEcho'] : ((isset($_POST['sEcho'])) ? $_POST['sEcho'] : '' );
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
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

    function getApprove() {
        $konten['page'] = 'event/approvement';
        $konten['nav'] = 'event';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function getList() {
        $approvecat = $this->uri->segment(3);
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
                $whereapprove = " AND is_published=1 ";
                $kolom = "is_published";
                $kolomplus = "editor_approval,moderator_approval";
                $arrplus1 = "editor_approval";
                $arrplus2 = "moderator_approval";
                break;
        endswitch;

        $aColumns = array("event_id", "lang_id", "name", "$kolom", "date_published", "date_created", "$arrplus1", "$arrplus2");
        $sIndexColumn = "event_id";
        $sTable = DB_EVENT;
        $sLimit = "";
        $xDisplayStart = (isset($_GET['iDisplayStart']) && $_GET['iDisplayStart'] != "") ? $_GET['iDisplayStart'] : ((isset($_POST['iDisplayStart'])) ? $_POST['iDisplayStart'] : '' );
        $xDisplayLength = (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != "") ? $_GET['iDisplayLength'] : ((isset($_POST['iDisplayLength'])) ? $_POST['iDisplayLength'] : '' );
        if (isset($xDisplayStart) && $xDisplayLength != '-1') {
            $sLimit = ($xDisplayStart == 0) ? $this->libglobal->mssql_escape($xDisplayLength) : ($this->libglobal->mssql_escape($xDisplayStart) + 10);
        }

        $xOrder = $_POST['iSortCol_0'];
        $xSortingCols = $_POST['iSortingCols'];
        if (isset($xOrder)) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($xSortingCols); $i++) {
                $xSortCol = $_POST['iSortCol_' . $i];
                $xSortDir = $_POST['sSortDir_' . $i];
                $xSortable = $_POST['bSortable_' . intval($xSortCol)];

                if ($xSortable == "true") {
                    $sOrder .= $aColumns[intval($xSortCol)] . "
				 	" . $xSortDir . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        $sWhere = "1=1 ";
        $xSearch = (isset($_GET['sSearch']) && $_GET['sSearch'] != "") ? $_GET['sSearch'] : ((isset($_POST['sSearch'])) ? $_POST['sSearch'] : '' );
        if ($xSearch != "") {
            $sWhere = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR "; //$sWhere .= $aColumns[$i]." LIKE '%".$this->libglobal->mssql_escape( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] != "") ? $_GET['bSearchable_' . $i] : ((isset($_POST['bSearchable_' . $i])) ? $_POST['bSearchable_' . $i] : '' );
            $xSearch = (isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != "") ? $_GET['sSearch_' . $i] : ((isset($_POST['sSearch_' . $i])) ? $_POST['sSearch_' . $i] : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere == "") {
                    $sWhere = "AND "; //"WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mssql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = (($sLimit - $xDisplayLength) == 0) ? '' : ',' . ($sLimit - $xDisplayLength);
        $sOrder = ($sOrder == '') ? "ORDER BY " . $sIndexColumn . " DESC" : $sOrder;
        $sQuery = "SELECT event_id,lang_id,name,$kolom,date_published,date_created,$kolomplus FROM 
                (
                    SELECT
                            event_id,lang_id,name,$kolom,date_published,date_created,$kolomplus 
                        FROM
                              $sTable WHERE $sWhere $whereapprove   
                ) as aep 
                WHERE $sWhere $sOrder LIMIT $sLimit $nextLimit";

        $rResult = $this->db->query($sQuery);

        $sQuery = "
		SELECT COUNT(*) as aTot FROM $sTable WHERE $sWhere $whereapprove
	";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as aTot
		FROM   $sTable WHERE $sWhere $whereapprove 
	";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = (isset($_GET['sEcho']) && $_GET['sEcho'] != "") ? $_GET['sEcho'] : ((isset($_POST['sEcho'])) ? $_POST['sEcho'] : '' );
        $output = array(
            "sEcho" => intval($xEcho),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        $resultx = $rResult->result_array();
        foreach ($resultx as $aRow) {
            $row = array();
            // Add the row ID and class to the object
            $row['DT_RowId'] = 'row_' . $aRow['event_id'];
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

    function getForm() {
        $param = ($this->uri->segment(3) == '') ? '' : $this->uri->segment(3);
        $paramid = ($this->uri->segment(4) == '') ? '' : $this->uri->segment(4);
        $optional = ($this->uri->segment(5) == '') ? '' : $this->uri->segment(5);
        $konten['fedit'] = false;
        $konten['lang_id'] = '';
        $konten['param_id'] = '';
        $konten['optional'] = $optional;
        if ($param == '') {

            #echo 'buat baru';
        } else {
            $konten['fedit'] = true;
            $konten['lang_id'] = $param;
            $konten['param_id'] = $paramid;
            $konten['rowdata'] = $this->event_model->ambilArticle($paramid, $param);

            $bahasa = ($param == 1) ? 'Bahasa Indonesia' : 'Bahasa Inggris';
        }

        $articlestat = $this->db->from(DB_STATUS)->get();
        $konten['rowstat'] = $articlestat->result();

        $konten['page'] = 'event/form';
        $konten['nav'] = 'event';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function saveForm() {
        if ($_POST['_edit'] == 1) {
            $this->updateForm();
        } else {
            if ($this->input->post('name')!='') {
                $title_indo = $this->input->post('name');
                $sum_indo = $this->input->post('place');
                $content_indo = $this->input->post('descriptionevent');
                $date_created = date('Y/m/d H:i:s',strtotime($this->input->post('date_created')));
                $date_published = date('Y/m/d H:i:s',strtotime($this->input->post('date_published')));
                $time_start = date('Y/m/d H:i:s', strtotime($this->input->post('time_start')));
                $time_finish = date('Y/m/d H:i:s', strtotime($this->input->post('time_finish')));
                $status_article = $this->input->post('status_article');
            }

            if (isset($_POST['_aid']) && $_POST['_aid'] != '') {
                $next_id = $_POST['_aid'];
            } else {
                $last_id_article_in_db = $this->event_model->getLastIdDb();
                $next_id = $last_id_article_in_db['event_id'] + 1;
            }
            if (isset($title_indo) && $title_indo != null) {
                $data = array(
                    'event_id' => $next_id,
                    'lang_id' => 1,
                    'name' => $title_indo,
                    'place' => $sum_indo,
                    'descriptionevent' => $content_indo,
                    'date_created' => $date_created,
                    'time_start' => $time_start,
                    'time_finish' => $time_finish,
                    'user_created' => $this->session->userdata('username'));
                $this->event_model->simpanArticle($data);
            }
        };

        #$this->index();
        redirect('kuwu/event');
    }

    function updateForm() {
        $id = $_POST['_aid'];
        $lang = $_POST['_lang'];
        if ($lang == 1) {
            $title = $this->input->post('name');
            $sum = $this->input->post('place');
            $content = $this->input->post('descriptionevent');
            $date_created = date('Y/m/d H:i:s',strtotime($this->input->post('date_created')));
            $date_published = date('Y/m/d H:i:s',strtotime($this->input->post('date_published'))); 
            $time_start = date('Y/m/d H:i:s', strtotime($this->input->post('time_start')));
            $time_finish = date('Y/m/d H:i:s', strtotime($this->input->post('time_finish')));
            $status_article = $this->input->post('status_article');
        }

        switch ($status_article) {
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
            'name' => $title,
            'place' => $sum,
            'descriptionevent' => $content,
            'editor_approval' => $editor,
            'moderator_approval' => $moderator,
            'is_published' => $is_published,
            'date_created' => $date_created,
            'date_modified' => date("Y-m-d H:i:s"),
            'time_start' => $time_start,
            'time_finish' => $time_finish,
            'user_created' => $this->session->userdata('username'));
        $this->event_model->updateArticle($id, $lang, $data);
    }

    function removeData() {
        if (IS_AJAX) {
            $this->event_model->deleteArticle($this->input->post('cid'), $this->input->post('langid'));
        }
    }

    function unPublish() {
        if (IS_AJAX) {
            $data = array('is_published' => 0, 'date_published' => NULL);
            $this->event_model->updateArticle($this->input->post('cid'), $this->input->post('langid'), $data);
            #echo $this->db->last_query();
        }
    }

    function approveEvent() {
        switch ($this->uri->segment(3)):
            case 'e':
                $data = array('editor_approval' => 1);
                break;
            case 'm':
                $data = array('moderator_approval' => 1);
                break;
            case 'p':
                $data = array('is_published' => 1, 'date_published' => date("Y-m-d H:i:s"));
                break;
        endswitch;
        $this->event_model->updateArticle($this->input->post('cid'), $this->input->post('langid'), $data);
    }

    function unapproveEvent() {
        switch ($this->uri->segment(3)):
            case 'e':
                $data = array('editor_approval' => 0);
                break;
            case 'm':
                $data = array('moderator_approval' => 0);
                break;
            case 'p':
                $data = array('is_published' => 0, 'date_published' => NULL);
                break;
        endswitch;
        $this->event_model->updateArticle($this->input->post('cid'), $this->input->post('langid'), $data);
    }

}
