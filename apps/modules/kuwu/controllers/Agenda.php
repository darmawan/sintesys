<?php

if (!\defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Agenda extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('agenda_model');
        $this->load->model('agenda_category_model');
    }

    public function index() {
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            redirect('kuwu/login');
        }
        if (IS_AJAX) {
            
        } else {
            $chkrole = $this->db->get_where(DB_MENU_ROLE, array('kode' => 32))->row();
            $konten['edit'] = $chkrole->edit;
            $konten['update'] = $chkrole->update;
            $konten['delete'] = $chkrole->delete;
            $konten['btn'] = $chkrole->btn;

            $konten['page'] = 'agenda/index';
            $konten['nav'] = 'agenda';
            $this->load->view('dashboard', array('data' => $konten));
        }
    }

    public function getAgenda() {
        $aColumns = array("agenda_id", "name", "time_start", "category", "dept", "is_published", "description");
        $sIndexColumn = "agenda_id";
        $sTable = DB_AGENDA;
        $sLimit = "";

        $xDisplayStart = ($this->input->get('iDisplayStart') != "") ? $this->input->get('iDisplayStart') : (($this->input->post('iDisplayStart') && $this->input->post('iDisplayStart') != '') ? $this->input->post('iDisplayStart') : '' );
        $xDisplayLength = ($this->input->get('iDisplayLength') != "") ? $this->input->get('iDisplayLength') : (($this->input->post('iDisplayLength') && $this->input->post('iDisplayLength') != '') ? $this->input->post('iDisplayLength') : '' );
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
        $xSearch = ($this->input->get('sSearch') != "") ? $this->input->get('sSearch') : (($this->input->post('sSearch')) ? $this->input->post('sSearch') : '' );
        if ($xSearch != "") {
            $sWhere0 = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere0 .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere0, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = ($this->input->get('bSearchable_' . $i) != "") ? $this->input->get('bSearchable_' . $i) : (($this->input->post('bSearchable_' . $i)) ? $this->input->post('bSearchable_' . $i) : '' );
            $xSearch = ($this->input->get('sSearch_' . $i) != "") ? $this->input->get('sSearch_' . $i) : (($this->input->post('sSearch_' . $i)) ? $this->input->post('sSearch_' . $i) : '' );
            if ($xSearchable == "true" && $xSearch != '') {
                if ($sWhere === "") : $sWhere = "AND ";
                else : $sWhere .= " AND ";
                endif;

                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->libglobal->mysql_escape($xSearch) . "%' ";
            }
        }

        $nextLimit = (($sLimit - $xDisplayLength) <= 0) ? '' : ($sLimit - $xDisplayLength) . ',';
        $sQuery = "SELECT category,agenda_id,lang_id,name,is_published,date_published,DATE_FORMAT(date_created,'%d/%m/%Y') as date_created,dept,description, DATE_FORMAT(time_start,'%d/%m/%Y %H:%i:%s') AS time_start
                   FROM $sTable WHERE 1=1 $sWhere 
                   GROUP BY category, agenda_id  
                   $sOrder LIMIT  $nextLimit $sLimit";
        $rResult = $this->db->query($sQuery);
        $sQuery = "SELECT COUNT(*) as aTot FROM $sTable WHERE 1=1 $sWhere ";
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->row();

        $iFilteredTotal = $aResultFilterTotal->aTot;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as aTot
		FROM   $sTable WHERE 1=1 $sWhere 
	";
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->aTot;

        $xEcho = ($this->input->get('sEcho') != "") ? $this->input->get('sEcho') : (($this->input->post('sEcho')) ? $this->input->post('sEcho') : '' );
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

    function getList() {
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
            default :
                $whereapprove = " AND is_published=1 ";
                $kolom = "is_published";
                $kolomplus = "editor_approval,moderator_approval";
                $arrplus1 = "editor_approval";
                $arrplus2 = "moderator_approval";
                break;
        endswitch;
        $aColumns = array("agenda_id", "name", "time_start", "$kolom", "category","dept","date_created","$arrplus1", "$arrplus2");
        #$aColumns = array("agenda_id", "lang_id", "agenda_title", "$kolom", "date_published", "date_created", "artcount", "$arrplus1", "$arrplus2");
        $sIndexColumn = "agenda_id";
        $sTable = DB_AGENDA;
        $sLimit = "";
        $xDisplayStart = ($this->input->get('iDisplayStart') != "") ? $this->input->get('iDisplayStart') : (($this->input->post('iDisplayStart') && $this->input->post('iDisplayStart') != '') ? $this->input->post('iDisplayStart') : '' );
        $xDisplayLength = ($this->input->get('iDisplayLength') != "") ? $this->input->get('iDisplayLength') : (($this->input->post('iDisplayLength') && $this->input->post('iDisplayLength') != '') ? $this->input->post('iDisplayLength') : '' );
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
        $xSearch = ($this->input->get('sSearch') != "") ? $this->input->get('sSearch') : (($this->input->post('sSearch')) ? $this->input->post('sSearch') : '' );
        if ($xSearch != "") {
            $sWhere0 = "AND ("; //"WHERE (";
            for ($i = 0; $i < (count($aColumns) - 1); $i++) {
                $sWhere0 .= $aColumns[$i] . " LIKE '%" . $xSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere0, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < (count($aColumns) - 1); $i++) {
            $xSearchable = ($this->input->get('bSearchable_' . $i) != "") ? $this->input->get('bSearchable_' . $i) : (($this->input->post('bSearchable_' . $i)) ? $this->input->post('bSearchable_' . $i) : '' );
            $xSearch = ($this->input->get('sSearch_' . $i) != "") ? $this->input->get('sSearch_' . $i) : (($this->input->post('sSearch_' . $i)) ? $this->input->post('sSearch_' . $i) : '' );
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
        $sQuery = "SELECT category,agenda_id,lang_id,name,is_published,date_published,DATE_FORMAT(date_created,'%d/%m/%Y') as date_created,dept,description, DATE_FORMAT(time_start,'%d/%m/%Y %H:%i:%s') AS time_start,$kolom, $kolomplus 
                     FROM
                         $sTable
                    WHERE 1=1 $sWhere $whereapprove 
                    GROUP BY category,agenda_id,moderator_approval,date_published,date_created,editor_approval,is_published 
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

        $xEcho = ($this->input->get('sEcho') != "") ? $this->input->get('sEcho') : (($this->input->post('sEcho')) ? $this->input->post('sEcho') : '' );
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
            $row['DT_RowId'] = 'row_' . $aRow['agenda_id'];
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
        $konten['page'] = 'agenda/approvement';
        $konten['nav'] = 'agenda';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function getForm() {
        $param = ($this->uri->segment(3) == '') ? '' : $this->uri->segment(3);
        $paramid = ($this->uri->segment(4) == '') ? '' : $this->uri->segment(4);
        $optional = ($this->uri->segment(5) == '') ? '' : $this->uri->segment(5);
        $konten['fedit'] = false;
        $konten['param_id'] = '';
        $konten['optional'] = $optional;
        if ($param == '') {
            /* buat baru  */
        } else {
            $konten['fedit'] = true;
            $konten['param_id'] = $paramid;
            $konten['rowdata'] = $this->agenda_model->getAgendaRow($paramid);
        }
        $this->db->flush_cache();
        $agendacat = $this->db->select('category_id,category')->from(DB_AGENDA_CAT)->group_by('category')->order_by('ordered')->get();
        $konten['rowcat'] = $agendacat->result();
        $this->db->flush_cache();
        $agendastat = $this->db->from(DB_STATUS)->get();
        $konten['rowstat'] = $agendastat->result();

        $konten['page'] = 'agenda/form';
        $konten['nav'] = 'agenda';
        $this->load->view('dashboard', array('data' => $konten));
    }

    function saveForm() {
        if ($this->input->post('_edit') == 1) {
            $this->updateForm();
        } else {
            $status_agenda = '';
            if ($this->input->post('name')) {
                $title_indo = $this->input->post('name');
                $dept_indo = $this->input->post('dept');
                $content_indo = $this->input->post('description');
                $cat_id_indo = $this->input->post('category');
                $time_start = date('Y/m/d H:i:s', strtotime($this->input->post('time_start') . ' ' . $this->input->post('timepicker')));
                $date_created = date('Y/m/d H:i:s', strtotime($this->input->post('date_created')));
                $publish_date = date('Y/m/d H:i:s', strtotime($this->input->post('publish_date')));
                $status_agenda = $this->input->post('status_agenda');
            }

            switch ($status_agenda) {
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

            if ($this->input->post('_aid') != '') {
                $next_id = $this->input->post('_aid');
            } else {
                $last_id_agenda_in_db = $this->agenda_model->getLastIdDb();
                $next_id = $last_id_agenda_in_db['agenda_id'] + 1;
            }
            if (isset($title_indo) && $title_indo != '') {
                $data = array(
                    'agenda_id' => $next_id,
                    'lang_id' => 1,
                    'name' => $title_indo,
                    'dept' => $dept_indo,
                    'description' => $content_indo,
                    'category' => $cat_id_indo,
                    'time_start' => $time_start,
                    'editor_approval' => $editor,
                    'moderator_approval' => $moderator,
                    'is_published' => $is_published,
                    'date_published' => ($publish_date == '') ? NULL : $publish_date,
                    'date_created' => ($date_created == '') ? date('Y/m/d h:i:s') : $date_created,
                    'user_created' => $_SESSION['username']);
                $this->agenda_model->simpanAgenda($data);
            }
        };
        redirect('kuwu/agenda');
    }

    function updateForm() {
        $id = $this->input->post('_aid');
        $lang = $this->input->post('_lang');

        $title_indo = $this->input->post('name');
        $dept_indo = $this->input->post('dept');
        $content_indo = $this->input->post('description');
        $cat_id_indo = $this->input->post('category');
        $time_start = date('Y/m/d H:i:s', strtotime($this->input->post('time_start') . ' ' . $this->input->post('timepicker')));
        $date_created = date('Y/m/d H:i:s', strtotime($this->input->post('date_created')));
        $publish_date = date('Y/m/d H:i:s', strtotime($this->input->post('publish_date')));
        $status_agenda = $this->input->post('status_agenda');

        switch ($status_agenda) {
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
            'lang_id' => 1,
            'name' => $title_indo,
            'dept' => $dept_indo,
            'description' => $content_indo,
            'category' => $cat_id_indo,
            'time_start' => $time_start,
            'editor_approval' => $editor,
            'moderator_approval' => $moderator,
            'is_published' => $is_published,
            'date_published' => ($publish_date == '') ? NULL : $publish_date,
            'date_created' => ($date_created == '') ? date('Y/m/d h:i:s') : $date_created,
            'user_created' => $_SESSION['username']);
        $this->agenda_model->updateAgenda($id, 1, $data);
    }

    function removeData() {
        if (IS_AJAX) {
            $this->agenda_model->deleteAgenda($this->input->post('cid'), $this->input->post('langid'));
        }
    }

    function unPublish() {
        if (IS_AJAX) {
            $data = array('is_published' => 0, 'date_published' => NULL);
            $this->agenda_model->updateAgenda($this->input->post('cid'), 1, $data);
            
        }
    }

    function approveAgenda() {
        switch ($this->uri->segment(4)):
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
        $this->agenda_model->updateAgenda($this->input->post('cid'), 1, $data);
    }

    function unapproveAgenda() {
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
        $this->agenda_model->updateAgenda($this->input->post('cid'), 1, $data);
    }

}
